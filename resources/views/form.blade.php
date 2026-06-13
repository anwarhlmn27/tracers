@extends('layouts.form')

@section('title', 'Kuesioner Tracer Study')

@section('content')
<style>
    /* Styling khusus untuk input form agar terlihat seperti Google Forms */
    .g-input {
        border: none;
        border-bottom: 1px solid #d1d5db;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        background-color: transparent;
        box-shadow: none !important;
        transition: border-color 0.3s ease;
    }
    .g-input:focus {
        border-bottom: 2px solid #800000;
        outline: none;
    }
    
    .card-hover {
        transition: all 0.3s ease;
        border-left: 6px solid transparent;
    }
    
    .card-hover:focus-within {
        border-left: 6px solid #800000;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>

<div class="space-y-4">

    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-5 text-emerald-800 flex items-start gap-4 shadow-sm">
            <svg class="w-6 h-6 mt-0.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h3 class="font-bold">Berhasil!</h3>
                <p class="text-sm mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 p-5 text-red-800 shadow-sm">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 mt-0.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold">Periksa kembali isian Anda</h3>
                    <ul class="list-disc list-inside text-sm mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(isset($needsToSelectStudent) && $needsToSelectStudent)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 border-t-8 border-t-[#800000]">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Evaluasi Alumni</h3>
            <p class="text-gray-600 mb-6">Silakan cari dan pilih alumni yang ingin Anda evaluasi.</p>
            
            <form action="{{ route('form.create') }}" method="GET" class="space-y-4">
                <div>
                    <select name="student_id" id="alumni-search" class="w-full no-ts" required placeholder="Ketik nama alumni (min. 3 huruf)..."></select>
                </div>
                <div class="pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#800000] text-white font-medium rounded-xl hover:bg-[#600000] transition-colors shadow">
                        Mulai Evaluasi
                    </button>
                </div>
            </form>
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
                            .then(json => {
                                callback(json.results);
                            }).catch(() => {
                                callback();
                            });
                    }
                });
            });
        </script>
        @endpush
    @elseif(!$activeForm)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10 text-center border-t-8 border-t-gray-400">
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Tidak Ada Kuesioner</h3>
            <p class="text-gray-500">Saat ini belum ada form kuesioner aktif untuk peran Anda. Silakan hubungi administrator.</p>
        </div>
    @else
        @if($hasFilledActiveForm)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10 text-center border-t-8 border-t-[#800000]">
                <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Terima Kasih!</h3>
                <p class="text-gray-600">Anda sudah mengisi kuesioner "{{ $activeForm->title }}". Jawaban Anda telah kami terima.</p>
                <div class="mt-8">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 text-[#800000] hover:text-[#a00000] font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali atau Lengkapi Profil Anda
                    </a>
                </div>
            </div>
        @else
            <form action="{{ route('form.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="form_id" value="{{ $activeForm->id }}">
                @if($user->role === 'atasan')
                    <input type="hidden" name="evaluated_student_id" value="{{ $evaluatedStudentId }}">
                @endif

                {{-- Header Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden border-t-8 border-t-[#800000]">
                    <div class="p-6 sm:p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $activeForm->title }}</h1>
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                            Isi kuesioner ini dengan sebenar-benarnya. Data Anda sangat berharga bagi peningkatan mutu layanan dan kurikulum institusi kami.
                        </p>
                        <hr class="my-4 border-gray-100">
                        <p class="text-sm text-red-600 font-medium">
                            * Menunjukkan pertanyaan yang wajib diisi
                        </p>
                    </div>
                </div>

                {{-- Questions Cards --}}
                @foreach($activeForm->questions as $index => $question)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 card-hover" tabindex="0">
                    <label class="block text-base font-medium text-gray-900 mb-4">
                        {{ $question->question_text }}
                        @if($question->is_required)
                            <span class="text-red-500 ml-1 text-lg leading-none">*</span>
                        @endif
                    </label>

                    @switch($question->question_type)
                        @case('text')
                            <input type="text" name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}"
                                class="w-full sm:w-2/3 g-input text-gray-800 focus:ring-0"
                                placeholder="Jawaban Anda"
                                {{ $question->is_required ? 'required' : '' }}>
                            @break

                        @case('number')
                            <input type="number" name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}"
                                class="w-full sm:w-1/3 g-input text-gray-800 focus:ring-0"
                                placeholder="Jawaban Anda"
                                {{ $question->is_required ? 'required' : '' }}>
                            @break

                        @case('textarea')
                            <textarea name="answers[{{ $question->id }}]" rows="2"
                                class="w-full g-input resize-y text-gray-800 focus:ring-0"
                                placeholder="Jawaban Anda"
                                {{ $question->is_required ? 'required' : '' }}>{{ old('answers.' . $question->id) }}</textarea>
                            @break

                        @case('radio')
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                <label class="flex items-center gap-3 cursor-pointer group w-fit">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->option_text }}"
                                        {{ old('answers.' . $question->id) == $option->option_text ? 'checked' : '' }}
                                        {{ $question->is_required ? 'required' : '' }}>
                                    <span class="text-gray-700 group-hover:text-gray-900 transition-colors">{{ $option->option_text }}</span>
                                </label>
                                @endforeach
                            </div>
                            @break

                        @case('select')
                            <select name="answers[{{ $question->id }}]"
                                class="w-full sm:w-1/2 rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm text-gray-700 bg-white shadow-sm transition-colors"
                                {{ $question->is_required ? 'required' : '' }}>
                                <option value="">Pilih</option>
                                @foreach($question->options as $option)
                                    <option value="{{ $option->option_text }}" {{ old('answers.' . $question->id) == $option->option_text ? 'selected' : '' }}>
                                        {{ $option->option_text }}
                                    </option>
                                @endforeach
                            </select>
                            @break

                        @case('checkbox')
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                <label class="flex items-center gap-3 cursor-pointer group w-fit">
                                    <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->option_text }}"
                                        {{ is_array(old('answers.' . $question->id)) && in_array($option->option_text, old('answers.' . $question->id)) ? 'checked' : '' }}>
                                    <span class="text-gray-700 group-hover:text-gray-900 transition-colors">{{ $option->option_text }}</span>
                                </label>
                                @endforeach
                            </div>
                            @break
                    @endswitch
                </div>
                @endforeach

                <div class="pt-6 pb-12 flex justify-between items-center">
                    <button type="button" class="text-[#800000] font-medium text-sm hover:underline" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                        Kembali ke Atas
                    </button>
                    <button type="submit" class="px-8 py-2.5 bg-[#800000] text-white font-bold rounded-lg shadow hover:bg-[#600000] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#800000] focus:ring-offset-2 transition-all">
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        @endif
    @endif

</div>
@endsection
