<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TracerResponse;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the report & analytics page.
     */
    public function index()
    {
        // 1. Overview stats
        $totalStudents = Student::count();
        $totalResponses = \App\Models\FormResponse::count();
        
        $alumniResponseCount = \App\Models\FormResponse::whereHas('form', function($q) {
            $q->where('target_role', 'alumni');
        })->distinct('user_id')->count('user_id');
        
        $responseRate = $totalStudents > 0 ? round(($alumniResponseCount / $totalStudents) * 100, 1) : 0;
        
        $atasanResponseCount = \App\Models\FormResponse::whereHas('form', function($q) {
            $q->where('target_role', 'atasan');
        })->distinct('user_id')->count('user_id');

        // 2. Perbandingan yang sudah mengisi vs belum per Prodi (Bar chart)
        $prodis = Prodi::withCount(['students'])->get();
        $prodiLabels = [];
        $sudahMengisi = [];
        $belumMengisi = [];

        $alumniIdsWithResponses = \App\Models\FormResponse::whereHas('form', function($q) {
            $q->where('target_role', 'alumni');
        })->pluck('user_id')->toArray();

        foreach ($prodis as $prodi) {
            $prodiLabels[] = $prodi->nama_prodi;
            $filledCount = Student::where('prodi_id', $prodi->id)
                ->whereIn('user_id', $alumniIdsWithResponses)
                ->count();
            $sudahMengisi[] = $filledCount;
            $belumMengisi[] = $prodi->students_count - $filledCount;
        }

        // 3. Trend response per bulan (Line chart) — last 12 months
        $monthlyData = \App\Models\FormResponse::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthLabels = [];
        $monthCounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $monthLabels[] = now()->subMonths($i)->format('M Y');
            $found = $monthlyData->firstWhere('month', $month);
            $monthCounts[] = $found ? $found->total : 0;
        }

        // 4. Dynamic Charts for Multiple Choice Questions (from Active Forms)
        $dynamicQuestions = \App\Models\FormQuestion::with(['answers', 'form'])
            ->whereHas('form', function($q) {
                $q->where('is_active', true);
            })
            ->whereIn('question_type', ['radio', 'select', 'checkbox'])
            ->get();
            
        $dynamicCharts = [];
        foreach ($dynamicQuestions as $q) {
            $counts = [];
            foreach ($q->answers as $ans) {
                if (empty($ans->answer_text)) continue;
                
                if ($q->question_type === 'checkbox') {
                    // Checkbox answers are comma separated
                    $parts = explode(', ', $ans->answer_text);
                    foreach ($parts as $p) {
                        $p = trim($p);
                        if (!empty($p)) {
                            $counts[$p] = ($counts[$p] ?? 0) + 1;
                        }
                    }
                } else {
                    $val = trim($ans->answer_text);
                    $counts[$val] = ($counts[$val] ?? 0) + 1;
                }
            }
            
            if (count($counts) > 0) {
                $dynamicCharts[] = [
                    'id' => 'chart_' . $q->id,
                    'question_text' => $q->question_text,
                    'form_title' => $q->form->title,
                    'labels' => array_keys($counts),
                    'data' => array_values($counts),
                ];
            }
        }

        // 5. Student per angkatan
        $angkatanData = Student::select('angkatan', DB::raw('COUNT(*) as total'))
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->get();

        return view('reports', compact(
            'totalStudents',
            'totalResponses',
            'alumniResponseCount',
            'atasanResponseCount',
            'responseRate',
            'prodiLabels',
            'sudahMengisi',
            'belumMengisi',
            'monthLabels',
            'monthCounts',
            'dynamicCharts',
            'angkatanData',
        ));
    }
}
