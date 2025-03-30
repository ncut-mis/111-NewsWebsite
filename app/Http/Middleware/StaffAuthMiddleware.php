<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StaffAuthMiddleware
{
    public function handle($request, Closure $next, $guard = 'staff') // 修改 guard 為 'staff'
    {
        if (!Auth::guard($guard)->check()) {
            logger()->info('Unauthenticated user tried to access a protected route.');
            return redirect()->route('staff.login'); // 修改重定向路由為 'staff.login'
        }

        return $next($request);
    }
}
