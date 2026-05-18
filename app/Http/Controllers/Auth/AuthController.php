<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // ─── Show Login ───────────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    // ─── Login ────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        /** @var User $user */
        $user = Auth::user();



        $request->session()->regenerate();

        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }

    // ─── Show Register ────────────────────────────────────────────
    public function showRegister()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.register');
    }

    // ─── Register ─────────────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'grade_level'           => 'required|in:6,9,12',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'grade_level' => $request->grade_level,
            'role'        => 'student',
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat!');
    }


    // ─── Show Forgot Password ─────────────────────────────────────
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    // ─── Send Reset Link ──────────────────────────────────────────
    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $token = Str::random(64);
        $user->update([
            'otp_code'       => $token,
            'otp_expires_at' => now()->addMinutes(30),
        ]);

        // Kirim email reset (pakai mail sederhana)
        $resetUrl = route('reset-password', ['token' => $token]) . '?email=' . urlencode($user->email);

        try {
            Mail::raw("Klik link ini untuk reset password SMARTKA kamu:\n\n{$resetUrl}\n\nLink berlaku 30 menit.", function ($m) use ($user) {
                $m->to($user->email)->subject('Reset Password SMARTKA');
            });
        } catch (\Exception $e) {
            // Kalau mail gagal, tetap lanjut (development mode)
        }

        return back()->with('success', 'Link reset password sudah dikirim ke email kamu.');
    }

    // ─── Show Reset Password ──────────────────────────────────────
    public function showReset(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // ─── Reset Password ───────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp_code', $request->token)
                    ->first();

        if (!$user || !$user->otp_expires_at || Carbon::now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['token' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        $user->update([
            'password'      => Hash::make($request->password),
            'otp_code'      => null,
            'otp_expires_at'=> null,
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.');
    }

    // ─── Logout ───────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }


}