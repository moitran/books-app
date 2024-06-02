<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheMiddleware extends AbstractCacheMiddleware
{
    public const CACHE_EXPIRATION_TIME = 3600;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return $response;
        }

        // Cache the response for future requests
        Cache::put(
            $this->generateCacheKey($request),
            $response->getContent(),
            self::CACHE_EXPIRATION_TIME
        );

        return $response;
    }
}
