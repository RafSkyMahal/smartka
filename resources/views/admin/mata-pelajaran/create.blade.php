@extends('layouts.admin')
@section('title', 'Tambah Mata Pelajaran')
@section('page-title', 'Tambah Mata Pelajaran')

@section('content')
<div class="mb-6 flex justify-end">
    <a href="{{ route('admin.mata-pelajaran.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
        &larr; Kembali
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-3xl">
    <form action="{{ route('admin.mata-pelajaran.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Mata Pelajaran</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kelas -->
            <div>
                <label for="class_level" class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kelas</label>
                <select id="class_level" name="class_level" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('class_level') border-red-500 @enderror">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="6" {{ old('class_level') == '6' ? 'selected' : '' }}>Kelas 6 SD</option>
                    <option value="9" {{ old('class_level') == '9' ? 'selected' : '' }}>Kelas 9 SMP</option>
                    <option value="12" {{ old('class_level') == '12' ? 'selected' : '' }}>Kelas 12 SMA/SMK</option>
                </select>
                @error('class_level')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ikon -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Ikon (Emoji)</label>
                <input type="text" id="icon" name="icon" value="{{ old('icon', '📚') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('icon') border-red-500 @enderror">
                @error('icon')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warna Hex -->
            <div>
                <label for="color_hex" class="block text-sm font-medium text-gray-700 mb-2">Warna Utama (Hex)</label>
                <input type="color" id="color_hex" name="color_hex" value="{{ old('color_hex', '#1a56db') }}"
                    class="w-full h-12 border border-gray-200 rounded-xl px-2 py-1 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                @error('color_hex')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex flex-col justify-end">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                    <span class="text-sm font-medium text-gray-700">Aktifkan Mapel Ini</span>
                </label>
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                Simpan Mata Pelajaran
            </button>
        </div>
    </form>
</div>
@endsection
