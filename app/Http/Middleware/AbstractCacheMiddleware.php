<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

abstract class AbstractCacheMiddleware
{
    protected function generateCacheKey(Request $request): string
    {
        $queries = $request->query();
        sort($queries);

        return $request->path() . serialize($queries);
    }
}
