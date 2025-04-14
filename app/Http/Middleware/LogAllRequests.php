<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class LogAllRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // -----------------------------------------------------------------------
        // Log request details
        // -----------------------------------------------------------------------
        $timestamp = Carbon::now()->toDateTimeString();

        // Collect response content if available
        $responseData = json_decode((string) $response->getContent(), true, 512) ?? [];

        // Prepare log data
        $logData = [
            'path'         => $request->getPathInfo(),
            'method'       => $request->getMethod(),
            'ip'           => $request->ip(),
            'http_version' => $_SERVER['SERVER_PROTOCOL'] ?? null,
            'timestamp'    => $timestamp,
            'headers'      => $this->extractHeaders($request),
            'user'         => $this->extractUserData($request),
            'request'      => $this->extractRequestData($request),
            'response'     => $this->extractResponseData($responseData),
        ];

        // Generate a unique log message identifier
        $logMessage = $this->generateLogMessage($request->getPathInfo());

        // Log the request details
        Log::debug($logMessage, array_filter($logData));

        return $response;
    }

    /**
     * Extract relevant headers from the request.
     */
    private function extractHeaders(Request $request): array
    {
        $headers = $request->header();

        return [
            'user-agent' => $headers['user-agent'][0] ?? null,
            'referer'    => $headers['referer'][0] ?? null,
            'origin'     => $headers['origin'][0] ?? null,
        ];
    }

    /**
     * Extract authenticated user data if available.
     */
    private function extractUserData(Request $request): ?array
    {
        if ($user = $request->user()) {
            return [
                'id'   => $user->id,
                'name' => $user->name,
            ];
        }

        return null;
    }

    /**
     * Extract request data while excluding sensitive keys.
     */
    private function extractRequestData(Request $request): ?array
    {
        $sensitiveKeys = ['password'];

        return count($request->all()) > 0
            ? $request->except($sensitiveKeys)
            : null;
    }

    /**
     * Extract specific fields from the response data.
     */
    private function extractResponseData(array $responseData): array
    {
        return array_filter([
            'message' => $responseData['message'] ?? null,
            'errors'  => $responseData['errors'] ?? null,
            'result'  => $responseData['result'] ?? null,
        ]);
    }

    /**
     * Generate a unique log message based on the request path.
     */
    private function generateLogMessage(string $path): string
    {
        return str_replace('/', '_', mb_trim($path, '/'));
    }
}
