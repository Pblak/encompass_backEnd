<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MultiGuardSanctumMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $currentGuard = null;
        foreach ($guards as $guard) {
            if (auth($guard)->check()) {
                $currentGuard = $guard;
                auth()->shouldUse($guard);
                break;
            }
        }
        if ($currentGuard) {
            $request->attributes->set('currentGuard', $currentGuard);
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
