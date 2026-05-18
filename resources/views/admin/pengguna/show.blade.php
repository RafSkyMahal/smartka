@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pengguna.index') }}" class="text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-bold text-white">Detail Pengguna</h1>
    </div>

    {{-- Profil --}}
    <div class="bg-gray-800 rounded-2xl p-6 flex items-center gap-5">
        <div class="w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center text-2xl font-bold text-white">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
            <div class="flex gap-2 mt-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                    {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-400' : 'bg-indigo-500/20 text-indigo-400' }}">
                    {{ ucfirst($user->role) }}
                </span>
                @if($user->isPremium())
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400">
                        Premium
                    </span>
                @endif
                @if($user->suspended_at ?? false)
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500/20 text-red-400">
                        Suspended
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="bg-gray-800 rounded-2xl p-6 grid grid-cols-2 gap-4">
        <div>
            <p class="text-xs text-gray-500 mb-1">Kelas</p>
            <p class="text-white font-medium">Kelas {{ $user->grade_level ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Bergabung</p>
            <p class="text-white font-medium">{{ $user->created_at->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Email Terverifikasi</p>
            <p class="text-white font-medium">{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Belum' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Rata-rata Skor</p>
            <p class="text-white font-medium">{{ number_format($user->getAverageScore(), 1) }}</p>
        </div>
    </div>

    {{-- Aksi --}}
    @if($user->role !== 'admin')
    <div class="bg-gray-800 rounded-2xl p-6 space-y-3">
        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Aksi Admin</h3>

        <form action="{{ route('admin.pengguna.upgrade', $user) }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                ⭐ Upgrade ke Premium (Manual)
            </button>
        </form>

        <form action="{{ route('admin.pengguna.suspend', $user) }}" method="POST"
              onsubmit="return confirm('Yakin suspend pengguna ini?')">
            @csrf
            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-500 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                🚫 Suspend Akun
            </button>
        </form>
    </div>
    @endif

    {{-- Riwayat Latihan --}}
    <div class="bg-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-4">Riwayat Latihan</h3>
        @if($user->results && $user->results->count() > 0)
            <div class="space-y-2">
                @foreach($user->results->take(10) as $result)
                    <div class="flex justify-between items-center py-2 border-b border-gray-700">
                        <span class="text-sm text-white">{{ $result->testPackage?->title ?? 'Latihan' }}</span>
                        <span class="text-sm font-bold {{ $result->score >= 70 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $result->score }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada riwayat latihan.</p>
        @endif
    </div>
</div>
@endsection