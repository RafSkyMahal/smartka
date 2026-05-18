@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex">

  {{-- KIRI: Form Login --}}
  <div class="w-full lg:w-2/5 flex items-center justify-center p-8 bg-white z-10 shadow-2xl relative">
    <div class="w-full max-w-md">

      {{-- Logo mobile & desktop --}}
      <div class="flex items-center gap-2 mb-8 justify-center lg:justify-start">
        <span class="text-2xl">🚀</span>
        <span class="text-xl font-extrabold text-blue-600" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
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

      <form method="POST" action="{{ route('login.post') }}" x-data="{ showPass: false }">
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

        {{-- Submit --}}
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition text-sm shadow-md">
          Masuk Sekarang →
        </button>
      </form>

      <div class="my-6 flex items-center gap-3">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-xs text-gray-400">Keamanan Terjamin</span>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <p class="text-center text-xs text-gray-400 mt-6">
        Dengan masuk, kamu menyetujui
        <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> SMARTKA.
      </p>
    </div>
  </div>

  {{-- KANAN: Ilustrasi (Sedikit dibedakan dari Register) --}}
  <div class="hidden lg:flex w-3/5 bg-gradient-to-bl from-indigo-800 via-blue-700 to-blue-600 flex-col items-center justify-center p-12 relative overflow-hidden">
    {{-- Dekorasi background --}}
    <div class="absolute top-10 right-10 w-64 h-64 bg-white opacity-5 rounded-full blur-2xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>

    <div class="relative z-10 text-center text-white max-w-md">
      
      {{-- Ilustrasi emoji besar (berbeda dari register) --}}
      <div class="text-9xl mb-10 select-none drop-shadow-2xl hover:scale-110 transition-transform cursor-default">🔑</div>

      <h2 class="text-4xl font-bold mb-4 leading-tight" style="font-family:'Plus Jakarta Sans',sans-serif">
        Selamat Datang Kembali! ✨
      </h2>
      <p class="text-blue-100 text-lg leading-relaxed mb-8 opacity-90">
        Lanjutkan belajarmu hari ini. Ribuan soal, pembahasan detail, dan analisis AI sudah siap membantumu meraih nilai terbaik!
      </p>

      {{-- Testimonial / Highlight --}}
      <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 text-left inline-block w-full max-w-sm">
        <div class="flex gap-1 mb-2 text-yellow-400 text-sm">
          ★★★★★
        </div>
        <p class="text-sm text-white/90 italic mb-3">
          "Berkat SMARTKA, nilai try out aku naik drastis dan aku berhasil masuk ke sekolah impianku!"
        </p>
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-full bg-blue-400 flex items-center justify-center text-xs font-bold">A</div>
          <div>
            <div class="text-sm font-semibold">Andi S.</div>
            <div class="text-xs text-blue-200">Siswa Kelas 9</div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection