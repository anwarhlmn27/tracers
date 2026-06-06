<?php

namespace App\Http\Controllers;

use App\Models\TracerResponse;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of all questionnaire responses.
     */
    public function index()
    {
        $responses = \App\Models\FormResponse::with(['user.student.prodi', 'form', 'answers.question'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('questionnaires', compact('responses'));
    }

    /**
     * Export questionnaire responses to CSV (Excel-compatible).
     */
    public function export(): StreamedResponse
    {
        $responses = \App\Models\FormResponse::with(['user.student.prodi', 'form', 'answers.question'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'tracer_responses_' . date('Y-m-d_His') . '.xlsx';

        $data = [
            ['No', 'Timestamp', 'Role Target', 'Nama Responden', 'Judul Form', 'Pertanyaan', 'Jawaban']
        ];

        $rowNo = 1;

        // Data rows
        foreach ($responses as $response) {
            $roleTarget = $response->form->target_role ?? '-';
            $namaResponden = $response->user->name ?? '-';
            if ($roleTarget === 'alumni' && $response->user->student) {
                $namaResponden = $response->user->student->nama_student;
            }
            $judulForm = $response->form->title ?? '-';
            $timestamp = $response->created_at->format('Y-m-d H:i:s');

            foreach ($response->answers as $answer) {
                $pertanyaan = $answer->question->question_text ?? 'Pertanyaan Dihapus';
                $jawabanText = $answer->answer_text ?? '-';

                $data[] = [
                    $rowNo++,
                    $timestamp,
                    $roleTarget,
                    $namaResponden,
                    $judulForm,
                    $pertanyaan,
                    $jawabanText,
                ];
            }
        }

        return response()->streamDownload(function () use ($data) {
            $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);
            $xlsx->saveAs('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
