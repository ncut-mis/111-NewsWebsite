<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models\admins;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            
                // 獲取目前用戶的角色
            $role = Auth::guard('admin')->user()->role;

            // 根據角色進行重定向
            if ($role == 0) {
                return view('admin.reporter.index');
            } elseif ($role == 1) {
                return view('admin.editor.index');
            } 
        }

        return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.']);
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    
} 
