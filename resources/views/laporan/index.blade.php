@extends('layouts.app')
@section('title', 'Laporan Belajar')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2" style="font-family:'Plus Jakarta Sans',sans-serif">Laporan Belajar</h1>
        <p class="text-gray-500">Pantau perkembangan dan statistik belajarmu di sini.</p>
    </div>
</div>

<!-- STATS CARDS -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="text-gray-400 text-sm font-semibold mb-1">Rata-rata Skor</div>
        <div class="text-3xl font-bold text-gray-800">{{ $averageScore }}</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="text-gray-400 text-sm font-semibold mb-1">Total Sesi Latihan</div>
        <div class="text-3xl font-bold text-blue-600">{{ $totalExercises }}</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="text-gray-400 text-sm font-semibold mb-1">Soal Dijawab</div>
        <div class="text-3xl font-bold text-green-600">{{ $totalAnswered }}</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="text-gray-400 text-sm font-semibold mb-1">Topik Lemah</div>
        <div class="text-3xl font-bold text-red-500">{{ count($weakTopics) }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- SUBJECT AVERAGES -->
    <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-6" style="font-family:'Plus Jakarta Sans',sans-serif">Rata-rata per Mata Pelajaran</h3>
        @if(empty($subjectAverages))
            <div class="flex flex-col items-center justify-center py-6 text-center">
                <span class="text-4xl mb-2">📉</span>
                <p class="text-gray-400 text-sm">Belum ada data nilai mata pelajaran.</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($subjectAverages as $subject => $avg)
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-semibold text-gray-700">{{ $subject }}</span>
                        <span class="font-bold text-gray-800">{{ $avg }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full transition-all duration-500" style="width: {{ $avg }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        
        <!-- WEAK TOPICS -->
        <h3 class="font-bold text-gray-800 mb-4 mt-8 pt-6 border-t border-gray-100" style="font-family:'Plus Jakarta Sans',sans-serif">Fokus Perbaikan</h3>
        @if(empty($weakTopics))
            <p class="text-gray-400 text-sm py-2">Belum ada topik yang terdeteksi lemah. Terus tingkatkan belajarmu!</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($weakTopics as $topic)
                    <span class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg border border-red-100 shadow-sm">{{ $topic }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <!-- HISTORY TABLE -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-6" style="font-family:'Plus Jakarta Sans',sans-serif">Riwayat Latihan Terakhir</h3>
        
        @if($resultsHistory->isEmpty())
            <div class="text-center py-10 bg-gray-50 rounded-xl border border-gray-100 border-dashed">
                <span class="text-4xl">📭</span>
                <p class="text-gray-500 mt-3 font-medium text-sm">Kamu belum menyelesaikan latihan apa pun.</p>
                <a href="{{ route('latihan.index') }}" class="inline-block mt-4 text-xs font-bold bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Mulai Latihan Pertama</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="py-3 px-4 font-semibold">Tanggal</th>
                            <th class="py-3 px-4 font-semibold">Paket Soal</th>
                            <th class="py-3 px-4 font-semibold text-center">Skor Akhir</th>
                            <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($resultsHistory as $res)
                        <tr class="border-b border-gray-50 hover:bg-blue-50 transition-colors group">
                            <td class="py-4 px-4 text-gray-500">{{ $res->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="py-4 px-4 font-medium text-gray-800">
                                {{ $res->session->testPackage->name ?? 'Paket Terhapus' }}
                                <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <span>⏱️</span> Waktu: {{ round($res->session->time_spent_seconds / 60) }} menit
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[3rem] px-2 py-1 rounded-md font-bold text-xs 
                                    {{ $res->total_score >= 75 ? 'bg-green-100 text-green-700 border border-green-200' : ($res->total_score >= 50 ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 'bg-red-100 text-red-700 border border-red-200') }}">
                                    {{ $res->total_score }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('latihan.hasil', $res->session_id) }}" class="inline-flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-600 font-semibold text-xs transition border border-transparent hover:border-blue-600 px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 focus:opacity-100">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
