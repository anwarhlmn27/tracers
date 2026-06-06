<?php

namespace App\Http\Controllers;

use App\Models\TracerResponse;
use App\Models\QuestionnaireForm;
use App\Models\FormResponse;
use App\Models\FormResponseAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TracerResponseController extends Controller
{
    /**
     * Show the form for creating a new tracer response.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        // Get active form for this user's role
        $activeForm = QuestionnaireForm::with(['questions.options'])
            ->where('target_role', $role)
            ->where('is_active', true)
            ->latest()
            ->first();

        // Get previous responses for this user
        $previousResponses = FormResponse::with(['form', 'answers.question'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Legacy: also get old static responses if user is alumni with student record
        $student = $user->student;
        $legacyResponses = collect();
        if ($student) {
            $legacyResponses = TracerResponse::where('student_id', $student->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Check if user has already filled the active form
        $hasFilledActiveForm = false;
        if ($activeForm) {
            $hasFilledActiveForm = FormResponse::where('form_id', $activeForm->id)
                ->where('user_id', $user->id)
                ->exists();
        }

        return view('form', compact('activeForm', 'hasFilledActiveForm', 'previousResponses', 'legacyResponses', 'student', 'user'));
    }

    /**
     * Store a newly created response in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'form_id' => ['required', 'exists:questionnaire_forms,id'],
            'answers' => ['required', 'array'],
        ]);

        $form = QuestionnaireForm::with('questions')->findOrFail($validated['form_id']);

        // Check if already submitted
        $alreadySubmitted = FormResponse::where('form_id', $form->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadySubmitted) {
            return back()->withErrors(['form_id' => 'Anda sudah mengisi kuesioner ini sebelumnya.']);
        }

        // Validate required questions have answers
        foreach ($form->questions as $question) {
            if ($question->is_required) {
                $answer = $validated['answers'][$question->id] ?? null;
                if (empty($answer) && $answer !== '0') {
                    return back()->withErrors(['answers.' . $question->id => 'Pertanyaan "' . $question->question_text . '" wajib diisi.'])->withInput();
                }
            }
        }

        // Create form response
        $formResponse = FormResponse::create([
            'id' => Str::uuid(),
            'form_id' => $form->id,
            'user_id' => $user->id,
        ]);

        // Save answers
        foreach ($form->questions as $question) {
            $answerValue = $validated['answers'][$question->id] ?? null;

            // For checkbox, join multiple values
            if ($question->question_type === 'checkbox' && is_array($answerValue)) {
                $answerValue = implode(', ', $answerValue);
            }

            FormResponseAnswer::create([
                'id' => Str::uuid(),
                'response_id' => $formResponse->id,
                'question_id' => $question->id,
                'answer_text' => $answerValue,
            ]);
        }

        return back()->with('success', 'Kuesioner berhasil disimpan! Terima kasih atas partisipasi Anda.');
    }
}
