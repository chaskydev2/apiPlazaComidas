<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
    return response()->json(['message' => 'Admin login view not available in API-only mode.']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/admin');
        }
    return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }

    public function dashboard()
    {
    return response()->json(['message' => 'Admin dashboard not available in API-only mode.']);
    }
}
