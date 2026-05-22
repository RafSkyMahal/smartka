@extends('layouts.admin')
@section('title', 'Topik / Bab')
@section('page-title', 'Manajemen Topik & Bab')
@section('page-subtitle', 'Kelola daftar bab untuk setiap mata pelajaran')

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div>
      <label class="block text-xs text-gray-500 mb-1">Mata Pelajaran</label>
      <select name="subject_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Mapel</option>
        @foreach($subjects as $s)
            <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                {{ $s->name }} (Kelas {{ $s->class_level }})
            </option>
        @endforeach
      </select>
    </div>
    <div class="flex-1 min-w-48">
      <label class="block text-xs text-gray-500 mb-1">Cari Topik/Bab</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul bab..."
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
        <h3 class="font-bold text-gray-800">Daftar Topik/Bab</h3>
        <span class="text-xs text-gray-400">{{ $topics->total() }} topik</span>
    </div>
    <a href="{{ route('admin.topik.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
        + Tambah Topik
    </a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-100">
        <tr>
          <th class="text-left px-5 py-3 text-gray-500 font-medium">Urutan</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Topik / Bab</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Mata Pelajaran</th>
          <th class="text-center px-4 py-3 text-gray-500 font-medium">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($topics as $t)
        <tr class="hover:bg-gray-50 transition">
          <td class="px-5 py-3.5 text-center font-bold text-gray-500">
            {{ $t->order_number }}
          </td>
          <td class="px-4 py-3.5">
            <div class="font-semibold text-gray-800">{{ $t->name }}</div>
          </td>
          <td class="px-4 py-3.5 text-gray-600">
            <span class="inline-flex items-center gap-1">
                {{ $t->subject->icon }} {{ $t->subject->name }} (Kelas {{ $t->subject->class_level }})
            </span>
          </td>
          <td class="px-4 py-3.5">
            <div class="flex items-center justify-center gap-2">
              <a href="{{ route('admin.topik.edit', $t) }}"
                class="text-xs bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg font-medium transition">
                Edit
              </a>
              <form method="POST" action="{{ route('admin.topik.destroy', $t) }}" x-data @submit.prevent="if(confirm('Yakin ingin menghapus topik ini?')) $el.submit()">
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
          <td colspan="4" class="px-6 py-12 text-center text-gray-400">
            <div class="text-4xl mb-2">📑</div>
            <div>Tidak ada topik/bab ditemukan</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">
    {{ $topics->links() }}
  </div>
</div>
@endsection
