<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class QueryLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Enable query logging
        DB::enableQueryLog();
        
        $response = $next($request);
        
        // Get all executed queries
        $queries = DB::getQueryLog();
        
        // Log slow queries (> 100ms)
        foreach ($queries as $query) {
            if ($query['time'] > 100) {
                Log::warning('Slow Query Detected', [
                    'query' => $query['query'],
                    'bindings' => $query['bindings'],
                    'time' => $query['time'] . 'ms',
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);
            }
        }
        
        // Log total query count and time
        $totalTime = array_sum(array_column($queries, 'time'));
        $queryCount = count($queries);
        
        if ($queryCount > 10) {
            Log::info('High Query Count', [
                'query_count' => $queryCount,
                'total_time' => $totalTime . 'ms',
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        }
        
        return $response;
    }
}