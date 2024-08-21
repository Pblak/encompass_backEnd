<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    public function handle($request, \Closure $next, ...$guards)
    {
        $guard = $guards[0] ?? null;

        if (Auth::guard($guard)->guest()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

}
