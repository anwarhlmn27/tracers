@extends('layouts.app')

@section('title', 'Dashboard - Tracer Study')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-[#800000] rounded-2xl p-6 shadow-lg shadow-red-900/20 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-white/80 text-sm font-medium">Total Alumni</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalAlumni) }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="bg-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-900/20 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-white/80 text-sm font-medium">Response Rate (Alumni)</p>
                <p class="text-3xl font-bold mt-2">{{ $responseRate }}%</p>
            </div>
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-900/20 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-white/80 text-sm font-medium">Kuesioner Aktif</p>
                <p class="text-3xl font-bold mt-2">{{ $activeForms }} <span class="text-lg font-normal opacity-70">/ {{ $totalForms }}</span></p>
            </div>
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Responses Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900">Respons Kuesioner Terbaru</h3>
        <a href="{{ route('questionnaires.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm">
                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Responden</th>
                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Role</th>
                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Judul Form</th>
                    <th class="px-6 py-4 font-semibold whitespace-nowrap">Tanggal Submit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($recentResponses as $response)
                @php
                    $roleTarget = $response->form->target_role ?? '-';
                    $namaResponden = $response->user->name ?? '-';
                    $prodi = '-';
                    if ($roleTarget === 'alumni' && $response->user->student) {
                        $namaResponden = $response->user->student->nama_student;
                        $prodi = $response->user->student->prodi->nama_prodi ?? '-';
                    }
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($namaResponden, 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-900 font-medium">{{ $namaResponden }}</span>
                                @if($roleTarget === 'alumni')
                                <span class="text-xs text-gray-500">{{ $prodi }}</span>
                                @else
                                <span class="text-xs text-gray-500">{{ $response->user->email }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $roleTarget === 'alumni' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                            {{ $roleTarget }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 font-medium whitespace-nowrap">
                        {{ $response->form->title ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-gray-900 font-medium text-xs">{{ $response->created_at->format('d M Y') }}</span>
                            <span class="text-gray-400 text-[11px]">{{ $response->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        Belum ada respons kuesioner yang masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
