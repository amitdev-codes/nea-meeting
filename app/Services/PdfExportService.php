<?php

namespace App\Services;

use ReflectionMethod;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PdfExportService
{
    protected $pdfOptions;

    protected $dtClass = 'App\\DataTables\\';

    public function __construct()
    {
        $this->pdfOptions = config('pdf_export.pdf_options', []);
    }

    public function export($request)
    {
        try {
            ob_end_clean();

            // Retrieve dynamic inputs from request
            $dataTableClass = $request->input('class', config('pdf_export.default_datatable_class'));
            $viewName = $request->input('view', config('pdf_export.default_view'));

            // dd($this->dtClass, $dataTableClass);

            $dtClass = $this->dtClass . $dataTableClass;

            if (!class_exists($dtClass)) {
                throw new \Exception("DataTable class '{$dtClass}' does not exist.");
            }
            $dataTable = new $dtClass();

            // Dynamically resolve the model class
            $modelClass = $this->getModelClass($dataTable);
            if (!class_exists($modelClass)) {
                throw new \Exception("Model class '{$modelClass}' does not exist.");
            }

            $model = app($modelClass);
            if (!$model instanceof \Illuminate\Database\Eloquent\Model) {
                throw new \Exception("Invalid model class: '{$modelClass}'");
            }

            $columns = collect($dataTable->html()->getColumns())
                ->filter(fn(Column $column) => $column->exportable)
                ->pluck('title', 'data');

            $query = $dataTable->query(app($modelClass));

            $headers = $this->mapExportHeaders($columns->toArray());

            $media = $query->with('media')->get();

            // Dynamically match headers with data keys
            $data = $query->get()->map(function ($row) use ($columns, $media) {
                $mappedRow = [];
                foreach ($columns->toArray() as $headerKey => $headerValue) {
                    // $mappedRow[$headerValue] = $row->{$headerKey} ?? '';
                    // If the header key corresponds to the image column
                    if ($headerKey === 'image') {
                        foreach ($media as $m) {
                            // Get the URL of the first media item
                            $url =  $m->preview_url;
                            $imgHtml = '<img src="' . $url . '" border="0" width="50" class="img-thumbnail" align="center"/>';

                            $mappedRow[$headerValue] = $imgHtml ?? '';
                        }
                    } else {
                        // Otherwise, map the normal columns
                        $mappedRow[$headerValue] = $row->{$headerKey} ?? '';
                    }
                }

                return $mappedRow; // Return the full row as an associative array
            });

            dd($data);

            $title = $this->getTitle($modelClass);
            $pdfName = $request->input('filename', "{$title}_data.pdf");
            // Generate PDF
            $pdf = $this->generatePdf($viewName, compact('data', 'title'), $pdfName);

            // Return the PDF for streaming or download
            return response()->stream(function () use ($pdf) {
                echo $pdf;
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $pdfName . '"',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF: ' . $e->getMessage());

            return response()->json([
                'message' => 'PDF generation failed',
                'error_code' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function generatePdf(string $viewName, array $data, string $filename = 'document.pdf')
    {
        if (! View::exists($viewName)) {
            throw new \Exception("View '{$viewName}' not found.");
        }

        $pdf = SnappyPdf::loadView($viewName, $data);

        foreach ($this->pdfOptions as $option => $value) {
            $pdf->setOption($option, $value);
        }

        // Save to file temporarily to inspect
        // $pdf->save(storage_path('app/public/' . $filename));  // Save the PDF locally to check its validity

        // Use download temporarily to ensure the PDF is generated correctly
        return $pdf->download($filename);  // Use download to ensure it's working fine
    }

    public function mapExportHeaders(array $columns)
    {
        // Return only the values of the $columns array
        return array_values($columns);
    }

    public function getTitle($modelClass)
    {
        return $modelClass ? class_basename($modelClass) : 'PDF Export';
    }

    public function setOption(string $option, string $value)
    {
        $this->pdfOptions[$option] = $value;

        return $this;
    }

    public function setOptions(array $options)
    {
        $this->pdfOptions = array_merge($this->pdfOptions, $options);

        return $this;
    }

    public function getModelClass($dataTable)
    {

        $className = $dataTable;
        $methodName = 'query';

        $reflectionMethod = new ReflectionMethod($className, $methodName);
        $parameters = $reflectionMethod->getParameters();

        foreach ($parameters as $param) {
            if ($param->hasType() && ! $param->getType()->isBuiltin()) {
                $modelClass = $param->getType()->getName(); // Get the class name

                return $modelClass;
            }
        }
    }

    public function getAllPks($models)
    {
        $primaryKeys = $models->map(function ($model) {
            return $model->getKey();
        })->toArray();

        return $primaryKeys;
    }
}
