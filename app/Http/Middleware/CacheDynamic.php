<?php

namespace App\Http\Middleware;

use Auth;
use Cache;
use Closure;
use Response;

class CacheDynamic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uri = $request->getPathInfo();
        $userId = Auth::id();

        if (Cache::has($uri . '_' . $userId)) {
            $cached = Cache::get($uri . '_' . $userId);
            return Response::make($cached);
        } else {
            $response = $next($request);
            $content = $response->content();
            Cache::put($uri . '_' . $userId, $content, 10);
            return $response;
        }
    }
}
