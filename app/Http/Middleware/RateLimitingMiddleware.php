<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type = 'global'): Response
    {
        $config = config("rate_limiting.{$type}", config('rate_limiting.global'));
        
        if (!$config) {
            return $next($request);
        }

        $key = $this->generateKey($request, $type);
        $maxAttempts = $config['max_attempts'] ?? 100;
        $decayMinutes = $config['decay_minutes'] ?? 1;

        // Cek apakah IP diblokir
        if ($this->isIpBlocked($request)) {
            $blockedDuration = config('rate_limiting.ip.blocked_duration', 60);
            $message = config('rate_limiting.messages.ip_blocked', 'IP Anda telah diblokir.');
            $message = str_replace(':minutes', $blockedDuration, $message);
            
            return response()->json([
                'error' => 'IP Blocked',
                'message' => $message,
                'retry_after' => $blockedDuration * 60
            ], 429);
        }

        // Cek rate limit
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $message = $this->getRateLimitMessage($type, $seconds);
            
            // Log violation if monitoring is enabled
            if (config('rate_limiting.monitoring.log_violations', true)) {
                $this->logViolation($request, $type, $key);
            }
            
            // Block IP if too many violations
            $this->checkForIpBlocking($request, $key);
            
            return response()->json([
                'error' => 'Rate Limit Exceeded',
                'message' => $message,
                'retry_after' => $seconds
            ], 429);
        }

        // Record the attempt
        RateLimiter::hit($key, $decayMinutes * 60);
        
        // Update activity tracking
        $this->updateActivityTracking($request, $type);

        return $next($request);
    }

    /**
     * Generate rate limiting key based on request and type
     */
    private function generateKey(Request $request, string $type): string
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        
        switch ($type) {
            case 'login':
                $username = $request->input('username');
                return "rate_limit:login:{$ip}:{$username}";
                
            case 'api':
                $route = $request->route()?->getName() ?? $request->path();
                return "rate_limit:api:{$ip}:{$route}";
                
            case 'ip':
                return "rate_limit:ip:{$ip}";
                
            default:
                return "rate_limit:global:{$ip}";
        }
    }

    /**
     * Check if IP is blocked
     */
    private function isIpBlocked(Request $request): bool
    {
        $ip = $request->ip();
        $blockedKey = "rate_limit:blocked:{$ip}";
        
        return Cache::has($blockedKey);
    }

    /**
     * Get appropriate rate limit message
     */
    private function getRateLimitMessage(string $type, int $seconds): string
    {
        $messages = config('rate_limiting.messages', []);
        
        switch ($type) {
            case 'login':
                $message = $messages['login_too_many_attempts'] ?? 'Terlalu banyak percobaan login.';
                break;
            case 'api':
                $message = $messages['api_too_many_requests'] ?? 'Terlalu banyak request.';
                break;
            default:
                $message = $messages['global_limit_exceeded'] ?? 'Batas request terlampaui.';
        }
        
        return str_replace(':seconds', $seconds, $message);
    }

    /**
     * Log rate limiting violation
     */
    private function logViolation(Request $request, string $type, string $key): void
    {
        Log::warning('Rate limiting violation detected', [
            'type' => $type,
            'key' => $key,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Check if IP should be blocked based on violations
     */
    private function checkForIpBlocking(Request $request, string $key): void
    {
        $ip = $request->ip();
        $violationKey = "rate_limit:violations:{$ip}";
        
        // Increment violation count
        $violations = Cache::increment($violationKey, 1);
        Cache::put($violationKey, $violations, now()->addMinutes(60));
        
        // Block IP if too many violations
        $threshold = config('rate_limiting.monitoring.alert_threshold', 10);
        if ($violations >= $threshold) {
            $blockedDuration = config('rate_limiting.ip.blocked_duration', 60);
            $blockedKey = "rate_limit:blocked:{$ip}";
            
            Cache::put($blockedKey, true, now()->addMinutes($blockedDuration));
            
            Log::critical('IP blocked due to excessive rate limiting violations', [
                'ip' => $ip,
                'violations' => $violations,
                'blocked_until' => now()->addMinutes($blockedDuration)->toDateTimeString(),
            ]);
        }
    }

    /**
     * Update activity tracking for monitoring
     */
    private function updateActivityTracking(Request $request, string $type): void
    {
        if (!config('rate_limiting.monitoring.enabled', true)) {
            return;
        }
        
        $ip = $request->ip();
        $activityKey = "rate_limit:activity:{$ip}";
        
        $activity = Cache::get($activityKey, []);
        $activity[] = [
            'type' => $type,
            'timestamp' => now()->toDateTimeString(),
            'url' => $request->path(),
        ];
        
        // Keep only last 100 activities
        if (count($activity) > 100) {
            $activity = array_slice($activity, -100);
        }
        
        Cache::put($activityKey, $activity, now()->addHours(24));
    }
}
