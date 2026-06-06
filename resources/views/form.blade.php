@extends('layouts.app')

@section('title', 'Form - Tracer Study')
@section('header', 'Pengisian Kuesioner')

@section('content')
<style>
    dialog::backdrop {
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
    }
    dialog[open] {
        animation: show 0.25s ease-out;
    }
    @keyframes show {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<div class="max-w-5xl mx-auto">
    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 p-4 text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- No active form available --}}
    @if(!$activeForm)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kuesioner Tersedia</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto">Saat ini belum ada form kuesioner aktif untuk role Anda. Silakan hubungi administrator.</p>
        </div>
    @else
        @if($hasFilledActiveForm)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 text-center">
                <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Terima Kasih!</h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto">Anda sudah mengisi kuesioner aktif saat ini ("{{ $activeForm->title }}"). Jawaban Anda telah kami rekam dan dapat dilihat pada riwayat di bawah.</p>
            </div>
        @else
            {{-- Active Form --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Form Header --}}
            <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-[#800000]">{{ $activeForm->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Isi semua pertanyaan yang ditandai <span class="text-red-500">*</span> wajib</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $activeForm->target_role === 'alumni' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                    {{ ucfirst($activeForm->target_role) }}
                </span>
            </div>

            {{-- Dynamic Form --}}
            <form action="{{ route('form.store') }}" method="POST" class="p-4 sm:p-8">
                @csrf
                <input type="hidden" name="form_id" value="{{ $activeForm->id }}">

                <div class="space-y-6">
                    @foreach($activeForm->questions as $index => $question)
                    <div class="group">
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-[#800000]/10 text-[#800000] text-xs font-bold mr-2">{{ $index + 1 }}</span>
                            {{ $question->question_text }}
                            @if($question->is_required)
                                <span class="text-red-500 ml-0.5">*</span>
                            @endif
                        </label>

                        @switch($question->question_type)
                            @case('text')
                                <input type="text" name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm"
                                    placeholder="Ketik jawaban Anda..."
                                    {{ $question->is_required ? 'required' : '' }}>
                                @break

                            @case('number')
                                <input type="number" name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm"
                                    placeholder="Masukkan angka..."
                                    {{ $question->is_required ? 'required' : '' }}>
                                @break

                            @case('textarea')
                                <textarea name="answers[{{ $question->id }}]" rows="3"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm"
                                    placeholder="Tulis jawaban Anda..."
                                    {{ $question->is_required ? 'required' : '' }}>{{ old('answers.' . $question->id) }}</textarea>
                                @break

                            @case('radio')
                                <div class="space-y-2 mt-1">
                                    @foreach($question->options as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-[#800000]/30 hover:bg-[#800000]/5 transition-all cursor-pointer">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->option_text }}"
                                            class="h-4 w-4 text-[#800000] border-gray-300 focus:ring-[#800000]"
                                            {{ old('answers.' . $question->id) == $option->option_text ? 'checked' : '' }}
                                            {{ $question->is_required ? 'required' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @break

                            @case('select')
                                <select name="answers[{{ $question->id }}]"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm"
                                    {{ $question->is_required ? 'required' : '' }}>
                                    <option value="">-- Pilih jawaban --</option>
                                    @foreach($question->options as $option)
                                        <option value="{{ $option->option_text }}" {{ old('answers.' . $question->id) == $option->option_text ? 'selected' : '' }}>
                                            {{ $option->option_text }}
                                        </option>
                                    @endforeach
                                </select>
                                @break

                            @case('checkbox')
                                <div class="space-y-2 mt-1">
                                    @foreach($question->options as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-[#800000]/30 hover:bg-[#800000]/5 transition-all cursor-pointer">
                                        <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->option_text }}"
                                            class="rounded border-gray-300 text-[#800000] focus:ring-[#800000]"
                                            {{ is_array(old('answers.' . $question->id)) && in_array($option->option_text, old('answers.' . $question->id)) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @break
                        @endswitch
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#800000] to-[#a00000] px-6 py-3 text-white font-semibold shadow-lg shadow-red-900/20 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>
        @endif
    @endif

    {{-- Previous Dynamic Responses --}}
    @if($previousResponses->count() > 0)
    <div class="mt-8 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#800000]">Riwayat Pengisian Kuesioner</h3>
            <p class="text-sm text-gray-500 mt-0.5">{{ $previousResponses->count() }} respons yang sudah Anda kirim</p>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($previousResponses as $response)
            <div class="p-4 sm:px-8 sm:py-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                            {{ $response->form->target_role === 'alumni' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                            {{ $response->form->title }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $response->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($response->answers as $answer)
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-[11px] text-gray-500 font-medium uppercase tracking-wider">{{ $answer->question->question_text ?? 'Pertanyaan dihapus' }}</p>
                        <p class="text-sm text-gray-900 font-medium mt-0.5">{{ $answer->answer_text ?? '-' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Legacy Static Responses (backward compat) --}}
    @if($legacyResponses->count() > 0)
    <div class="mt-8 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-700">Riwayat Tracer Response (Lama)</h3>
        </div>
        <div class="overflow-x-auto">
            <table id="tracerTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                        <th class="px-4 sm:px-8 py-4 font-semibold w-16">No</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Waktu Tunggu</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Gaji Pertama</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Sesuai Prodi</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Saran</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($legacyResponses as $index => $response)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-8 py-4 text-gray-900 font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 sm:px-8 py-4 text-gray-500">{{ $response->waktu_tunggu_kerja }} bln</td>
                        <td class="px-4 sm:px-8 py-4 text-gray-500">{{ $response->gaji_pertama ? 'Rp ' . number_format($response->gaji_pertama, 0, ',', '.') : '-' }}</td>
                        <td class="px-4 sm:px-8 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $response->is_sesuai_prodi ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $response->is_sesuai_prodi ? 'Ya' : 'Tidak' }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-8 py-4 text-gray-500 max-w-xs truncate">{{ $response->saran_kurikulum ?? '-' }}</td>
                        <td class="px-4 sm:px-8 py-4 text-gray-500 whitespace-nowrap">{{ $response->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
