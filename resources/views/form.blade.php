@extends('layouts.form')

@section('title', 'Kuesioner Tracer Study')

@section('content')
<div class="space-y-5 max-w-3xl mx-auto pb-16">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-5 flex items-start gap-4 shadow-sm animate-card">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-emerald-900">Berhasil!</h3>
                <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-200/80 p-5 shadow-sm animate-card">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-red-900">Periksa kembali isian Anda</h3>
                    <ul class="list-disc list-inside text-sm mt-1.5 space-y-0.5 text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- STATE: Atasan needs to select alumni first     --}}
    {{-- ============================================= --}}
    @if(isset($needsToSelectStudent) && $needsToSelectStudent)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 animate-card">
            <div class="h-1.5 w-full bg-gradient-to-r from-[#800000] via-[#a30000] to-[#800000] rounded-t-2xl"></div>
            <div class="p-8 sm:p-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-50 to-red-100 text-[#800000] flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Evaluasi Alumni</h3>
                        <p class="text-gray-500 text-sm mt-0.5">Cari dan pilih alumni yang akan Anda evaluasi.</p>
                    </div>
                </div>
                
                <form action="{{ route('form.create') }}" method="GET" class="space-y-6">
                    <div>
                        <label for="alumni-search" class="block text-sm font-semibold text-gray-700 mb-2">Nama Alumni</label>
                        <select name="student_id" id="alumni-search" class="w-full no-ts" required placeholder="Ketik nama alumni (min. 3 huruf)..."></select>
                    </div>
                    <button type="submit" class="px-7 py-3 bg-[#800000] text-white font-bold rounded-xl hover:bg-[#600000] focus:ring-4 focus:ring-[#800000]/20 transition-all shadow-md inline-flex items-center gap-2 text-sm">
                        Mulai Evaluasi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new TomSelect('#alumni-search', {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    loadThrottle: 300,
                    placeholder: 'Ketik nama alumni (min. 3 huruf)...',
                    load: function(query, callback) {
                        if (query.length < 3) return callback();
                        fetch('{{ route("form.search_alumni") }}?q=' + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(json => callback(json.results))
                            .catch(() => callback());
                    }
                });
            });
        </script>
        @endpush

    {{-- ============================================= --}}
    {{-- STATE: No active form available                --}}
    {{-- ============================================= --}}
    @elseif(!$activeForm)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center animate-card">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Kuesioner</h3>
            <p class="text-gray-500 max-w-sm mx-auto text-sm">Belum ada kuesioner aktif untuk peran Anda saat ini. Hubungi administrator jika ini tidak seharusnya terjadi.</p>
        </div>

    @else
        {{-- ============================================= --}}
        {{-- STATE: Already filled                         --}}
        {{-- ============================================= --}}
        @if($hasFilledActiveForm)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 sm:p-12 text-center relative animate-card">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-t-2xl"></div>
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-5 ring-4 ring-emerald-100">
                    <svg class="w-9 h-9 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-3">Terima Kasih!</h3>
                <p class="text-gray-600 max-w-md mx-auto">Anda sudah mengisi kuesioner <strong class="text-gray-800">"{{ $activeForm->title }}"</strong>. Jawaban Anda sangat berharga bagi peningkatan mutu layanan kami.</p>
                
                <div class="mt-8">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl border border-gray-200 transition-colors text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Lengkapi Profil Anda
                    </a>
                </div>
            </div>

        {{-- ============================================= --}}
        {{-- STATE: Active form — main questionnaire       --}}
        {{-- ============================================= --}}
        @else
            <form action="{{ route('form.store') }}" method="POST" id="questionnaireForm"
                  x-data="questionnaire()" @submit.prevent="submitForm">
                @csrf
                <input type="hidden" name="form_id" value="{{ $activeForm->id }}">
                @if($user->role === 'atasan')
                    <input type="hidden" name="evaluated_student_id" value="{{ $evaluatedStudentId }}">
                @endif

                {{-- ====== HEADER CARD ====== --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 mb-5 animate-card relative">
                    <div class="h-1.5 w-full bg-gradient-to-r from-[#800000] via-[#a30000] to-[#800000] rounded-t-2xl"></div>
                    <div class="p-7 sm:p-9">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">{{ $activeForm->title }}</h1>
                        <p class="text-gray-500 text-sm mt-3 leading-relaxed max-w-2xl">
                            Isi kuesioner ini dengan sebenar-benarnya. Data Anda sangat berharga bagi peningkatan mutu layanan dan kurikulum institusi kami.
                        </p>
                        <div class="mt-5 pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-xs text-red-600 font-semibold flex items-center gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Tanda <span class="text-red-500 text-sm font-bold">*</span> menunjukkan pertanyaan wajib
                            </p>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span x-text="answeredCount + '/' + totalQuestions"></span>
                                <div class="progress-track w-24 sm:w-32">
                                    <div class="progress-fill" :style="'width:' + progressPercent + '%'"></div>
                                </div>
                                <span x-text="progressPercent + '%'" class="font-semibold text-gray-600 tabular-nums"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== QUESTION CARDS ====== --}}
                @foreach($activeForm->questions as $index => $question)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md border mt-3 border-gray-100 transition-shadow duration-300 animate-card"
                     style="animation-delay: {{ ($index + 1) * 60 }}ms">
                    
                    {{-- Question header --}}
                    <div class="px-7 sm:px-9 pt-7 sm:pt-8 pb-4 flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-[#800000] font-bold text-sm shrink-0 border border-red-100">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <label class="block text-base font-bold text-gray-900 leading-snug">
                                {{ $question->question_text }}
                                @if($question->is_required)
                                    <span class="text-red-500 ml-0.5">*</span>
                                @endif
                            </label>
                        </div>
                    </div>

                    {{-- Question body --}}
                    <div class="px-7 sm:px-9 pb-7 sm:pb-8">
                        @switch($question->question_type)

                            @case('text')
                                <input type="text" name="answers[{{ $question->id }}]"
                                    value="{{ old('answers.' . $question->id) }}"
                                    class="w-full sm:w-3/4 py-3 px-4"
                                    placeholder="Ketik jawaban Anda..."
                                    @input="trackAnswer('{{ $question->id }}', $event.target.value)"
                                    {{ $question->is_required ? 'required' : '' }}>
                                @break

                            @case('number')
                                <input type="number" name="answers[{{ $question->id }}]"
                                    value="{{ old('answers.' . $question->id) }}"
                                    class="w-full sm:w-1/3 py-3 px-4"
                                    placeholder="0"
                                    @input="trackAnswer('{{ $question->id }}', $event.target.value)"
                                    {{ $question->is_required ? 'required' : '' }}>
                                @break

                            @case('textarea')
                                <textarea name="answers[{{ $question->id }}]" rows="3"
                                    class="w-full py-3 px-4 resize-y"
                                    placeholder="Tulis jawaban Anda di sini..."
                                    @input="trackAnswer('{{ $question->id }}', $event.target.value)"
                                    {{ $question->is_required ? 'required' : '' }}>{{ old('answers.' . $question->id) }}</textarea>
                                @break

                            @case('radio')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5" x-data="{ selected: '{{ old('answers.' . $question->id, '') }}' }">
                                    @foreach($question->options as $option)
                                    <div class="option-card" :class="{ 'selected': selected === '{{ addslashes($option->option_text) }}' }"
                                         @click="selected = '{{ addslashes($option->option_text) }}'; trackAnswer('{{ $question->id }}', selected)">
                                        <input type="radio" name="answers[{{ $question->id }}]"
                                               value="{{ $option->option_text }}" class="sr-only"
                                               x-ref="radio_{{ $question->id }}_{{ $loop->index }}"
                                               :checked="selected === '{{ addslashes($option->option_text) }}'"
                                               {{ $question->is_required ? 'required' : '' }}>
                                        <div class="option-radio"></div>
                                        <span class="option-text">{{ $option->option_text }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @break

                            @case('select')
                                <select name="answers[{{ $question->id }}]"
                                    class="no-ts w-full sm:w-2/3 rounded-xl border border-gray-300 px-4 py-3 focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/10 text-gray-800 bg-white shadow-sm transition-all text-sm"
                                    @change="trackAnswer('{{ $question->id }}', $event.target.value)"
                                    {{ $question->is_required ? 'required' : '' }}>
                                    <option value="">— Pilih salah satu —</option>
                                    @foreach($question->options as $option)
                                        <option value="{{ $option->option_text }}" {{ old('answers.' . $question->id) == $option->option_text ? 'selected' : '' }}>
                                            {{ $option->option_text }}
                                        </option>
                                    @endforeach
                                </select>
                                @break

                            @case('checkbox')
                                @php $oldCheckbox = old('answers.' . $question->id, []); @endphp
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5"
                                     x-data="{ checked: {{ json_encode(is_array($oldCheckbox) ? $oldCheckbox : []) }} }">
                                    @foreach($question->options as $option)
                                    <div class="option-card"
                                         :class="{ 'selected': checked.includes('{{ addslashes($option->option_text) }}') }"
                                         @click="toggleCheck(checked, '{{ addslashes($option->option_text) }}'); trackAnswer('{{ $question->id }}', checked.length > 0 ? 'filled' : '')">
                                        <input type="checkbox" name="answers[{{ $question->id }}][]"
                                               value="{{ $option->option_text }}" class="sr-only"
                                               :checked="checked.includes('{{ addslashes($option->option_text) }}')">
                                        <div class="option-checkbox">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span class="option-text">{{ $option->option_text }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @break

                        @endswitch
                    </div>
                </div>
                @endforeach

                {{-- ====== SUBMIT AREA ====== --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-6 pb-8">
                    <button type="button"
                        class="text-gray-400 font-medium text-sm hover:text-[#800000] transition-colors flex items-center gap-1.5 order-2 sm:order-1"
                        onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        Kembali ke atas
                    </button>
                    <button type="submit"
                        class="order-1 sm:order-2 w-full sm:w-auto px-8 py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg shadow-red-900/15 hover:bg-[#660000] hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#800000]/20 transition-all flex items-center justify-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        @endif
    @endif

</div>
@endsection

@push('scripts')
<script>
function questionnaire() {
    return {
        answers: {},
        totalQuestions: {{ isset($activeForm) && !($hasFilledActiveForm ?? false) ? $activeForm->questions->count() : 0 }},

        get answeredCount() {
            return Object.values(this.answers).filter(v => v && v !== '').length;
        },

        get progressPercent() {
            if (this.totalQuestions === 0) return 0;
            return Math.round((this.answeredCount / this.totalQuestions) * 100);
        },

        trackAnswer(questionId, value) {
            this.answers[questionId] = value;
        },

        toggleCheck(arr, value) {
            const idx = arr.indexOf(value);
            if (idx === -1) arr.push(value);
            else arr.splice(idx, 1);
        },

        submitForm() {
            // Sync all checkbox states before submit
            document.querySelectorAll('.option-card input[type="checkbox"]').forEach(cb => {
                const label = cb.closest('label');
                cb.checked = label.classList.contains('selected');
            });
            // Sync radio states
            document.querySelectorAll('.option-card input[type="radio"]').forEach(rb => {
                const label = rb.closest('label');
                rb.checked = label.classList.contains('selected');
            });

            document.getElementById('questionnaireForm').submit();
        }
    }
}
</script>
@endpush
