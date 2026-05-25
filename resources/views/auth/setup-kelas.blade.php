@extends('layouts.auth')
@section('title', 'Pilih Jenjang Kelas')

@section('content')
<div class="min-h-screen flex">

  {{-- KIRI: Ilustrasi --}}
  <div class="hidden lg:flex w-3/5 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 flex-col items-center justify-center p-12 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>

    <div class="relative z-10 text-center text-white max-w-md">
      <div class="flex items-center justify-center mb-10">
        <img src="{{ asset('logo.png') }}" alt="SMARTKA Logo" class="h-24 w-auto object-contain brightness-0 invert">
      </div>

      <div class="w-24 h-24 bg-white/10 rounded-3xl mx-auto flex items-center justify-center mb-8">
        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        </svg>
      </div>

      <h2 class="text-3xl font-bold mb-4" style="font-family:'Plus Jakarta Sans',sans-serif">
        Satu Langkah Lagi<br>Menuju Prestasi!
      </h2>
      <p class="text-blue-200 text-lg leading-relaxed">
        Pilih jenjang kelas kamu agar kami bisa<br>
        menyiapkan soal yang paling sesuai.
      </p>

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

  {{-- KANAN: Form Pilih Kelas --}}
  <div class="w-full lg:w-2/5 flex items-center justify-center p-8 bg-white"
    x-data="{ class_level: '' }">

    <div class="w-full max-w-md">

      {{-- Logo mobile --}}
      <div class="flex items-center mb-8 lg:hidden justify-center">
        <img src="{{ asset('logo.png') }}" alt="SMARTKA Logo" class="h-16 w-auto object-contain">
      </div>

      {{-- Greeting --}}
      <div class="flex items-center gap-3 mb-6 p-4 bg-blue-50 rounded-2xl border border-blue-100">
        @if(Auth::user()->avatar)
        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
        @else
        <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        @endif
        <div>
          <div class="font-semibold text-gray-800 text-sm">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</div>
          <div class="text-xs text-gray-500">Berhasil masuk via Google</div>
        </div>
      </div>

      {{-- Info --}}
      @if(session('info'))
      <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">ℹ️</span>
        <span>{{ session('info') }}</span>
      </div>
      @endif

      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">⚠️</span>
        <span>{{ $errors->first() }}</span>
      </div>
      @endif

      <h1 class="text-2xl font-bold text-gray-800 mb-1" style="font-family:'Plus Jakarta Sans',sans-serif">Kamu Kelas Berapa?</h1>
      <p class="text-gray-500 text-sm mb-6">Pilih jenjang agar soal yang diberikan sesuai denganmu.</p>

      <form method="POST" action="{{ route('setup.kelas.save') }}">
        @csrf

        <div class="grid gap-4 mb-8">
          {{-- Kelas 6 --}}
          <div @click="class_level = '6'"
            :class="class_level === '6' ? 'border-blue-600 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300'"
            class="border-2 rounded-2xl p-5 cursor-pointer transition-all">
            <div class="flex items-center gap-4">
              <div class="text-4xl">🏫</div>
              <div class="flex-1">
                <div class="font-bold text-gray-800 text-base">Kelas 6 SD</div>
                <div class="text-xs text-gray-500 mt-0.5">Seleksi masuk SMP favorit</div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <span class="bg-blue-100 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Matematika</span>
                  <span class="bg-green-100 text-green-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">IPA</span>
                  <span class="bg-yellow-100 text-yellow-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">B. Indonesia</span>
                </div>
              </div>
              <div x-show="class_level === '6'" class="text-blue-600 text-xl">✅</div>
            </div>
          </div>

          {{-- Kelas 9 --}}
          <div @click="class_level = '9'"
            :class="class_level === '9' ? 'border-blue-600 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300'"
            class="border-2 rounded-2xl p-5 cursor-pointer transition-all">
            <div class="flex items-center gap-4">
              <div class="text-4xl">🏢</div>
              <div class="flex-1">
                <div class="font-bold text-gray-800 text-base">Kelas 9 SMP</div>
                <div class="text-xs text-gray-500 mt-0.5">Seleksi masuk SMA/SMK negeri</div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <span class="bg-blue-100 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Matematika</span>
                  <span class="bg-green-100 text-green-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">IPA</span>
                  <span class="bg-red-100 text-red-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">B. Inggris</span>
                </div>
              </div>
              <div x-show="class_level === '9'" class="text-blue-600 text-xl">✅</div>
            </div>
          </div>

          {{-- Kelas 12 --}}
          <div @click="class_level = '12'"
            :class="class_level === '12' ? 'border-blue-600 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300'"
            class="border-2 rounded-2xl p-5 cursor-pointer transition-all">
            <div class="flex items-center gap-4">
              <div class="text-4xl">🎓</div>
              <div class="flex-1">
                <div class="font-bold text-gray-800 text-base">Kelas 12 SMA/SMK</div>
                <div class="text-xs text-gray-500 mt-0.5">UTBK/SNBT & ujian mandiri PTN</div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <span class="bg-blue-100 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">TPS</span>
                  <span class="bg-yellow-100 text-yellow-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Literasi</span>
                  <span class="bg-purple-100 text-purple-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Saintek/Soshum</span>
                </div>
              </div>
              <div x-show="class_level === '12'" class="text-blue-600 text-xl">✅</div>
            </div>
          </div>
        </div>

        <input type="hidden" name="class_level" x-model="class_level">

        <button type="submit" :disabled="!class_level"
          class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3.5 rounded-xl transition text-sm shadow-md">
          Mulai Belajar →
        </button>
      </form>

    </div>
  </div>

</div>
@endsection
