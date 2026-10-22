<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
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

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('auth.forgot-password', compact('settings'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email administrator wajib diisi.',
            'email.email' => 'Format email administrator tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email administrator tidak ditemukan pada sistem.',
            ])->withInput();
        }

        // Send reset link email using standard Laravel Password Broker
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirimkan ke email administrator (pklasoy25@gmail.com).');
        }

        return back()->with('status', 'Link reset password telah dikirimkan ke email administrator (pklasoy25@gmail.com).');
    }

    public function showResetPassword(Request $request, $token)
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        $email = $request->email;
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('auth.reset-password', compact('token', 'email', 'settings'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email administrator wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            ActivityLog::record('Reset Password', 'Pengguna berhasil memperbarui password melalui pemulihan email.');
            return redirect()->route('login')->with('status', 'Password berhasil diperbarui! Silakan login dengan password baru Anda.');
        }

        // Direct fallback: if token in password_reset_tokens table exists for user email
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            ActivityLog::record('Reset Password', 'Pengguna berhasil memperbarui password melalui pemulihan email.');
            return redirect()->route('login')->with('status', 'Password berhasil diperbarui! Silakan login dengan password baru Anda.');
        }

        return back()->withErrors([
            'email' => 'Gagal memperbarui password. Silakan coba minta link reset password kembali.',
        ])->withInput();
    }
}
