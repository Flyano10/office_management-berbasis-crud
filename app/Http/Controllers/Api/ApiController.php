<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * API Base Controller dengan common functionality
     */
    
    /**
     * Rate limiting untuk API endpoints
     */
    protected function checkRateLimit(Request $request, string $key = 'api', int $maxAttempts = 60): bool
    {
        $key = $key . ':' . ($request->user()?->id ?? $request->ip());
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return false;
        }
        
        RateLimiter::hit($key, 60); // 60 detik decay
        return true;
    }
    
    /**
     * Standard API response format
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ], $code);
    }
    
    /**
     * Standard API error response format
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString()
        ], $code);
    }
    
    /**
     * Validation error response
     */
    protected function validationErrorResponse($validator): JsonResponse
    {
        return $this->errorResponse(
            'Validation failed',
            422,
            $validator->errors()
        );
    }
    
    /**
     * Rate limit exceeded response
     */
    protected function rateLimitExceededResponse(): JsonResponse
    {
        return $this->errorResponse(
            'Too many requests. Please try again later.',
            429
        );
    }
    
    /**
     * Log API request
     */
    protected function logApiRequest(Request $request, string $action, array $data = []): void
    {
        Log::channel('api')->info('API Request', [
            'user_id' => $request->user()?->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => $action,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Get pagination data
     */
    protected function getPaginationData($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more_pages' => $paginator->hasMorePages()
        ];
    }
}