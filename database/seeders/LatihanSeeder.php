<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\TestPackage;
use App\Models\Question;
use App\Models\TestPackageQuestion;

class LatihanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        // --- KELAS 6 SD ---
        $subjectMTK6 = Subject::where('name', 'Matematika')->where('class_level', '6')->first();
        if ($subjectMTK6) {
            $packageSD = TestPackage::create([
                'name' => 'Latihan Soal Matematika Dasar SD',
                'description' => 'Paket latihan ini berisi kombinasi soal pilihan ganda dan isian (essay) untuk melatih kemampuan dasar matematika kamu.',
                'class_level' => '6',
                'total_questions' => 2,
                'duration_minutes' => 30,
                'type' => 'free',
                'is_randomized' => false,
                'status' => 'published',
                'created_by' => $admin->id,
            ]);

            $q1 = Question::create([
                'subject_id' => $subjectMTK6->id,
                'topic_id' => $subjectMTK6->topics()->firstOrCreate(['name' => 'Operasi Hitung Campuran', 'order_number' => 1])->id,
                'class_level' => '6',
                'difficulty' => 'easy',
                'type' => 'multiple_choice',
                'question_text' => '<p>Berapakah hasil dari <strong>25 &times; 4 + 50</strong>?</p>',
                'option_a' => '150',
                'option_b' => '100',
                'option_c' => '200',
                'option_d' => '120',
                'correct_answer' => 'a',
                'explanation_text' => 'Berdasarkan aturan operasi hitung campuran (KuKaBaTaKu - Kurung, Kali/Bagi, Tambah/Kurang), perkalian dikerjakan lebih dulu.<br>25 &times; 4 = 100<br>100 + 50 = 150',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            $q2 = Question::create([
                'subject_id' => $subjectMTK6->id,
                'topic_id' => $subjectMTK6->topics()->firstOrCreate(['name' => 'Bangun Datar', 'order_number' => 2])->id,
                'class_level' => '6',
                'difficulty' => 'medium',
                'type' => 'short_answer',
                'question_text' => '<p>Sebuah segitiga siku-siku memiliki panjang alas 10 cm dan tinggi 5 cm. Berapakah luas segitiga tersebut dalam cm&sup2;? Tuliskan hanya angkanya saja.</p>',
                'correct_answer' => '25',
                'explanation_text' => 'Rumus luas segitiga = &frac12; &times; alas &times; tinggi.<br>Luas = &frac12; &times; 10 &times; 5 = 5 &times; 5 = 25 cm&sup2;.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            TestPackageQuestion::insert([
                ['test_package_id' => $packageSD->id, 'question_id' => $q1->id, 'order_number' => 1],
                ['test_package_id' => $packageSD->id, 'question_id' => $q2->id, 'order_number' => 2],
            ]);
        }

        // --- KELAS 9 SMP ---
        $subjectIPA9 = Subject::where('name', 'IPA')->where('class_level', '9')->first();
        if ($subjectIPA9) {
            $packageSMP = TestPackage::create([
                'name' => 'Latihan Sains Biologi SMP',
                'description' => 'Uji pemahamanmu tentang sel dan sistem biologi. Ada soal pilihan ganda dan essay!',
                'class_level' => '9',
                'total_questions' => 2,
                'duration_minutes' => 45,
                'type' => 'free',
                'is_randomized' => false,
                'status' => 'published',
                'created_by' => $admin->id,
            ]);

            $q1 = Question::create([
                'subject_id' => $subjectIPA9->id,
                'topic_id' => $subjectIPA9->topics()->firstOrCreate(['name' => 'Sel dan Organel', 'order_number' => 1])->id,
                'class_level' => '9',
                'difficulty' => 'medium',
                'type' => 'multiple_choice',
                'question_text' => '<p>Organel sel yang berfungsi sebagai tempat berlangsungnya respirasi selular dan menghasilkan energi (ATP) adalah...</p>',
                'option_a' => 'Nukleus',
                'option_b' => 'Ribosom',
                'option_c' => 'Mitokondria',
                'option_d' => 'Badan Golgi',
                'correct_answer' => 'c',
                'explanation_text' => 'Mitokondria sering disebut sebagai "the powerhouse of the cell" karena fungsinya menghasilkan energi ATP melalui proses respirasi selular.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            $q2 = Question::create([
                'subject_id' => $subjectIPA9->id,
                'topic_id' => $subjectIPA9->topics()->firstOrCreate(['name' => 'Fotosintesis', 'order_number' => 2])->id,
                'class_level' => '9',
                'difficulty' => 'hard',
                'type' => 'short_answer',
                'question_text' => '<p>Sebutkan 3 komponen utama yang dibutuhkan oleh tumbuhan hijau untuk melakukan proses fotosintesis!</p>',
                'correct_answer' => 'Air, Karbon Dioksida, Cahaya Matahari',
                'explanation_text' => 'Fotosintesis membutuhkan Air (H2O) yang diserap akar, Karbon Dioksida (CO2) yang diserap melalui stomata, dan Cahaya Matahari yang ditangkap oleh klorofil.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            TestPackageQuestion::insert([
                ['test_package_id' => $packageSMP->id, 'question_id' => $q1->id, 'order_number' => 1],
                ['test_package_id' => $packageSMP->id, 'question_id' => $q2->id, 'order_number' => 2],
            ]);
        }

        // --- KELAS 12 SMA ---
        $subjectTPS12 = Subject::where('name', 'TPS')->where('class_level', '12')->first();
        if ($subjectTPS12) {
            $packageSMA = TestPackage::create([
                'name' => 'Latihan Penalaran Kuantitatif SMA/SMK',
                'description' => 'Persiapan SNBT! Kerjakan soal penalaran matematika dalam format pilihan ganda dan essay penalaran.',
                'class_level' => '12',
                'total_questions' => 2,
                'duration_minutes' => 60,
                'type' => 'free',
                'is_randomized' => false,
                'status' => 'published',
                'created_by' => $admin->id,
            ]);

            $q1 = Question::create([
                'subject_id' => $subjectTPS12->id,
                'topic_id' => $subjectTPS12->topics()->firstOrCreate(['name' => 'Aljabar Lanjut', 'order_number' => 1])->id,
                'class_level' => '12',
                'difficulty' => 'hard',
                'type' => 'multiple_choice',
                'question_text' => '<p>Diketahui bahwa <strong>x + y = 10</strong> dan <strong>x - y = 4</strong>. Berapakah nilai dari <strong>x&sup2; - y&sup2;</strong>?</p>',
                'option_a' => '14',
                'option_b' => '40',
                'option_c' => '24',
                'option_d' => '100',
                'option_e' => '16',
                'correct_answer' => 'b',
                'explanation_text' => 'Menggunakan identitas aljabar: x&sup2; - y&sup2; = (x + y)(x - y). Substitusikan nilainya: 10 &times; 4 = 40.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            $q2 = Question::create([
                'subject_id' => $subjectTPS12->id,
                'topic_id' => $subjectTPS12->topics()->firstOrCreate(['name' => 'Barisan dan Deret', 'order_number' => 2])->id,
                'class_level' => '12',
                'difficulty' => 'medium',
                'type' => 'short_answer',
                'question_text' => '<p>Jelaskan secara singkat dan tepat perbedaan antara barisan aritmatika dan barisan geometri!</p>',
                'correct_answer' => 'Barisan aritmatika memiliki selisih (beda) antar suku yang tetap, sedangkan barisan geometri memiliki rasio (pengali) antar suku yang tetap.',
                'explanation_text' => 'Barisan aritmatika terbentuk dengan menambahkan selisih konstan (b) pada setiap langkah. Barisan geometri terbentuk dengan mengalikan rasio konstan (r) pada setiap langkah.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            TestPackageQuestion::insert([
                ['test_package_id' => $packageSMA->id, 'question_id' => $q1->id, 'order_number' => 1],
                ['test_package_id' => $packageSMA->id, 'question_id' => $q2->id, 'order_number' => 2],
            ]);
        }
    }
}
