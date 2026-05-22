@extends('layouts.auth')
@section('title', 'Syarat & Ketentuan')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8" 
     x-data="{ accepted: localStorage.getItem('smartka_terms') === 'true' }">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Header --}}
        <div class="bg-blue-600 px-8 py-6 text-white text-center">
            <h1 class="text-2xl font-bold font-heading" style="font-family:'Plus Jakarta Sans',sans-serif">Syarat & Ketentuan Layanan</h1>
            <p class="text-blue-100 mt-2 text-sm">Terakhir diperbarui: {{ date('d M Y') }}</p>
        </div>

        {{-- Content --}}
        <div class="p-8 text-gray-600 text-sm space-y-6 leading-relaxed max-h-[60vh] overflow-y-auto">
            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">1. Pendahuluan</h3>
                <p>Selamat datang di SMARTKA (Smart Academic Learning Platform). Syarat dan Ketentuan ini mengatur penggunaan akses dan layanan di platform SMARTKA. Dengan mendaftar dan menggunakan platform kami, Anda menyetujui untuk terikat dengan seluruh syarat dan ketentuan yang berlaku.</p>
            </section>
            
            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">2. Akun Pengguna</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Anda wajib memberikan informasi data diri yang akurat, termasuk email dan jenjang kelas.</li>
                    <li>Satu akun hanya diperuntukkan bagi satu pengguna dan tidak boleh dipindahtangankan.</li>
                    <li>Anda bertanggung jawab menjaga kerahasiaan kata sandi akun Anda.</li>
                </ul>
            </section>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">3. Layanan AI Tutor</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Fitur AI Tutor disediakan menggunakan Google Gemini 2.0 Flash. Hasil atau jawaban yang diberikan oleh AI tidak selalu mutlak benar dan pengguna disarankan untuk tetap melakukan verifikasi ulang pada buku materi resmi.</li>
                    <li>Pengguna dilarang menggunakan *prompt* yang mengandung kekerasan, pelecehan, atau melanggar hukum dalam bentuk apa pun.</li>
                    <li>Kami memiliki batas akses kuota AI harian (*Daily Limit*) untuk pengguna Free, yang dapat berubah sewaktu-waktu sesuai kebijakan.</li>
                </ul>
            </section>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">4. Pembelian dan Langganan (Premium)</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Biaya berlangganan Premium bersifat final dan *non-refundable* (tidak dapat dikembalikan) kecuali ada kesalahan teknis dari sisi kami yang divalidasi oleh sistem.</li>
                    <li>Status keanggotaan Premium berlaku sesuai durasi paket yang Anda beli.</li>
                </ul>
            </section>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-2">5. Hak Kekayaan Intelektual</h3>
                <p>Seluruh konten termasuk bank soal, desain UI/UX, dan materi pembelajaran di platform SMARTKA dilindungi oleh Hak Cipta. Anda tidak diperkenankan untuk menyalin, mendistribusikan, atau menjual kembali (*resell*) materi yang ada di platform ini.</p>
            </section>
        </div>

        {{-- Footer Action --}}
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-100">
            <div class="flex items-start gap-3 mb-6">
                <input type="checkbox" id="agree_terms" 
                       x-model="accepted"
                       @change="localStorage.setItem('smartka_terms', accepted)"
                       class="mt-1 w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                <label for="agree_terms" class="text-sm font-medium text-gray-700 cursor-pointer select-none">
                    Saya telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan Layanan SMARTKA.
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
