<?php

namespace App\Http\Middleware;

use App\Helpers\CookieHelper;
use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InvalidateAuthCookieIfGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [config('auth.defaults.guard')] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        return ApiResponse::error(
            'Unauthorized',
            401,
            null
        )->cookie(CookieHelper::forgetAuthCookie());
    }
}
