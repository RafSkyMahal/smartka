@extends('layouts.admin')

@section('title', 'Paket Latihan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-white">Paket Latihan</h1>
        <a href="{{ route('admin.paket.create') }}"
           class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            + Tambah Paket
        </a>
    </div>

    <div class="bg-gray-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700 text-left">
                    <th class="px-5 py-3 text-gray-400 font-medium">Judul</th>
                    <th class="px-5 py-3 text-gray-400 font-medium">Kelas</th>
                    <th class="px-5 py-3 text-gray-400 font-medium">Jumlah Soal</th>
                    <th class="px-5 py-3 text-gray-400 font-medium">Tipe</th>
                    <th class="px-5 py-3 text-gray-400 font-medium">Status</th>
                    <th class="px-5 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($packages as $package)
                    <tr class="hover:bg-gray-750 transition">
                        <td class="px-5 py-3 text-white font-medium">{{ $package->title }}</td>
                        <td class="px-5 py-3 text-gray-300">Kelas {{ $package->grade_level }}</td>
                        <td class="px-5 py-3 text-gray-300">{{ $package->questions_count ?? $package->questions->count() }} soal</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $package->type === 'premium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-600 text-gray-300' }}">
                                {{ $package->type === 'premium' ? 'Premium' : 'Gratis' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $package->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <a href="#" class="text-indigo-400 hover:text-indigo-300 text-xs font-medium">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                            Belum ada paket latihan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="text-gray-500 text-xs">Total: {{ $packages->total() }} paket</div>
    {{ $packages->links() }}
</div>
@endsection