@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Pengguna</h1>
        <p class="text-gray-600">Perbarui informasi untuk pengguna: <span class="font-semibold">{{ $user->name }}</span></p>
    </div>
    <a href="{{ route('admin.pengguna.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
        &larr; Kembali
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-3xl">
    <form action="{{ route('admin.pengguna.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
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
                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Siswa</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kelas (Khusus Siswa) -->
            <div x-data="{ role: '{{ old('role', $user->role) }}' }" @role-changed.window="role = $event.detail" x-show="role === 'student'">
                <label for="class_level" class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kelas</label>
                <select id="class_level" name="class_level"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('class_level') border-red-500 @enderror">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="6" {{ old('class_level', $user->class_level) == '6' ? 'selected' : '' }}>Kelas 6 SD</option>
                    <option value="9" {{ old('class_level', $user->class_level) == '9' ? 'selected' : '' }}>Kelas 9 SMP</option>
                    <option value="12" {{ old('class_level', $user->class_level) == '12' ? 'selected' : '' }}>Kelas 12 SMA/SMK</option>
                </select>
                @error('class_level')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="md:col-span-2">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru (Opsional)</label>
                <p class="text-xs text-gray-500 mb-2">Biarkan kosong jika tidak ingin mengubah password.</p>
                <input type="password" id="password" name="password" minlength="8"
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
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
