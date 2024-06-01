<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckCacheMiddleware extends AbstractCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cacheKey = $this->generateCacheKey($request);
        if (Cache::has($cacheKey)) {
            Log::info('cached data');

            return response(Cache::get($cacheKey));
        }

        return $next($request);
    }
}
