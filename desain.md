# SMARTKA — Smart Academic Learning Platform
## Master Project Documentation (Untuk dilanjutkan di Gemini CLI)

---

## IDENTITAS PROYEK

- **Nama**: SMARTKA (Smart Academic Learning Platform)
- **Tagline**: "Belajar Cerdas, Raih Prestasi Terbaik"
- **Target User**: Siswa kelas 6 SD, 9 SMP, 12 SMA/SMK + orang tua + admin/guru
- **Lokasi**: `D:\laragon\www\smartka\`
- **URL Dev**: `http://127.0.0.1:8000`
- **Akun Admin**: admin@smartka.id / admin123

---

## TECH STACK

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Blade + Tailwind CSS CDN + Alpine.js |
| Database | MySQL via Laragon |
| AI Tutor | Google Gemini API (gemini-2.0-flash) |
| Auth | Custom session-based (bukan Laravel Breeze/Jetstream) |
| Storage | Laravel local storage |
| Queue | Sync (development) |
| Cache | File driver |
| Session | File driver |

---

## KONSEP DESAIN UI/UX

### Brand Colors
Primer        : #1a56db  (Biru elektrik)
Aksen sukses  : #0e9f6e  (Teal/hijau)
Aksen premium : #f59e0b  (Kuning emas)
Background    : #ffffff  (Putih bersih)
Dark sidebar  : #111827  (Admin panel)
Text utama    : #111827
Text sekunder : #6b7280

### Typography
Heading : Plus Jakarta Sans (700, 800)
Body    : Inter (400, 500, 600)
Import  : Google Fonts CDN

### Prinsip Desain
1. **Modern Flat Design** — tidak ada shadow berlebihan, border tipis, radius konsisten
2. **Card-based Layout** — konten dalam card rounded-2xl dengan border border-gray-100
3. **Warna bermakna** — hijau=sukses/free, biru=primary/aksi, kuning=premium/warning, merah=error/danger
4. **Mobile-first** — semua layout pakai grid md:grid-cols-X, sidebar collapsible di mobile
5. **Micro-interaction** — hover state, transition-all, Alpine.js untuk interaksi tanpa reload
6. **Konsistensi spacing** — padding card p-6, gap antar section mb-6, gap grid gap-4 atau gap-6
7. **Empty state** — setiap tabel/list punya ilustrasi emoji + teks jika data kosong
8. **Loading state** — tombol disabled + spinner saat proses, typing indicator di AI chat

### Pola Komponen yang Dipakai
Tombol primer    : bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl
Tombol outline   : border border-blue-600 text-blue-600 hover:bg-blue-50 rounded-xl
Tombol danger    : bg-red-50 text-red-600 hover:bg-red-100 rounded-lg
Input field      : border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500
Card             : bg-white rounded-2xl border border-gray-100 shadow-sm p-6
Badge FREE       : bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full
Badge PREMIUM    : bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded-full
Badge POPULER    : bg-yellow-400 text-yellow-900 font-extrabold rounded-full
Alert sukses     : bg-green-50 border border-green-200 text-green-700 rounded-xl
Alert error      : bg-red-50 border border-red-200 text-red-700 rounded-xl
Alert info       : bg-blue-50 border border-blue-200 text-blue-700 rounded-xl

### Layout Struktur
Landing Page  : layouts/landing.blade.php (navbar transparan + sticky)
Auth Pages    : layouts/auth.blade.php (minimal, centered)
Student Area  : layouts/app.blade.php (sidebar 240px + topbar + main content)
Admin Area    : layouts/admin.blade.php (sidebar dark 240px + topbar + main)

### Sidebar Student (layouts/app.blade.php)

Logo SMARTKA
Avatar + nama + badge jenjang + status FREE/PREMIUM
Menu: Beranda | Latihan Soal | Try Out | Laporan | AI Tutor | Pembahasan | Peringkat | Pengaturan
Banner upgrade (jika free)
Tombol Keluar


### Sidebar Admin (layouts/admin.blade.php)

Logo + "Admin Panel"
Info admin
Menu: Dashboard | Bank Soal | Paket Latihan | Pengguna | AI Monitor
Link: Lihat Website
Tombol Keluar
Background: bg-gray-900 (dark)


---

## KONFIGURASI .env

```env
APP_NAME=SMARTKA
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartka_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@smartka.id
MAIL_FROM_NAME="SMARTKA"

GEMINI_API_KEY=your_gemini_key_here
GEMINI_MODEL=gemini-2.0-flash
AI_DAILY_FREE_LIMIT=5
```

---

## DATABASE — 16 TABEL

```sql
1.  users                 -- id, name, email, password, phone, role, class_level, avatar,
                          -- email_verified_at, otp_code, otp_expires_at,
                          -- subscription_status (free/premium/premium_plus),
                          -- subscription_ends_at, remember_token

2.  subscriptions         -- id, user_id, plan, start_date, end_date,
                          -- payment_status, amount, payment_method, transaction_id

3.  subjects              -- id, name, class_level, icon, color_hex, description, is_active

4.  topics                -- id, subject_id, name, description, order_number

5.  questions             -- id, subject_id, topic_id, class_level, difficulty,
                          -- type, question_text, question_image,
                          -- option_a/b/c/d/e, correct_answer,
                          -- explanation_text, explanation_video_url,
                          -- source, status, created_by

6.  test_packages         -- id, name, description, class_level, total_questions,
                          -- duration_minutes, type (free/premium), is_randomized,
                          -- available_from, available_until, status, created_by

7.  test_package_questions-- id, test_package_id, question_id, order_number

8.  user_sessions         -- id, user_id, test_package_id, started_at, finished_at,
                          -- status (ongoing/completed/abandoned), time_spent_seconds

9.  user_answers          -- id, session_id, question_id, selected_answer,
                          -- is_correct, is_marked, hint_used, time_spent_seconds

10. results               -- id, user_id, session_id, total_score, correct_count,
                          -- wrong_count, empty_count,
                          -- score_per_subject (JSON), weakness_topics (JSON)

11. user_activity_logs    -- id, user_id, action_type, detail (JSON)

12. payments              -- id, user_id, plan, amount, payment_method,
                          -- gateway_transaction_id, status, callback_payload (JSON), paid_at

13. ai_chat_sessions      -- id, user_id, title, subject, message_count

14. ai_chat_messages      -- id, session_id, role (user/model), content,
                          -- image_path, is_starred, feedback (helpful/not_helpful/null)

15. ai_daily_usage        -- id, user_id, date, count — UNIQUE(user_id, date)

16. settings              -- id, key (unique), value, description
                          -- (ai_system_prompt, ai_daily_free_limit, dll)
```

### Data Seeder yang Sudah Ada
Admin    : admin@smartka.id / admin123 (role=admin)
Subjects : 14 mata pelajaran (kelas 6=4 mapel, 9=5 mapel, 12=5 mapel)
Settings : ai_system_prompt, ai_daily_free_limit=5, maintenance_mode=false

---

## SEMUA MODEL (app/Models/)

```php
User.php
  - fillable: name, email, password, phone, role, class_level, avatar,
              otp_code, otp_expires_at, subscription_status, subscription_ends_at
  - relations: sessions(), results(), payments(), subscriptions(),
               aiSessions(), activityLogs(), aiDailyUsage()
  - helpers:
      isPremium(): bool
      isAdmin(): bool
      isStudent(): bool
      todayAiQuota(): int          // 999 jika premium, 0-5 jika free
      getAverageScore(): float
      getWeakTopics(): array
      getTotalAnswered(): int
      isOtpValid(otp): bool
      activatePremium(plan, months): void
      resetToFree(): void
      classLevelLabel (accessor)   // "Kelas 6 SD" / "Kelas 9 SMP" / "Kelas 12 SMA"
      subscriptionLabel (accessor) // "Premium" / "Premium Plus" / "Gratis"

Subject.php        -- hasMany topics, questions
Topic.php          -- belongsTo subject
Question.php       -- belongsTo subject, topic, createdBy
TestPackage.php    -- belongsToMany questions (pivot: order_number)
TestPackageQuestion.php
UserSession.php    -- belongsTo user, testPackage; hasMany answers; hasOne result
UserAnswer.php     -- belongsTo question, session
Result.php         -- belongsTo user, session; casts: score_per_subject[], weakness_topics[]
Payment.php        -- belongsTo user
Subscription.php   -- belongsTo user
AiChatSession.php  -- belongsTo user; hasMany messages
AiChatMessage.php  -- belongsTo session
AiDailyUsage.php   -- belongsTo user
UserActivityLog.php-- belongsTo user
Setting.php        -- static get(key, default): string
```

---

## SEMUA ROUTES (routes/web.php)

```php
// PUBLIC
GET  /                           → landing page (landing.index)
GET  /login                      → AuthController@showLogin
POST /login                      → AuthController@login
GET  /register                   → AuthController@showRegister
POST /register                   → AuthController@register
GET  /verify-otp                 → AuthController@showOtp
POST /verify-otp                 → AuthController@verifyOtp
POST /resend-otp                 → AuthController@resendOtp
GET  /forgot-password            → AuthController@showForgot
POST /forgot-password            → AuthController@sendReset
GET  /reset-password/{token}     → AuthController@showReset
POST /reset-password             → AuthController@resetPassword
POST /logout                     → AuthController@logout [auth]

// STUDENT [middleware: auth]
GET  /dashboard                  → StudentController@dashboard
GET  /premium                    → PremiumController@index
GET  /checkout/{plan}            → PaymentController@checkout
POST /payment/process            → PaymentController@process
GET  /payment/status/{id}        → PaymentController@status
GET  /ai/tutor                   → AiChatController@index
POST /ai/chat/send               → AiChatController@send [throttle:60,1]
POST /ai/chat/{message}/feedback → AiChatController@feedback
POST /ai/chat/{message}/star     → AiChatController@star
GET  /ai/sessions                → AiChatController@sessions
GET  /ai/sessions/{session}      → AiChatController@sessionMessages

// BELUM DIBUAT — perlu dilanjutkan
GET  /latihan                    → PackageController@index
GET  /latihan/{id}               → PackageController@show
GET  /latihan/{id}/mulai         → SessionController@start
POST /latihan/{id}/jawab         → SessionController@submitAnswer
POST /latihan/{id}/selesai       → SessionController@finish
GET  /latihan/{id}/hasil         → ResultController@show
GET  /laporan                    → ReportController@index
GET  /peringkat                  → LeaderboardController@index
GET  /akun                       → AccountController@show
POST /akun/update                → AccountController@update

// ADMIN [middleware: auth + admin]
GET  /admin/dashboard            → AdminController@dashboard
GET  /admin/soal                 → QuestionController@index
GET  /admin/soal/tambah          → QuestionController@create
POST /admin/soal                 → QuestionController@store
GET  /admin/soal/{q}/edit        → QuestionController@edit
PUT  /admin/soal/{q}             → QuestionController@update
DELETE /admin/soal/{q}           → QuestionController@destroy
GET  /admin/paket                → AdminPackageController@index
GET  /admin/paket/tambah         → AdminPackageController@create
POST /admin/paket                → AdminPackageController@store
GET  /admin/paket/{p}/edit       → AdminPackageController@edit
PUT  /admin/paket/{p}            → AdminPackageController@update
DELETE /admin/paket/{p}          → AdminPackageController@destroy
GET  /admin/pengguna             → AdminUserController@index
GET  /admin/pengguna/{user}      → AdminUserController@show
POST /admin/pengguna/{u}/suspend → AdminUserController@suspend
POST /admin/pengguna/{u}/upgrade → AdminUserController@upgrade
POST /admin/pengguna/{u}/reset-pass → AdminUserController@resetPassword
GET  /admin/ai-monitor           → AiMonitorController@index
PUT  /admin/settings/ai-prompt   → AiMonitorController@updatePrompt

// WEBHOOK
POST /payment/callback           → PaymentController@callback
```

---

## CONTROLLERS YANG SUDAH DIBUAT
app/Http/Controllers/
├── Auth/
│   └── AuthController.php         ✅ login, register, OTP, forgot, reset, logout
├── Student/
│   └── StudentController.php      ✅ dashboard
├── Admin/
│   ├── AdminController.php        ✅ dashboard
│   ├── QuestionController.php     ✅ CRUD soal
│   ├── AdminUserController.php    ✅ list, show, suspend, upgrade, resetPassword
│   ├── AiMonitorController.php    ✅ index, updatePrompt
│   └── AdminPackageController.php ✅ CRUD paket
├── AiChatController.php           ✅ index, send, feedback, star, sessions, sessionMessages
├── PremiumController.php          ✅ index
└── PaymentController.php          ✅ checkout, process, status, callback

### Controllers yang BELUM DIBUAT
Student/PackageController.php      ❌ daftar & detail paket latihan
Student/SessionController.php      ❌ mulai, submitAnswer, finish latihan
Student/ResultController.php       ❌ tampilkan hasil + analisis
Student/ReportController.php       ❌ laporan kemajuan
Student/LeaderboardController.php  ❌ peringkat nasional
Student/AccountController.php      ❌ profil & pengaturan akun
app/Services/ScoringService.php    ❌ autoscoring & analisis kelemahan

---

## VIEWS YANG SUDAH DIBUAT
resources/views/
├── layouts/
│   ├── landing.blade.php    ✅
│   ├── auth.blade.php       ✅
│   ├── app.blade.php        ✅ (sidebar student)
│   └── admin.blade.php      ✅ (sidebar dark admin)
├── components/
│   └── ai-chat-widget.blade.php  ✅ floating button
├── emails/
│   └── otp.blade.php        ✅
├── landing/
│   └── index.blade.php      ✅ full landing page
├── auth/
│   ├── login.blade.php      ✅
│   ├── register.blade.php   ✅
│   ├── verify-otp.blade.php ✅
│   ├── forgot-password.blade.php ✅
│   └── reset-password.blade.php  ✅
├── dashboard/
│   └── index.blade.php      ✅
├── ai-tutor/
│   └── index.blade.php      ✅
├── premium/
│   ├── index.blade.php      ✅
│   ├── checkout.blade.php   ✅
│   └── status.blade.php     ✅
└── admin/
├── dashboard.blade.php  ✅
├── soal/
│   ├── index.blade.php  ✅
│   ├── create.blade.php ✅
│   └── edit.blade.php   ❌ BELUM DIBUAT
├── paket/
│   ├── index.blade.php  ❌ BELUM DIBUAT
│   ├── create.blade.php ❌ BELUM DIBUAT
│   └── edit.blade.php   ❌ BELUM DIBUAT
├── pengguna/
│   ├── index.blade.php  ✅
│   └── show.blade.php   ❌ BELUM DIBUAT
└── ai-monitor/
└── index.blade.php  ✅

### Views yang BELUM DIBUAT
latihan/index.blade.php       ❌ daftar paket latihan
latihan/show.blade.php        ❌ detail paket
latihan/mulai.blade.php       ❌ sesi mengerjakan soal
latihan/hasil.blade.php       ❌ hasil + analisis + radar chart
laporan/index.blade.php       ❌ laporan kemajuan
peringkat/index.blade.php     ❌ leaderboard
akun/show.blade.php           ❌ profil user

---

## SERVICES YANG SUDAH DIBUAT
app/Services/
└── GeminiService.php   ✅
- buildSystemPrompt(context): string
- chat(history, userMessage, context, imageBase64): string
- testConnection(): bool

---

## MIDDLEWARE
app/Http/Middleware/
└── AdminMiddleware.php  ✅ (cek role === 'admin')

**PENTING**: AdminMiddleware sudah dibuat tapi belum pasti terdaftar di `bootstrap/app.php`.
Pastikan ada di sana:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

---

## BUG YANG SUDAH DIKETAHUI & PERLU DIFIX

### 1. Login Bermasalah
- Kemungkinan: redirect setelah login salah, atau middleware conflict
- Cek di `AuthController@login`: setelah `Auth::attempt()` berhasil, pastikan redirect ke `/dashboard` untuk student dan `/admin/dashboard` untuk admin
- Pastikan `routes/api.php` ada (buat file kosong jika tidak ada)

### 2. `routes/api.php` Missing
- **Fix**: Buat file `routes/api.php` kosong, ATAU hapus baris `api:` dari `bootstrap/app.php`

### 3. AdminMiddleware belum terdaftar
- **Fix**: Tambahkan alias di `bootstrap/app.php`

### 4. Semua link di sidebar masih `href="#"`
- Perlu diupdate ke route yang benar setelah semua controller dibuat

### 5. AI Tutor — `Attempt to read property "id" on null`
- **Penyebab**: Route `/ai/tutor` diakses tanpa login
- **Fix**: Pastikan semua route AI ada di dalam `Route::middleware('auth')->group()`

---

## FITUR YANG PERLU DILANJUTKAN (Prioritas)

### 🔴 KRITIS — Harus difix dulu
1. Fix login flow (redirect yang benar)
2. Daftarkan AdminMiddleware di bootstrap/app.php
3. Buat `routes/api.php` kosong

### 🟠 PENTING — Core feature belum ada
4. **Sesi Latihan Soal** — halaman mulai latihan, submit per soal, autosave
5. **Autoscoring** — ScoringService: hitung skor, weakness topics
6. **Hasil Latihan** — tampilkan skor, grade, breakdown, rekomendasi AI
7. **Admin: edit soal** — form edit soal yang belum ada
8. **Admin: manajemen paket** — view index, create, edit paket latihan
9. **Admin: detail pengguna** — halaman show user dengan riwayat

### 🟡 FITUR TAMBAHAN
10. Laporan kemajuan siswa
11. Leaderboard / peringkat
12. Halaman profil & pengaturan akun
13. Try Out dengan timer ketat
14. Pembahasan soal per item

---

## CARA MEMINTA LANJUTAN KE GEMINI

Gunakan format ini saat meminta lanjutan:
Saya sedang mengembangkan SMARTKA di Laravel 12 dengan Laragon.
[Paste bagian yang relevan dari dokumen ini]
Tolong buatkan [nama fitur] dengan ketentuan:

Ikuti konsep desain yang sudah ada (Tailwind + Alpine.js, card rounded-2xl, warna #1a56db)
Gunakan layout yang sesuai (layouts/app.blade.php untuk student, layouts/admin.blade.php untuk admin)
Konsisten dengan pola komponen yang sudah ada
[Requirement spesifik lainnya]


---

## CATATAN PENTING UNTUK GEMINI

1. **Jangan pakai npm/vite** — semua CSS pakai Tailwind CDN, JS pakai Alpine.js CDN
2. **Jangan pakai Laravel Breeze/Jetstream** — auth custom sendiri
3. **Session driver = file**, bukan database
4. **Cache driver = file**, bukan database  
5. **Queue = sync**, bukan redis/database
6. **Laragon**, bukan XAMPP — MySQL port 3306, root tanpa password
7. **PHP artisan serve** untuk development (`http://127.0.0.1:8000`)
8. **Selalu pakai `/** @var \App\Models\User $user */`** setelah `Auth::user()` untuk menghindari warning Intelephense
9. **Konsistensi nama route** — gunakan nama route yang sudah didefinisikan, jangan hardcode URL
10. **Semua form** pakai `@csrf`, method spoofing pakai `@method('PUT')` / `@method('DELETE')`