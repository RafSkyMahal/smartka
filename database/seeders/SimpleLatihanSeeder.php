<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Subject;
use App\Models\TestPackage;
use App\Models\TestPackageQuestion;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimpleLatihanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Ambil admin untuk created_by
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            // Jangan buat user baru di seeder latihan minimal ini,
            // biar konsisten dengan seeder lain.
            return;
        }

        // --- 1) SUBJECT ---
        // Minimal: 1 materi yang jelas untuk latihan.
        $subject = Subject::updateOrCreate(
            [
                'name' => 'Matematika',
                'class_level' => '6',
            ],
            [
                'name' => 'Matematika',
                'class_level' => '6',
                'description' => 'Mata pelajaran Matematika',
                'icon' => 'calculator',
                'color_hex' => '#1a56db',
                'is_active' => true,
            ]
        );

        // --- 2) TOPIC ---
        $topic = Topic::updateOrCreate(
            [
                'subject_id' => $subject->id,
                'name' => 'Operasi Hitung Campuran',
            ],
            [
                'subject_id' => $subject->id,
                'name' => 'Operasi Hitung Campuran',
                'description' => 'Pelajaran tentang operasi hitung campuran',
                'order_number' => 1,
            ]
        );

        // --- 3) QUESTIONS ---
        // Pakai unique key sederhana: subject_id + topic_id + question_text
        $questionsPayload = [
            [
                'class_level' => '6',
                'difficulty' => 'easy',
                'type' => 'multiple_choice',
                'question_text' => '<p>Berapakah hasil dari <strong>25 &times; 4 + 50</strong>?</p>',
                'question_image' => null,
                'option_a' => '150',
                'option_b' => '100',
                'option_c' => '200',
                'option_d' => '120',
                'option_e' => null,
                'correct_answer' => 'a',
                'explanation_text' => 'Berdasarkan aturan operasi hitung campuran (KuKaBaTaKu - Kurung, Kali/Bagi, Tambah/Kurang), perkalian dikerjakan lebih dulu. <br>25 &times; 4 = 100<br>100 + 50 = 150',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
            ],
            [
                'class_level' => '6',
                'difficulty' => 'medium',
                'type' => 'short_answer',
                'question_text' => '<p>Sebuah segitiga siku-siku memiliki panjang alas 10 cm dan tinggi 5 cm. Berapakah luas segitiga tersebut dalam cm&sup2;? Tuliskan hanya angkanya saja.</p>',
                'question_image' => null,
                'option_a' => null,
                'option_b' => null,
                'option_c' => null,
                'option_d' => null,
                'option_e' => null,
                'correct_answer' => '25',
                'explanation_text' => 'Rumus luas segitiga = 1/2 &times; alas &times; tinggi. <br>Luas = 1/2 &times; 10 &times; 5 = 25 cm&sup2;.',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
            ],
        ];

        $questionIds = [];

        foreach ($questionsPayload as $payload) {
            $question = Question::updateOrCreate(
                [
                    'subject_id' => $subject->id,
                    'topic_id' => $topic->id,
                    'question_text' => $payload['question_text'],
                ],
                [
                    'subject_id' => $subject->id,
                    'topic_id' => $topic->id,
                    'class_level' => $payload['class_level'],
                    'difficulty' => $payload['difficulty'],
                    'type' => $payload['type'],
                    'question_text' => $payload['question_text'],
                    'question_image' => $payload['question_image'],
                    'option_a' => $payload['option_a'],
                    'option_b' => $payload['option_b'],
                    'option_c' => $payload['option_c'],
                    'option_d' => $payload['option_d'],
                    'option_e' => $payload['option_e'],
                    'correct_answer' => $payload['correct_answer'],
                    'explanation_text' => $payload['explanation_text'],
                    'explanation_video_url' => $payload['explanation_video_url'],
                    'source' => $payload['source'],
                    'status' => $payload['status'],
                    'created_by' => $admin->id,
                ]
            );

            $questionIds[] = $question->id;
        }

        // --- 4) TEST PACKAGE ---
        $package = TestPackage::updateOrCreate(
            [
                'name' => 'Latihan Operasi Hitung Campuran (Kelas 6)',
            ],
            [
                'name' => 'Latihan Operasi Hitung Campuran (Kelas 6)',
                'description' => 'Paket latihan sederhana untuk mencoba fitur latihan soal.',
                'class_level' => '6',
                'total_questions' => count($questionIds),
                'duration_minutes' => 20,
                'type' => 'free',
                'is_randomized' => false,
                'status' => 'published',
                'created_by' => $admin->id,
            ]
        );

        // --- 5) RELATION: test_package_questions ---
        // Insert sederhana: cek dulu berdasarkan (test_package_id, question_id, order_number).
        $relations = [
            ['order_number' => 1, 'question_id' => $questionIds[0] ?? null],
            ['order_number' => 2, 'question_id' => $questionIds[1] ?? null],
        ];

        foreach ($relations as $rel) {
            if (!$rel['question_id']) {
                continue;
            }

            $exists = TestPackageQuestion::where('test_package_id', $package->id)
                ->where('question_id', $rel['question_id'])
                ->first();

            if (!$exists) {
                TestPackageQuestion::create([
                    'test_package_id' => $package->id,
                    'question_id' => $rel['question_id'],
                    'order_number' => $rel['order_number'],
                ]);
            }
        }
    }
}

