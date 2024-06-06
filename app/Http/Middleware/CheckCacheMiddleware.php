<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        if (!config('app.enable_redis_cache')) {
            Log::warning('Disabled Redis cache');

            return $next($request);
        }

        $cacheKey = $this->generateCacheKey($request);
        if (Cache::has($cacheKey)) {
            return response(Cache::get($cacheKey));
        }

        return $next($request);
    }
}
