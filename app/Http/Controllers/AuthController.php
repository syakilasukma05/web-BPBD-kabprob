<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiteSetting;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('auth.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
            'captcha_code' => ['required'],
            'captcha_answer' => ['required'],
        ]);

        // Validate Captcha (case-insensitive)
        if (strtolower($request->captcha_answer) !== strtolower($request->captcha_code)) {
            return back()->withErrors([
                'captcha_answer' => 'Kode captcha yang Anda masukkan salah. Silakan coba lagi.',
            ])->onlyInput('email');
        }

        // Support login using Email or Username
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $authCredentials = [
            $loginType => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($authCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Record Activity Log
            ActivityLog::record('Login Sesi', 'Pengguna berhasil masuk ke panel administrator.');

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Username/Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::record('Logout Sesi', 'Pengguna keluar dari panel administrator.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
