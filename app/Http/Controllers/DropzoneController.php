<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DropzoneController extends Controller
{
    /**
     * Upload a file to the temporary storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropzoneUpload(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
        
        try {
            $file = $request->file('file');
            
            // Validate file
            $this->validateFile($file);
            
            $filename = now()->timestamp . Str::random(8) . '.' . $file->getClientOriginalExtension();
            
            // Store in temp folder
            Storage::putFileAs('temp/dropzone/', $file, $filename);
            
            return response()->json([
                'name' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);
        } catch (Exception $e) {
            Log::error('Dropzone upload error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Delete a file from temporary storage or media library
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropzoneDelete(Request $request)
    {
        $fileName = $request->input('file_name');
        
        if (empty($fileName)) {
            return response()->json(['error' => 'No file name provided.'], 400);
        }
        
        try {
            $deleted = false;
            
            // Check if it's a temporary file
            if (Storage::exists('temp/dropzone/' . $fileName)) {
                $deleted = Storage::delete('temp/dropzone/' . $fileName);
                Log::info('Deleted temporary file: ' . $fileName);
            } 
            // Check if it's a media library item
            else {
                $media = Media::where('file_name', $fileName)->first();
                
                if ($media) {
                    $deleted = $media->delete();
                    Log::info('Deleted media file: ' . $fileName . ' (ID: ' . $media->id . ')');
                } else {
                    return response()->json(['error' => 'File not found in storage or media library.'], 404);
                }
            }
            
            if ($deleted) {
                return response()->json(['success' => true, 'file' => $fileName], 200);
            } else {
                return response()->json(['error' => 'Failed to delete the file.'], 500);
            }
        } catch (Exception $e) {
            Log::error('Dropzone delete error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Validate uploaded file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @throws \Exception
     */
    private function validateFile($file)
    {
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'text/plain',
            'application/octet-stream' // Allow generic type
        ];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        
        \Log::info('Detected MIME type: ' . $mimeType . ', Extension: ' . $extension); // Debug
        
        // Check if MIME type is allowed or if extension is valid when MIME is octet-stream
        if (!in_array($mimeType, $allowedMimes) || 
            ($mimeType === 'application/octet-stream' && !in_array($extension, $allowedExtensions))) {
            throw new Exception('Invalid file type. Allowed types: JPG, PNG, GIF, PDF, TXT');
        }
        
        if ($file->getSize() > $maxSize) {
            throw new Exception('File size exceeds the limit (5MB)');
        }
    }
}