<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use App\Models\Result;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function show(UserSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Authorization check
        if ($session->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        // Ensure session is completed
        if ($session->status !== 'completed') {
            return redirect()->route('latihan.mulai', $session->id)->with('info', 'Sesi belum selesai.');
        }

        $package = $session->testPackage;
        $questions = $package->questions()->orderBy('order_number')->get();
        $answers = $session->answers->keyBy('question_id');

        // Jika result belum ada, kita buat (hitung skor)
        $result = $session->result;
        if (!$result) {
            $totalQuestions = $package->total_questions;
            $correctCount = 0;
            $wrongCount = 0;
            $emptyCount = 0;

            $scorePerSubject = [];
            $weaknessTopics = [];

            foreach ($questions as $q) {
                $subjectName = $q->subject->name;
                $topicName = $q->topic->name;
                
                if (!isset($scorePerSubject[$subjectName])) {
                    $scorePerSubject[$subjectName] = ['correct' => 0, 'total' => 0];
                }
                $scorePerSubject[$subjectName]['total']++;

                $ans = $answers->get($q->id);
                if (!$ans || $ans->selected_answer === null || $ans->selected_answer === '') {
                    $emptyCount++;
                    $weaknessTopics[$topicName] = ($weaknessTopics[$topicName] ?? 0) + 1;
                } elseif ($ans->is_correct) {
                    $correctCount++;
                    $scorePerSubject[$subjectName]['correct']++;
                } else {
                    $wrongCount++;
                    $weaknessTopics[$topicName] = ($weaknessTopics[$topicName] ?? 0) + 1;
                }
            }

            $totalScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

            // Format score per subject to percentage
            $formattedScorePerSubject = [];
            foreach ($scorePerSubject as $sub => $data) {
                $formattedScorePerSubject[$sub] = $data['total'] > 0 ? round(($data['correct'] / $data['total']) * 100, 2) : 0;
            }

            // Get top 3 weakness topics
            arsort($weaknessTopics);
            $topWeaknesses = array_slice(array_keys($weaknessTopics), 0, 3);

            $result = Result::create([
                'user_id' => $user->id,
                'session_id' => $session->id,
                'total_score' => $totalScore,
                'correct_count' => $correctCount,
                'wrong_count' => $wrongCount,
                'empty_count' => $emptyCount,
                'score_per_subject' => $formattedScorePerSubject,
                'weakness_topics' => $topWeaknesses,
            ]);
        }

        // --- AI Recommendation Generation ---
        $aiFeedback = null;
        if ($user->todayAiQuota() > 0 && !empty($result->weakness_topics)) {
            try {
                $context = [
                    'name' => $user->name,
                    'class_level' => $user->class_level_label,
                    'avg_score' => $result->total_score,
                    'weak_topics' => $result->weakness_topics,
                    'subscription' => $user->subscription_label,
                    'active_question' => 'Sedang melihat hasil latihan',
                ];

                $prompt = "Siswa baru saja menyelesaikan latihan paket '{$package->name}' dan mendapat skor {$result->total_score}. Topik yang masih lemah adalah: " . implode(', ', $result->weakness_topics) . ". Berikan 2-3 paragraf ulasan singkat yang ramah, menyemangati, dan berikan tips cara memperbaiki kelemahan tersebut. Jangan terlalu panjang.";

                $aiFeedback = $this->gemini->chat([], $prompt, $context);
                
                // Record usage if free
                if (!$user->isPremium()) {
                    \App\Models\AiDailyUsage::firstOrCreate(
                        ['user_id' => $user->id, 'date' => today()->toDateString()]
                    )->increment('count');
                }
            } catch (\Exception $e) {
                $aiFeedback = "Maaf, AI Tutor sedang tidak dapat dihubungi saat ini. Tetap semangat belajarnya ya!";
            }
        } elseif (empty($result->weakness_topics)) {
             $aiFeedback = "Luar biasa! Kamu berhasil menjawab semua soal dengan sangat baik. Pertahankan terus prestasimu!";
        } else {
             $aiFeedback = "Skor kamu sudah dicatat! Tingkatkan lagi ya di topik " . implode(', ', $result->weakness_topics) . ". (Upgrade ke Premium untuk mendapat analisis penuh dari AI Tutor!)";
        }

        return view('latihan.hasil', compact('session', 'package', 'questions', 'answers', 'result', 'aiFeedback'));
    }
}
