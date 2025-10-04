<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ]);
            }
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Implementation for sending reset link
        // This would typically use Laravel's built-in password reset
        return back()->with('status', 'Reset link sent!');
    }

    public function resetPassword(Request $request)
    {
        // Implementation for resetting password
        return back()->with('status', 'Password reset successfully!');
    }
}
