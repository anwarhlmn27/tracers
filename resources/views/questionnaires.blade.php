@extends('layouts.app')

@section('title', 'Questionnaires - Tracer Study')
@section('header', 'Questionnaire Responses')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-[#800000]">Daftar Respons Kuesioner</h3>
                <p class="text-sm text-gray-500 mt-1">Semua respons tracer study dari mahasiswa</p>
            </div>
            
            <a href="{{ route('questionnaires.export') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 px-5 py-2.5 text-white font-semibold shadow-lg shadow-emerald-600/20 transition-all duration-200 hover:shadow-xl hover:shadow-emerald-600/30 hover:-translate-y-0.5 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export to Excel
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 sm:px-8 sm:py-6 border-b border-gray-100 bg-gray-50/50">
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $responses->count() }}</p>
                        <p class="text-xs text-gray-500">Total Respons Form</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $responses->pluck('user_id')->unique()->count() }}</p>
                        <p class="text-xs text-gray-500">Responden Unik</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        @php $totalJawaban = $responses->sum(function($r) { return $r->answers->count(); }); @endphp
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalJawaban) }}</p>
                        <p class="text-xs text-gray-500">Total Jawaban Diberikan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto" x-data="{ showModal: false, selectedAnswers: [], selectedName: '', selectedForm: '' }">
            <table id="questionnaireTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                        <th class="px-4 sm:px-6 py-4 font-semibold w-12">No</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Timestamp</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Target Role</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Nama Responden</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Judul Form</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Total Jawaban</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($responses as $index => $response)
                    @php
                        $roleTarget = $response->form->target_role ?? '-';
                        $namaResponden = $response->user->name ?? '-';
                        if ($roleTarget === 'alumni' && $response->user->student) {
                            $namaResponden = $response->user->student->nama_student;
                        }
                        $answersJson = \Illuminate\Support\Js::from($response->answers->map(function($a) {
                            return [
                                'question' => $a->question->question_text ?? 'Pertanyaan Dihapus',
                                'answer' => $a->answer_text ?? '-'
                            ];
                        }));
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 text-gray-900 font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-gray-900 font-medium text-xs">{{ $response->created_at->format('d M Y') }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $response->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $roleTarget === 'alumni' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $roleTarget }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#800000] to-[#b30000] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                    {{ strtoupper(substr($namaResponden, 0, 1)) }}
                                </div>
                                <span class="text-gray-900 font-medium">{{ $namaResponden }}</span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-gray-500 whitespace-nowrap max-w-[200px] truncate" title="{{ $response->form->title ?? '-' }}">
                            {{ $response->form->title ?? '-' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
                                {{ $response->answers->count() }} Jawaban
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <button type="button" @click="selectedAnswers = {{ $answersJson }}; selectedName = '{{ addslashes($namaResponden) }}'; selectedForm = '{{ addslashes($response->form->title ?? '-') }}'; showModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>Belum ada respons kuesioner.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Detail Modal -->
            <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 backdrop-blur-sm" style="display: none;">
                <div x-show="showModal" @click.away="showModal = false" x-transition.opacity class="relative w-full max-w-2xl p-4 sm:p-6 mx-auto">
                    <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[85vh]">
                        <!-- Modal Header -->
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 shrink-0">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900" x-text="'Detail Respons: ' + selectedName"></h3>
                                <p class="text-sm text-gray-500 mt-0.5" x-text="'Form: ' + selectedForm"></p>
                            </div>
                            <button @click="showModal = false" class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-red-500 hover:border-red-200 flex items-center justify-center transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="p-6 overflow-y-auto space-y-4">
                            <template x-for="(item, index) in selectedAnswers" :key="index">
                                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                    <p class="text-[11px] font-bold text-[#800000] uppercase tracking-wider mb-1" x-text="'Pertanyaan ' + (index + 1)"></p>
                                    <p class="text-sm text-gray-700 font-medium mb-3" x-text="item.question"></p>
                                    
                                    <div class="bg-white rounded-xl p-3 border border-gray-200 shadow-sm">
                                        <p class="text-sm text-gray-900 whitespace-pre-wrap" x-text="item.answer"></p>
                                    </div>
                                </div>
                            </template>
                            <div x-show="selectedAnswers.length === 0" class="text-center py-8 text-gray-500 text-sm">
                                Tidak ada jawaban untuk ditampilkan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper { padding: 1rem; }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 0.375rem 0.75rem; outline: none; margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #800000; box-shadow: 0 0 0 1px #800000; }
    table.dataTable.no-footer { border-bottom: 1px solid #f3f4f6; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: linear-gradient(135deg, #800000, #a00000); color: white !important; border: 1px solid #800000; border-radius: 0.5rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 0.5rem; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        if ($('#questionnaireTable tbody tr').length > 0 && !$('#questionnaireTable tbody tr td[colspan]').length) {
            $('#questionnaireTable').DataTable({
                responsive: true,
                order: [[1, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                }
            });
        }
    });
</script>
@endpush
