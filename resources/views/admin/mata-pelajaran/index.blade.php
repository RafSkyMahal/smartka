@extends('layouts.admin')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')
@section('page-subtitle', 'Kelola data mata pelajaran')

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div>
      <label class="block text-xs text-gray-500 mb-1">Kelas</label>
      <select name="class_level" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Kelas</option>
        <option value="6" {{ request('class_level') === '6' ? 'selected' : '' }}>Kelas 6 SD</option>
        <option value="9" {{ request('class_level') === '9' ? 'selected' : '' }}>Kelas 9 SMP</option>
        <option value="12" {{ request('class_level') === '12' ? 'selected' : '' }}>Kelas 12 SMA/SMK</option>
      </select>
    </div>
    <div class="flex-1 min-w-48">
      <label class="block text-xs text-gray-500 mb-1">Cari Mata Pelajaran</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama mapel..."
        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
      Cari
    </button>
  </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
    <div>
        <h3 class="font-bold text-gray-800">Daftar Mata Pelajaran</h3>
        <span class="text-xs text-gray-400">{{ $subjects->total() }} mapel</span>
    </div>
    <a href="{{ route('admin.mata-pelajaran.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
        + Tambah Mapel
    </a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-100">
        <tr>
          <th class="text-left px-5 py-3 text-gray-500 font-medium w-16 text-center">Ikon</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Mata Pelajaran</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Kelas</th>
          <th class="text-center px-4 py-3 text-gray-500 font-medium">Status</th>
          <th class="text-center px-4 py-3 text-gray-500 font-medium">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($subjects as $s)
        <tr class="hover:bg-gray-50 transition">
          <td class="px-5 py-3.5 text-center text-2xl">
            {{ $s->icon }}
          </td>
          <td class="px-4 py-3.5">
            <div class="font-semibold text-gray-800" style="color: {{ $s->color_hex }}">{{ $s->name }}</div>
            <div class="text-gray-400 text-xs truncate max-w-xs">{{ $s->description }}</div>
          </td>
          <td class="px-4 py-3.5 text-gray-600 text-xs font-semibold">
            Kelas {{ $s->class_level }}
          </td>
          <td class="px-4 py-3.5 text-center">
            @if($s->is_active)
              <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold">Aktif</span>
            @else
              <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-semibold">Nonaktif</span>
            @endif
          </td>
          <td class="px-4 py-3.5">
            <div class="flex items-center justify-center gap-2">
              <a href="{{ route('admin.mata-pelajaran.edit', $s) }}"
                class="text-xs bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg font-medium transition">
                Edit
              </a>
              <form method="POST" action="{{ route('admin.mata-pelajaran.destroy', $s) }}" x-data @submit.prevent="if(confirm('Yakin ingin menghapus mapel ini?')) $el.submit()">
                @csrf
                @method('DELETE')
                <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg font-medium transition">
                  Hapus
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-6 py-12 text-center text-gray-400">
            <div class="text-4xl mb-2">📚</div>
            <div>Tidak ada mata pelajaran ditemukan</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">
    {{ $subjects->links() }}
  </div>
</div>
@endsection
