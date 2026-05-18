<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\TestPackage;
use App\Models\Question;
use App\Models\TestPackageQuestion;

class TryoutSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        // --- KELAS 12 SMA ---
        $subjectTPS12 = Subject::where('name', 'TPS')->where('class_level', '12')->first();
        if ($subjectTPS12) {
            $packageTO = TestPackage::create([
                'name' => 'Try Out Akbar SNBT 2026',
                'description' => 'Simulasi ujian sesungguhnya dengan timer ketat. Buktikan kemampuanmu di Try Out tingkat nasional ini!',
                'class_level' => '12',
                'total_questions' => 2,
                'duration_minutes' => 120, // 2 jam
                'type' => 'free',
                'is_randomized' => true,
                'status' => 'published',
                'created_by' => $admin->id,
            ]);

            $q1 = Question::create([
                'subject_id' => $subjectTPS12->id,
                'topic_id' => $subjectTPS12->topics()->firstOrCreate(['name' => 'Penalaran Umum', 'order_number' => 1])->id,
                'class_level' => '12',
                'difficulty' => 'hard',
                'type' => 'multiple_choice',
                'question_text' => '<p>Sebagian besar siswa yang rajin belajar lulus ujian. Budi rajin belajar. Kesimpulan yang paling tepat adalah...</p>',
                'option_a' => 'Budi pasti lulus ujian',
                'option_b' => 'Budi mungkin lulus ujian',
                'option_c' => 'Budi tidak lulus ujian',
                'option_d' => 'Budi bukan siswa',
                'option_e' => 'Tidak dapat ditarik kesimpulan',
                'correct_answer' => 'b',
                'explanation_text' => 'Karena hanya "sebagian besar" (bukan semua) siswa rajin yang lulus, maka Budi memiliki kemungkinan (mungkin) lulus, tetapi belum pasti.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);
            
            TestPackageQuestion::insert([
                ['test_package_id' => $packageTO->id, 'question_id' => $q1->id, 'order_number' => 1],
            ]);
        }
        
        // --- KELAS 9 SMP ---
        $subjectMTK9 = Subject::where('name', 'Matematika')->where('class_level', '9')->first();
        if ($subjectMTK9) {
            $packageTOSMP = TestPackage::create([
                'name' => 'Try Out Ujian Sekolah SMP',
                'description' => 'Persiapan ujian sekolah dengan soal terstandar nasional. Tersedia untuk jenjang kelas 9 SMP.',
                'class_level' => '9',
                'total_questions' => 1,
                'duration_minutes' => 90,
                'type' => 'free',
                'is_randomized' => true,
                'status' => 'published',
                'created_by' => $admin->id,
            ]);

            $q2 = Question::create([
                'subject_id' => $subjectMTK9->id,
                'topic_id' => $subjectMTK9->topics()->firstOrCreate(['name' => 'Aljabar', 'order_number' => 1])->id,
                'class_level' => '9',
                'difficulty' => 'hard',
                'type' => 'multiple_choice',
                'question_text' => '<p>Jika x = 2 dan y = 3, maka nilai dari 3x + 2y adalah...</p>',
                'option_a' => '10',
                'option_b' => '12',
                'option_c' => '13',
                'option_d' => '15',
                'option_e' => '16',
                'correct_answer' => 'b',
                'explanation_text' => '3(2) + 2(3) = 6 + 6 = 12.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);
            
            TestPackageQuestion::insert([
                ['test_package_id' => $packageTOSMP->id, 'question_id' => $q2->id, 'order_number' => 1],
            ]);
        }
        
        // --- KELAS 6 SD ---
        $subjectMTK6 = Subject::where('name', 'Matematika')->where('class_level', '6')->first();
        if ($subjectMTK6) {
            $packageTOSD = TestPackage::create([
                'name' => 'Try Out Kelulusan SD',
                'description' => 'Siapkan dirimu menghadapi ujian kelulusan kelas 6 SD dengan paket simulasi ini.',
                'class_level' => '6',
                'total_questions' => 1,
                'duration_minutes' => 60,
                'type' => 'free',
                'is_randomized' => false,
                'status' => 'published',
                'created_by' => $admin->id,
            ]);

            $q3 = Question::create([
                'subject_id' => $subjectMTK6->id,
                'topic_id' => $subjectMTK6->topics()->firstOrCreate(['name' => 'Aritmatika', 'order_number' => 1])->id,
                'class_level' => '6',
                'difficulty' => 'medium',
                'type' => 'short_answer',
                'question_text' => '<p>Berapakah akar kuadrat dari 144?</p>',
                'correct_answer' => '12',
                'explanation_text' => 'Akar kuadrat dari 144 adalah 12, karena 12 * 12 = 144.',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);
            
            TestPackageQuestion::insert([
                ['test_package_id' => $packageTOSD->id, 'question_id' => $q3->id, 'order_number' => 1],
            ]);
        }
    }
}
