<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // ─── Redirect ke halaman Google ────────────────────────
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // ─── Handle callback dari Google ───────────────────────
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        // 1. Cari user berdasarkan google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            // Update avatar jika berubah
            if ($googleUser->getAvatar() && !$user->avatar) {
                $user->update(['avatar' => $googleUser->getAvatar()]);
            }
            Auth::login($user, true);
            request()->session()->regenerate();

            return $this->redirectAfterLogin($user);
        }

        // 2. Cari berdasarkan email (akun sudah ada, link google_id)
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $user->avatar ?? $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
            Auth::login($user, true);
            request()->session()->regenerate();

            return $this->redirectAfterLogin($user);
        }

        // 3. User baru — buat akun baru
        $user = User::create([
            'name'              => $googleUser->getName(),
            'email'             => $googleUser->getEmail(),
            'google_id'         => $googleUser->getId(),
            'avatar'            => $googleUser->getAvatar(),
            'password'          => null, // login via Google, tidak perlu password
            'role'              => 'student',
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);
        request()->session()->regenerate();

        // Arahkan ke halaman setup kelas karena Google tidak memberikan data ini
        return redirect()->route('setup.kelas')
            ->with('info', 'Selamat datang! Silakan pilih jenjang kelas kamu terlebih dahulu.');
    }

    // ─── Halaman Pilih Kelas (untuk user Google baru) ──────
    public function showSetupKelas()
    {
        /** @var User $user */
        $user = Auth::user();

        // Jika sudah punya class_level, langsung ke dashboard
        if ($user->class_level) {
            return redirect()->route('dashboard');
        }

        return view('auth.setup-kelas');
    }

    // ─── Simpan Pilihan Kelas ───────────────────────────────
    public function saveSetupKelas(Request $request)
    {
        $request->validate([
            'class_level' => 'required|in:6,9,12',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update(['class_level' => $request->class_level]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat bergabung dengan SMARTKA! 🎉');
    }

    // ─── Helper: redirect setelah login ────────────────────
    private function redirectAfterLogin(User $user)
    {
        // Jika student belum punya class_level
        if ($user->role === 'student' && !$user->class_level) {
            return redirect()->route('setup.kelas');
        }

        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
}
