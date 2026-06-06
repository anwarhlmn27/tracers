@extends('layouts.app')

@section('title', 'Buat Form Baru - Tracer Study')
@section('header', 'Buat Form Kuesioner Baru')

@section('content')
<div class="max-w-4xl mx-auto" x-data="formBuilder()">
    <form action="{{ route('master-form.store') }}" method="POST" @submit="prepareSubmit">
        @csrf

        <!-- Form Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Form</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Form <span class="text-red-500">*</span></label>
                    <input type="text" name="title" x-model="formTitle" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm"
                        placeholder="Contoh: Kuesioner Tracer Study Alumni 2024">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Target Role <span class="text-red-500">*</span></label>
                    <select name="target_role" x-model="targetRole" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm">
                        <option value="alumni">Alumni (Student)</option>
                        <option value="atasan">Atasan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Questions Builder -->
        <div class="space-y-4 mb-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Daftar Pertanyaan</h3>
                <span class="text-sm text-gray-500" x-text="questions.length + ' pertanyaan'"></span>
            </div>

            <template x-for="(question, qIndex) in questions" :key="question.id">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                    <!-- Question Header -->
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-[#800000] text-white flex items-center justify-center text-xs font-bold" x-text="qIndex + 1"></span>
                            <span class="text-sm font-semibold text-gray-700">Pertanyaan</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="moveUp(qIndex)" x-show="qIndex > 0" class="w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-200 flex items-center justify-center transition-colors" title="Pindah ke atas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <button type="button" @click="moveDown(qIndex)" x-show="qIndex < questions.length - 1" class="w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-200 flex items-center justify-center transition-colors" title="Pindah ke bawah">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <button type="button" @click="removeQuestion(qIndex)" class="w-7 h-7 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors" title="Hapus pertanyaan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Question Body -->
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Teks Pertanyaan <span class="text-red-500">*</span></label>
                            <input type="text" x-model="question.text" required
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm"
                                placeholder="Tulis pertanyaan di sini...">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Pertanyaan</label>
                                <select x-model="question.type" @change="onTypeChange(qIndex)"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm">
                                    <option value="text">Text (Input Singkat)</option>
                                    <option value="number">Number (Angka)</option>
                                    <option value="textarea">Textarea (Teks Panjang)</option>
                                    <option value="radio">Radio (Pilih Satu)</option>
                                    <option value="select">Select (Dropdown)</option>
                                    <option value="checkbox">Checkbox (Pilih Banyak)</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="question.required" class="rounded border-gray-300 text-[#800000] focus:ring-[#800000]">
                                    <span class="text-sm text-gray-700">Wajib diisi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Options (for radio/select/checkbox) -->
                        <div x-show="['radio', 'select', 'checkbox'].includes(question.type)" x-transition class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700">Opsi Jawaban</label>
                            <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-[10px] font-bold shrink-0" x-text="String.fromCharCode(65 + oIndex)"></span>
                                    <input type="text" x-model="question.options[oIndex]"
                                        class="flex-1 rounded-xl border border-gray-200 px-3 py-2 focus:ring-[#800000] focus:border-[#800000] text-sm"
                                        :placeholder="'Opsi ' + (oIndex + 1)">
                                    <button type="button" @click="removeOption(qIndex, oIndex)" x-show="question.options.length > 1"
                                        class="w-7 h-7 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addOption(qIndex)"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-[#800000] hover:text-[#a00000] transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Opsi
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty state -->
            <div x-show="questions.length === 0" class="bg-white rounded-2xl border border-dashed border-gray-200 p-10 text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-gray-500 text-sm">Belum ada pertanyaan. Klik tombol di bawah untuk menambahkan.</p>
            </div>
        </div>

        <!-- Add Question Button -->
        <button type="button" @click="addQuestion()"
            class="w-full mb-6 py-3 rounded-2xl border-2 border-dashed border-gray-300 text-gray-500 hover:border-[#800000] hover:text-[#800000] transition-all duration-200 flex items-center justify-center gap-2 text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pertanyaan
        </button>

        <!-- Hidden inputs populated on submit -->
        <template x-for="(question, qIndex) in questions" :key="'hidden-' + question.id">
            <div>
                <input type="hidden" :name="'questions[' + qIndex + '][text]'" :value="question.text">
                <input type="hidden" :name="'questions[' + qIndex + '][type]'" :value="question.type">
                <input type="hidden" :name="'questions[' + qIndex + '][required]'" :value="question.required ? 1 : 0">
                <template x-if="['radio', 'select', 'checkbox'].includes(question.type)">
                    <template x-for="(opt, oIndex) in question.options" :key="'opt-' + oIndex">
                        <input type="hidden" :name="'questions[' + qIndex + '][options][' + oIndex + ']'" :value="opt">
                    </template>
                </template>
            </div>
        </template>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('master-form.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <button type="submit" :disabled="questions.length === 0"
                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#800000] to-[#a00000] px-6 py-3 text-white font-semibold shadow-lg shadow-red-900/20 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Form
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function formBuilder() {
    return {
        formTitle: '',
        targetRole: 'alumni',
        questions: [],
        nextId: 1,

        addQuestion() {
            this.questions.push({
                id: this.nextId++,
                text: '',
                type: 'text',
                required: true,
                options: ['', ''],
            });
        },

        removeQuestion(index) {
            this.questions.splice(index, 1);
        },

        moveUp(index) {
            if (index > 0) {
                [this.questions[index - 1], this.questions[index]] = [this.questions[index], this.questions[index - 1]];
            }
        },

        moveDown(index) {
            if (index < this.questions.length - 1) {
                [this.questions[index], this.questions[index + 1]] = [this.questions[index + 1], this.questions[index]];
            }
        },

        addOption(qIndex) {
            this.questions[qIndex].options.push('');
        },

        removeOption(qIndex, oIndex) {
            this.questions[qIndex].options.splice(oIndex, 1);
        },

        onTypeChange(qIndex) {
            const q = this.questions[qIndex];
            if (['radio', 'select', 'checkbox'].includes(q.type) && (!q.options || q.options.length === 0)) {
                q.options = ['', ''];
            }
        },

        prepareSubmit(e) {
            // Validate at least one question
            if (this.questions.length === 0) {
                e.preventDefault();
                alert('Tambahkan minimal 1 pertanyaan.');
                return;
            }
            // Validate each question has text
            for (let i = 0; i < this.questions.length; i++) {
                if (!this.questions[i].text.trim()) {
                    e.preventDefault();
                    alert('Pertanyaan #' + (i + 1) + ' belum diisi.');
                    return;
                }
                // Validate options for radio/select/checkbox
                if (['radio', 'select', 'checkbox'].includes(this.questions[i].type)) {
                    const filledOptions = this.questions[i].options.filter(o => o.trim() !== '');
                    if (filledOptions.length < 2) {
                        e.preventDefault();
                        alert('Pertanyaan #' + (i + 1) + ' membutuhkan minimal 2 opsi.');
                        return;
                    }
                }
            }
        },
    };
}
</script>
@endpush
