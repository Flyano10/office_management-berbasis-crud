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
        // Hanya aktifkan query logging di development
        if (app()->environment('local', 'development')) {
            DB::enableQueryLog();
        }
        
        $response = $next($request);
        
        // Hanya log query di development
        if (app()->environment('local', 'development')) {
            // Ambil semua query yang dieksekusi
            $queries = DB::getQueryLog();
            
            // Log query lambat (> 200ms) - threshold dinaikkan
            foreach ($queries as $query) {
                if ($query['time'] > 200) {
                Log::warning('Slow Query Detected', [
                    'query' => $query['query'],
                    'bindings' => $query['bindings'],
                    'time' => $query['time'] . 'ms',
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);
            }
            }
            
            // Log total jumlah query dan waktu
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
        }
        
        return $response;
    }
}