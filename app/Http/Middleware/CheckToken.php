<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('access_token')) {
            $request->headers->set('Authorization', sprintf('%s %s', 'Bearer', $request->get('access_token')));
        }
        return $next($request);
    }
}
