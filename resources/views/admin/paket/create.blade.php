@extends('layouts.admin')

@section('title', 'Tambah Paket Latihan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.paket.index') }}" class="text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-bold text-white">Tambah Paket Latihan</h1>
    </div>

    <form action="{{ route('admin.paket.store') }}" method="POST"
          class="bg-gray-800 rounded-2xl p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm text-gray-400 mb-1">Judul Paket</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   placeholder="Contoh: Latihan Matematika UNBK Kelas 9">
            @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Deskripsi</label>
            <textarea name="description" rows="3"
                      class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                      placeholder="Deskripsi singkat paket ini...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Jenjang Kelas</label>
                <select name="grade_level" class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="6">Kelas 6 SD</option>
                    <option value="9" selected>Kelas 9 SMP</option>
                    <option value="12">Kelas 12 SMA/SMK</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Tipe</label>
                <select name="type" class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="free">Gratis</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Durasi (menit)</label>
                <input type="number" name="duration" value="{{ old('duration', 60) }}" min="5" max="300"
                       class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Status</label>
                <select name="is_active" class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-xl transition">
                Simpan Paket
            </button>
        </div>
    </form>
</div>
@endsection