<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class StaffAuthController extends Controller
{
    /**
     * 顯示登入表單。
     */
    public function showLoginForm()
    {
        return view('staff.auth.login');
    }

    /**
     * 處理登入邏輯，根據角色導向不同頁面。
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $role = Auth::guard('staff')->user()->role;

            if ($role == 0) {
                return redirect()->route('staff.reporter.news.writing');
            } elseif ($role == 1) {
                return redirect()->route('staff.editor.review');
            }
        }

        return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * 處理登出邏輯，並重導至登入頁面。
     */
    public function logout()
    {
        Auth::guard('staff')->logout();
        return redirect('/staff/login');
    }
}
