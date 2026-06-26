<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
            ->whereIn('question_type', ['radio', 'select', 'checkbox', 'linear_scale', 'rating'])
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

        // Helper closure: query answer counts for a question by keyword
        $answerCounts = function (string $keyword) {
            return \App\Models\FormResponseAnswer::whereHas('question', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(question_text) LIKE ?', ['%' . strtolower($keyword) . '%']);
            })
            ->whereNotNull('answer_text')
            ->where('answer_text', '!=', '')
            ->selectRaw('answer_text, COUNT(*) as total')
            ->groupBy('answer_text')
            ->orderByDesc('total')
            ->pluck('total', 'answer_text')
            ->toArray();
        };

        // 6. Waktu Tunggu — ambil dari answer_text, kelompokkan ke bucket
        $rawWaktuTunggu = \App\Models\FormResponseAnswer::whereHas('question', function ($q) {
                $q->whereRaw('LOWER(question_text) LIKE ?', ['%waktu tunggu%']);
            })
            ->whereNotNull('answer_text')
            ->where('answer_text', '!=', '')
            ->pluck('answer_text');

        $waktuTungguBuckets = [
            '0 bulan' => 0, '1-3 bulan' => 0, '4-6 bulan' => 0,
            '7-12 bulan' => 0, '> 12 bulan' => 0,
        ];
        foreach ($rawWaktuTunggu as $val) {
            $num = (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
            if ($num <= 0)       $waktuTungguBuckets['0 bulan']++;
            elseif ($num <= 3)   $waktuTungguBuckets['1-3 bulan']++;
            elseif ($num <= 6)   $waktuTungguBuckets['4-6 bulan']++;
            elseif ($num <= 12)  $waktuTungguBuckets['7-12 bulan']++;
            else                 $waktuTungguBuckets['> 12 bulan']++;
        }
        $waktuTungguLabels = array_keys($waktuTungguBuckets);
        $waktuTungguData   = array_values($waktuTungguBuckets);

        // 7. Skala Tempat Kerja
        $skalaTempat = $answerCounts('skala tempat kerja');

        // 8. Distribusi Pendapatan
        $pendapatanData = $answerCounts('pendapatan');

        // 9. Kesesuaian Pekerjaan dengan Prodi
        $kesesuaianData = $answerCounts('sesuai dengan program studi');

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
            'waktuTungguLabels',
            'waktuTungguData',
            'skalaTempat',
            'pendapatanData',
            'kesesuaianData',
        ));
    }
}
