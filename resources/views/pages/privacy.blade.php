@extends('layouts.auth')
@section('title', 'Kebijakan Privasi')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8" 
     x-data="{ accepted: localStorage.getItem('smartka_privacy') === 'true' }">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Header --}}
        <div class="bg-blue-600 px-8 py-6 text-white text-center">
            <h1 class="text-2xl font-bold font-heading" style="font-family:'Plus Jakarta Sans',sans-serif">Kebijakan Privasi</h1>
            <p class="text-blue-100 mt-2 text-sm">Terakhir diperbarui: {{ date('d M Y') }}</p>
        </div>

        {{-- Content --}}
        <div class="p-8 text-gray-600 text-sm space-y-6 leading-relaxed max-h-[60vh] overflow-y-auto">
            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">1. Pendahuluan</h3>
                <p>Di SMARTKA, privasi Anda adalah prioritas utama kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi data pribadi Anda saat menggunakan platform kami.</p>
            </section>
            
            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">2. Informasi yang Kami Kumpulkan</h3>
                <p>Kami mengumpulkan beberapa jenis informasi untuk memberikan pengalaman belajar terbaik, meliputi:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1">
                    <li><strong>Data Profil:</strong> Nama lengkap, alamat email, nomor telepon, jenjang kelas, dan *password* yang dienkripsi.</li>
                    <li><strong>Data Akademik:</strong> Histori try out, jawaban soal, nilai ujian, dan metrik pembelajaran.</li>
                    <li><strong>Data Interaksi AI:</strong> Transkrip percakapan (*chat logs*) antara Anda dan AI Tutor untuk keperluan evaluasi model AI dan keselamatan.</li>
                    <li><strong>Data Transaksi:</strong> Riwayat pembelian paket Premium (kami tidak menyimpan nomor kartu kredit/debit karena diproses oleh *Payment Gateway* pihak ketiga).</li>
                </ul>
            </section>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">3. Penggunaan Informasi</h3>
                <p>Data yang kami kumpulkan digunakan secara spesifik untuk:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1">
                    <li>Menyediakan analisis kelemahan (*weakness topics*) dan personalisasi materi pembelajaran.</li>
                    <li>Memproses verifikasi akun, otentikasi, dan kelancaran fitur langganan.</li>
                    <li>Meningkatkan *prompt* dan akurasi balasan AI Tutor pada sistem.</li>
                    <li>Berkomunikasi dengan Anda mengenai pembaruan layanan, informasi pembayaran, dan promosi relevan.</li>
                </ul>
            </section>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">4. Berbagi Data</h3>
                <p>Kami **tidak pernah menjual** data pribadi Anda kepada pihak ketiga. Kami hanya membagikan data Anda kepada pihak terpercaya untuk tujuan fungsional, seperti layanan *Payment Gateway* untuk transaksi dan API Provider (Google) untuk layanan AI Tutor, sesuai dengan standar kebijakan privasi mereka masing-masing.</p>
            </section>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">5. Keamanan Data</h3>
                <p>Kami menerapkan prosedur keamanan berlapis untuk melindungi data Anda dari akses tidak sah, termasuk enkripsi *password* menggunakan standar industri dan pengamanan lalu lintas data melalui SSL/TLS.</p>
            </section>
        </div>

        {{-- Footer Action --}}
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-100">
            <div class="flex items-start gap-3 mb-6">
                <input type="checkbox" id="agree_privacy" 
                       x-model="accepted"
                       @change="localStorage.setItem('smartka_privacy', accepted)"
                       class="mt-1 w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                <label for="agree_privacy" class="text-sm font-medium text-gray-700 cursor-pointer select-none">
                    Saya telah membaca, memahami, dan menyetujui Kebijakan Privasi SMARTKA.
                </label>
            </div>
            
            <button type="button" 
                    @click="window.history.back()"
                    :disabled="!accepted"
                    class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl transition text-sm flex items-center justify-center gap-2">
                <span>Setuju & Kembali</span>
                <span x-show="accepted">→</span>
            </button>
        </div>
    </div>
</div>
@endsection
