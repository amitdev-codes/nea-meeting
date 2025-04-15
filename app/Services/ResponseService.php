<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;


class ResponseService
{
    /**
     * Send a JSON response with dynamic status and message.
     *     *
     */
    public function sendJsonResponse(string $status, string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $status,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Send a redirect response for form requests.
     */
    public function sendRedirectResponse(string $routeName, string $status, string $message): RedirectResponse
    {
        return redirect()->route($routeName)->with($status, $message);
    }

    public function sendViewResponse(string $view): JsonResponse
    {
        return response()->json(['html' => $view]);
    }

    /**
     * Success response for creating a resource.
     */
    // public function successCreateResponse(string $resourceName): JsonResponse
    // {
    //     return $this->sendJsonResponse('success', "$resourceName created successfully.", 201);
    // }
    public function successCreateResponse(string $resourceName, bool $isAjax, string $routeName = ''): JsonResponse|RedirectResponse
    {
        $message = "$resourceName created successfully.";

        return $isAjax ? $this->sendJsonResponse('success', $message, 201) : $this->sendRedirectResponse($routeName, 'success', $message);
    }

    /**
     * Success response for updating a resource.
     */
    // public function successUpdateResponse(string $resourceName): JsonResponse
    // {
    //     return $this->sendJsonResponse('success', "$resourceName updated successfully.", 200);
    // }

    public function successUpdateResponse(string $resourceName, bool $isAjax, string $routeName = ''): JsonResponse|RedirectResponse
    {
        $message = "$resourceName updated successfully.";

        return $isAjax ? $this->sendJsonResponse('success', $message, 200) : $this->sendRedirectResponse($routeName, 'success', $message);
    }

    /**
     * Error response for failed creation.
     */
    // public function errorCreateResponse(string $resourceName): JsonResponse
    // {
    //     return $this->sendJsonResponse('error', "Failed to create $resourceName. Please try again.", 500);
    // }
    public function errorCreateResponse(string $resourceName, bool $isAjax, string $routeName = ''): JsonResponse|RedirectResponse
    {
        $message = "Failed to create $resourceName. Please try again.";

        return $isAjax ? $this->sendJsonResponse('error', $message, 500) : $this->sendRedirectResponse($routeName, 'error', $message);
    }

    /**
     * Error response for failed update.
     */
    // public function errorUpdateResponse(string $resourceName): JsonResponse
    // {
    //     return $this->sendJsonResponse('error', "Failed to update $resourceName. Please try again.", 500);
    // }
    public function errorUpdateResponse(string $resourceName, bool $isAjax, string $routeName = ''): JsonResponse|RedirectResponse
    {
        $message = "Failed to update $resourceName. Please try again.";

        return $isAjax ? $this->sendJsonResponse('error', $message, 500) : $this->sendRedirectResponse($routeName, 'error', $message);
    }

    /**
     * Error response for invalid ajax request.
     */
    public function errorInvalidRequestResponse(): JsonResponse
    {
        return $this->sendJsonResponse('error', 'Invalid ajax request!', 400);
    }

    // public function errorGenericResponse(string $resourceName, ?Throwable $exception = null): JsonResponse
    // {
    //     $errorMessage = "An error occurred while processing the $resourceName.";
    //     if ($exception) {
    //         \Log::error("Error processing $resourceName: ".$exception->getMessage());
    //     }

    //     return $this->sendJsonResponse('error', $errorMessage, 500);
    // }

    public function errorGenericResponse(string $resourceName, bool $isAjax, string $routeName = '', ?\Throwable $exception = null): JsonResponse|RedirectResponse
    {
        $message = "An error occurred while processing the $resourceName.";

        if ($exception) {
            Log::error("Error processing $resourceName: ".$exception->getMessage());
        }

        return $isAjax
            ? $this->sendJsonResponse('error', $message, 500)
            : $this->sendRedirectResponse($routeName, 'error', $message);
    }
}
