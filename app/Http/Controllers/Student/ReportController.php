<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display the user's progress report and statistics.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Mengambil seluruh riwayat hasil latihan dengan eager loading
        $resultsHistory = $user->results()
                               ->with(['session', 'session.testPackage'])
                               ->latest()
                               ->get();

        // Metrik utama
        $averageScore = $user->getAverageScore();
        $totalExercises = $user->sessions()->where('status', 'completed')->count();
        $totalAnswered = $user->getTotalAnswered();
        $weakTopics = $user->getWeakTopics();

        // Menghitung rata-rata nilai per mata pelajaran
        $subjectStats = [];
        foreach ($resultsHistory as $result) {
            $scoresPerSubject = $result->score_per_subject ?? [];
            foreach ($scoresPerSubject as $subject => $score) {
                if (!isset($subjectStats[$subject])) {
                    $subjectStats[$subject] = ['total_score' => 0, 'count' => 0];
                }
                $subjectStats[$subject]['total_score'] += $score;
                $subjectStats[$subject]['count'] += 1;
            }
        }

        // Hitung rata-rata final per mapel
        $subjectAverages = [];
        foreach ($subjectStats as $subject => $data) {
            $subjectAverages[$subject] = round($data['total_score'] / $data['count'], 1);
        }

        return view('laporan.index', compact(
            'resultsHistory', 
            'averageScore', 
            'totalExercises', 
            'totalAnswered', 
            'weakTopics',
            'subjectAverages'
        ));
    }
}
