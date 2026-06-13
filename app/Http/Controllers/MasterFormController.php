<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireForm;
use App\Models\FormQuestion;
use App\Models\FormQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MasterFormController extends Controller
{
    /**
     * Display a listing of all questionnaire forms.
     */
    public function index()
    {
        $forms = QuestionnaireForm::withCount(['questions', 'responses'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('master-form.index', compact('forms'));
    }

    /**
     * Show the form for creating a new questionnaire form.
     */
    public function create()
    {
        return view('master-form.create');
    }

    /**
     * Store a newly created questionnaire form in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'target_role' => ['required', Rule::in(['alumni', 'atasan'])],
            'angkatan' => ['nullable', 'string', 'max:50'],
            'form_group' => ['nullable', 'string', 'max:100'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.text' => ['required', 'string'],
            'questions.*.type' => ['required', Rule::in(['text', 'number', 'textarea', 'radio', 'select', 'checkbox'])],
            'questions.*.required' => ['sometimes', 'boolean'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.options.*' => ['nullable', 'string'],
        ]);

        $form = QuestionnaireForm::create([
            'id' => Str::uuid(),
            'title' => $validated['title'],
            'target_role' => $validated['target_role'],
            'angkatan' => $validated['angkatan'] ?? null,
            'form_group' => $validated['form_group'] ?? null,
            'is_active' => true,
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            $question = FormQuestion::create([
                'id' => Str::uuid(),
                'form_id' => $form->id,
                'question_text' => $questionData['text'],
                'question_type' => $questionData['type'],
                'is_required' => $questionData['required'] ?? true,
                'sort_order' => $index,
            ]);

            // Create options for radio/select/checkbox
            if (in_array($questionData['type'], ['radio', 'select', 'checkbox']) && !empty($questionData['options'])) {
                foreach ($questionData['options'] as $optIndex => $optionText) {
                    if (!empty(trim($optionText))) {
                        FormQuestionOption::create([
                            'id' => Str::uuid(),
                            'question_id' => $question->id,
                            'option_text' => trim($optionText),
                            'sort_order' => $optIndex,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('master-form.index')->with('success', 'Form kuesioner berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified questionnaire form.
     */
    public function edit(string $id)
    {
        $form = QuestionnaireForm::with(['questions.options'])->findOrFail($id);

        return view('master-form.edit', compact('form'));
    }

    /**
     * Update the specified questionnaire form in storage.
     */
    public function update(Request $request, string $id)
    {
        $form = QuestionnaireForm::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'target_role' => ['required', Rule::in(['alumni', 'atasan'])],
            'angkatan' => ['nullable', 'string', 'max:50'],
            'form_group' => ['nullable', 'string', 'max:100'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.text' => ['required', 'string'],
            'questions.*.type' => ['required', Rule::in(['text', 'number', 'textarea', 'radio', 'select', 'checkbox'])],
            'questions.*.required' => ['sometimes', 'boolean'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.options.*' => ['nullable', 'string'],
        ]);

        $form->update([
            'title' => $validated['title'],
            'target_role' => $validated['target_role'],
            'angkatan' => $validated['angkatan'] ?? null,
            'form_group' => $validated['form_group'] ?? null,
        ]);

        // Delete old questions (cascade deletes options too)
        $form->questions()->delete();

        // Re-create questions
        foreach ($validated['questions'] as $index => $questionData) {
            $question = FormQuestion::create([
                'id' => Str::uuid(),
                'form_id' => $form->id,
                'question_text' => $questionData['text'],
                'question_type' => $questionData['type'],
                'is_required' => $questionData['required'] ?? true,
                'sort_order' => $index,
            ]);

            if (in_array($questionData['type'], ['radio', 'select', 'checkbox']) && !empty($questionData['options'])) {
                foreach ($questionData['options'] as $optIndex => $optionText) {
                    if (!empty(trim($optionText))) {
                        FormQuestionOption::create([
                            'id' => Str::uuid(),
                            'question_id' => $question->id,
                            'option_text' => trim($optionText),
                            'sort_order' => $optIndex,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('master-form.index')->with('success', 'Form kuesioner berhasil diperbarui!');
    }

    /**
     * Remove the specified questionnaire form from storage.
     */
    public function destroy(string $id)
    {
        $form = QuestionnaireForm::findOrFail($id);
        $form->delete();

        return back()->with('success', 'Form kuesioner berhasil dihapus!');
    }

    /**
     * Toggle active status of a form.
     */
    public function toggleActive(string $id)
    {
        $form = QuestionnaireForm::findOrFail($id);
        $form->update(['is_active' => !$form->is_active]);

        $status = $form->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Form \"{$form->title}\" berhasil {$status}!");
    }
}
