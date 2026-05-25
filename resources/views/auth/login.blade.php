@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex">

  {{-- KIRI: Ilustrasi --}}
  <div class="hidden lg:flex w-3/5 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 flex-col items-center justify-center p-12 relative overflow-hidden">
    {{-- Dekorasi background --}}
    <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>

    <div class="relative z-10 text-center text-white max-w-md">
      {{-- Logo --}}
      <div class="flex items-center justify-center mb-10">
        <img src="{{ asset('logo.png') }}" alt="SMARTKA Logo" class="h-24 w-auto object-contain brightness-0 invert">
      </div>

      {{-- Ilustrasi --}}
      <div class="w-24 h-24 bg-white/10 rounded-3xl mx-auto flex items-center justify-center mb-8">
        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
      </div>

      <h2 class="text-3xl font-bold mb-4" style="font-family:'Plus Jakarta Sans',sans-serif">
        Selamat Datang Kembali,<br>Pejuang Nilai!
      </h2>
      <p class="text-blue-200 text-lg leading-relaxed">
        Ribuan soal, analisis AI, dan try out menunggumu.<br>
        Yuk lanjutkan perjalanan belajarmu!
      </p>

      {{-- Stats --}}
      <div class="flex gap-6 justify-center mt-10">
        <div class="text-center">
          <div class="text-2xl font-bold">50K+</div>
          <div class="text-blue-300 text-sm">Siswa Aktif</div>
        </div>
        <div class="w-px bg-blue-400"></div>
        <div class="text-center">
          <div class="text-2xl font-bold">200K+</div>
          <div class="text-blue-300 text-sm">Soal Tersedia</div>
        </div>
        <div class="w-px bg-blue-400"></div>
        <div class="text-center">
          <div class="text-2xl font-bold">95%</div>
          <div class="text-blue-300 text-sm">Tingkat Lulus</div>
        </div>
      </div>
    </div>
  </div>

  {{-- KANAN: Form Login --}}
  <div class="w-full lg:w-2/5 flex items-center justify-center p-8">
    <div class="w-full max-w-md" x-data="{
        showPass: false,
        terms: localStorage.getItem('smartka_terms') === 'true',
        privacy: localStorage.getItem('smartka_privacy') === 'true'
      }">

      {{-- Logo mobile & desktop --}}
      <div class="flex items-center mb-8 justify-center lg:justify-start">
        <img src="{{ asset('logo.png') }}" alt="SMARTKA Logo" class="h-16 w-auto object-contain">
      </div>

      <h1 class="text-2xl font-bold text-gray-800 mb-1 lg:text-left text-center" style="font-family:'Plus Jakarta Sans',sans-serif">Masuk ke Akun</h1>
      <p class="text-gray-500 mb-8 text-sm lg:text-left text-center">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar gratis</a></p>

      {{-- Alert error --}}
      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">⚠️</span>
        <span>{{ $errors->first() }}</span>
      </div>
      @endif

      {{-- Alert success (dari logout/reset) --}}
      @if(session('success'))
      <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">✅</span>
        <span>{{ session('success') }}</span>
      </div>
      @endif

      {{-- Alert info (OTP) --}}
      @if(session('info'))
      <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">ℹ️</span>
        <span>{{ session('info') }}</span>
      </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
          <input
            type="email" name="email" value="{{ old('email') }}"
            placeholder="nama@email.com"
            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-400 bg-red-50 @enderror"
            required autofocus
          >
        </div>

        {{-- Password --}}
        <div class="mb-5">
          <div class="flex justify-between items-center mb-1.5">
            <label class="text-sm font-medium text-gray-700">Password</label>
            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Lupa password?</a>
          </div>
          <div class="relative">
            <input
              :type="showPass ? 'text' : 'password'"
              name="password"
              placeholder="Masukkan password"
              class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
            <button type="button" @click="showPass = !showPass"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
              <span x-show="!showPass">👁️</span>
              <span x-show="showPass">🙈</span>
            </button>
          </div>
        </div>

        {{-- Remember me --}}
        <div class="flex items-center mb-6">
          <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 rounded border-gray-300">
          <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
        </div>

        {{-- Checkboxes for Terms & Privacy --}}
        <div class="flex flex-col gap-3 mb-6">
          <div class="flex items-start gap-2">
            <input type="checkbox" id="login_terms" x-model="terms" @change="localStorage.setItem('smartka_terms', terms)" class="mt-0.5 w-4 h-4 text-blue-600 rounded border-gray-300">
            <label for="login_terms" class="text-sm text-gray-600">
              Saya menyetujui <a href="{{ route('terms') }}" class="text-blue-600 font-medium hover:underline">Syarat & Ketentuan</a> SMARTKA
            </label>
          </div>
          <div class="flex items-start gap-2">
            <input type="checkbox" id="login_privacy" x-model="privacy" @change="localStorage.setItem('smartka_privacy', privacy)" class="mt-0.5 w-4 h-4 text-blue-600 rounded border-gray-300">
            <label for="login_privacy" class="text-sm text-gray-600">
              Saya menyetujui <a href="{{ route('privacy') }}" class="text-blue-600 font-medium hover:underline">Kebijakan Privasi</a> SMARTKA
            </label>
          </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
          :disabled="!terms || !privacy"
          class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3.5 rounded-xl transition text-sm shadow-md">
          Masuk Sekarang →
        </button>
      </form>

      <div class="my-6 flex items-center gap-3">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-xs text-gray-400">atau lanjutkan dengan</span>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      {{-- Tombol Google --}}
      <a href="{{ route('auth.google') }}"
        class="flex items-center justify-center gap-3 w-full border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition text-sm shadow-sm">
        <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Masuk dengan Google
      </a>
    </div>
  </div>

</div>
@endsection