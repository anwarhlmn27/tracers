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

{{-- =====================================================
     SECTION: ANALITIK KARIR ALUMNI (DASHBOARD)
====================================================== --}}
<div class="mt-8 mb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-1">Analitik Karir Alumni</h2>
    <p class="text-sm text-gray-500 mb-6">Ringkasan performa lulusan berdasarkan tracer study</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- 1. Waktu Tunggu Pekerjaan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Waktu Tunggu Mendapatkan Pekerjaan</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Bulan menunggu setelah lulus</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="relative" style="height:300px">
                <canvas id="waktuTungguChart"></canvas>
            </div>
        </div>

        {{-- 2. Skala Tempat Kerja --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Skala Tempat Kerja</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Sebaran skala perusahaan</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="relative flex items-center justify-center" style="height:300px">
                <canvas id="skalaTempatChart"></canvas>
            </div>
        </div>

        {{-- 3. Distribusi Pendapatan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Distribusi Pendapatan</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Rentang gaji per bulan (Rupiah)</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="relative" style="height:300px">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>

        {{-- 4. Kesesuaian Pekerjaan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Kesesuaian dengan Prodi</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Relevansi bidang studi</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
            </div>
            <div class="relative flex items-center justify-center" style="height:300px">
                <canvas id="kesesuaianChart"></canvas>
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

@push('scripts')
<script src="{{ asset('assets/js/chart.umd.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color palette
    const maroon = '#800000';
    const emerald = '#059669';
    const amber = '#d97706';
    const violet = '#7c3aed';
    const rose = '#e11d48';
    const cyan = '#0891b2';
    
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6b7280';

    // 1. Waktu Tunggu (Bar)
    new Chart(document.getElementById('waktuTungguChart'), {
        type: 'bar',
        data: {
            labels: @json($waktuTungguLabels),
            datasets: [{
                label: 'Jumlah Alumni',
                data: @json($waktuTungguData),
                backgroundColor: [emerald, cyan, amber, violet, rose],
                borderRadius: 10,
                borderSkipped: false,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1f2937', cornerRadius: 12, padding: 12 }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' }, border: { display: false } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false }, border: { display: false } }
            }
        }
    });

    // 2. Skala Tempat Kerja (Doughnut)
    const skalaTempatRaw = @json($skalaTempat);
    const skalaLabels = Object.keys(skalaTempatRaw).map(l => l.charAt(0).toUpperCase() + l.slice(1));
    const skalaData   = Object.values(skalaTempatRaw);
    const skalaEmpty  = skalaData.every(v => v === 0);
    new Chart(document.getElementById('skalaTempatChart'), {
        type: 'doughnut',
        data: {
            labels: skalaEmpty ? ['Belum ada data'] : skalaLabels,
            datasets: [{
                data: skalaEmpty ? [1] : skalaData,
                backgroundColor: skalaEmpty ? ['#e5e7eb'] : [emerald, cyan, violet],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 10, font: { size: 12 }, padding: 16 } },
                tooltip: { backgroundColor: '#1f2937', cornerRadius: 12, padding: 12 }
            }
        }
    });

    // 3. Pendapatan (Bar horizontal)
    const pendapatanRaw = @json($pendapatanData);
    const orderedSalaryLabels = ['< 1.000.000','1.000.000 - 5.000.000','5.000.000 - 10.000.000','10.000.000 - 20.000.000','> 20.000.000'];
    const pendapatanLabels = orderedSalaryLabels.filter(l => pendapatanRaw[l] !== undefined);
    const pendapatanValues = pendapatanLabels.map(l => pendapatanRaw[l]);
    new Chart(document.getElementById('pendapatanChart'), {
        type: 'bar',
        data: {
            labels: pendapatanLabels.length ? pendapatanLabels : ['Belum ada data'],
            datasets: [{
                label: 'Jumlah Alumni',
                data: pendapatanValues.length ? pendapatanValues : [0],
                backgroundColor: [maroon, '#b91c1c', '#dc2626', '#ef4444', '#f87171'],
                borderRadius: 10,
                borderSkipped: false,
                barPercentage: 0.6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1f2937', cornerRadius: 12, padding: 12 }
            },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' }, border: { display: false } },
                y: { ticks: { font: { size: 11 } }, grid: { display: false }, border: { display: false } }
            }
        }
    });

    // 4. Kesesuaian Pekerjaan (Pie)
    const kesesuaianRaw = @json($kesesuaianData);
    const kesesuaianOrder = ['Sangat Sesuai', 'Sesuai', 'Kurang Sesuai', 'Tidak Sesuai'];
    const kesesuaianColors = [emerald, cyan, amber, rose];
    const kesesuaianLabels = kesesuaianOrder.filter(l => kesesuaianRaw[l] !== undefined);
    const kesesuaianValues = kesesuaianLabels.map(l => kesesuaianRaw[l]);
    const kesesuaianEmpty  = kesesuaianValues.every(v => v === 0) || kesesuaianLabels.length === 0;
    new Chart(document.getElementById('kesesuaianChart'), {
        type: 'doughnut',
        data: {
            labels: kesesuaianEmpty ? ['Belum ada data'] : kesesuaianLabels,
            datasets: [{
                data: kesesuaianEmpty ? [1] : kesesuaianValues,
                backgroundColor: kesesuaianEmpty ? ['#e5e7eb'] : kesesuaianColors.slice(0, kesesuaianLabels.length),
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 10, font: { size: 12 }, padding: 16 } },
                tooltip: { backgroundColor: '#1f2937', cornerRadius: 12, padding: 12 }
            }
        }
    });
});
</script>
@endpush
