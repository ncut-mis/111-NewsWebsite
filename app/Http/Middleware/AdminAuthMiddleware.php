<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::guard($guard)->check()) {
            logger()->info('Unauthenticated user tried to access a protected route.');
            return redirect()->route('admin.login'); // 未登入則重定向到登入頁面
        }

        return $next($request);
    }
}
