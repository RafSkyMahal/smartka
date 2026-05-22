@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Pengguna Baru</h1>
    <p class="text-gray-600">Isi formulir di bawah ini untuk menambahkan pengguna (Admin/Siswa).</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-3xl">
    <form action="{{ route('admin.pengguna.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Peran (Role)</label>
                <select id="role" name="role" required x-data x-on:change="$dispatch('role-changed', $event.target.value)"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Siswa</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kelas (Khusus Siswa) -->
            <div x-data="{ role: '{{ old('role', 'student') }}' }" @role-changed.window="role = $event.detail" x-show="role === 'student'">
                <label for="class_level" class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kelas</label>
                <select id="class_level" name="class_level"
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

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required minlength="8"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('admin.pengguna.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                Simpan Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
