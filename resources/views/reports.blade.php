@extends('layouts.app')

@section('title', 'Reports & Analytics - Tracer Study')
@section('header', 'Reports & Analytics')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Overview Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Alumni -->
        <div class="relative overflow-hidden bg-gradient-to-br from-[#800000] to-[#a80000] rounded-2xl p-5 text-white shadow-lg shadow-red-900/20">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full translate-y-6 -translate-x-6"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Total Alumni</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalStudents) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-white/60">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Mahasiswa terdaftar
                </div>
            </div>
        </div>

        <!-- Response Rate -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 to-emerald-500 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full translate-y-6 -translate-x-6"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Response Rate</p>
                        <p class="text-3xl font-bold mt-1">{{ $responseRate }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-white/60">
                    {{ $alumniResponseCount }} dari {{ $totalStudents }} alumni
                </div>
            </div>
        </div>

        <!-- Total Atasan -->
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-5 text-white shadow-lg shadow-amber-500/20">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full translate-y-6 -translate-x-6"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Respons Atasan</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($atasanResponseCount) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-white/60">
                    Total Atasan/Perusahaan
                </div>
            </div>
        </div>

        <!-- Total Respons Form -->
        <div class="relative overflow-hidden bg-gradient-to-br from-violet-600 to-purple-600 rounded-2xl p-5 text-white shadow-lg shadow-violet-600/20">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full translate-y-6 -translate-x-6"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Total Semua Form</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalResponses) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-white/60">
                    Jumlah keseluruhan submisi kuesioner
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1: Trends & Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Line Chart: Trend Response per Bulan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Trend Respons Bulanan</h3>
                    <p class="text-sm text-gray-500 mt-0.5">12 bulan terakhir</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart: Sudah vs Belum Mengisi per Prodi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Pengisian Kuesioner per Prodi (Alumni)</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Perbandingan sudah vs belum mengisi</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="prodiCompareChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2: Dynamic Forms Charts -->
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Analitik Kuesioner (Dinamis)</h2>
        <p class="text-sm text-gray-500 mb-6">Grafik di bawah ini digenerate secara otomatis berdasarkan pertanyaan pilihan ganda pada form kuesioner aktif.</p>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($dynamicCharts as $chart)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 mb-2">{{ $chart['form_title'] }}</span>
                        <h3 class="text-md font-bold text-gray-900 leading-snug">{{ $chart['question_text'] }}</h3>
                    </div>
                </div>
                <div class="relative flex-1 mt-auto flex items-center justify-center min-h-[280px]">
                    <canvas id="{{ $chart['id'] }}"></canvas>
                </div>
            </div>
            @endforeach

            @if(empty($dynamicCharts))
            <div class="lg:col-span-2 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Data Analitik</h3>
                <p class="text-sm text-gray-500">Tidak ada pertanyaan bertipe pilihan ganda (radio/select) yang sudah diisi oleh responden pada form aktif saat ini.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Student per angkatan -->
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Alumni per Angkatan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Jumlah mahasiswa per tahun masuk</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <div class="relative" style="height: 320px;">
            <canvas id="angkatanChart"></canvas>
        </div>
    </div>

    {{-- =====================================================
         SECTION: ANALITIK KARIR ALUMNI
    ====================================================== --}}
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-1">Analitik Karir Alumni</h2>
        <p class="text-sm text-gray-500 mb-6">Data berdasarkan jawaban kuesioner tracer study alumni</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- 1. Waktu Tunggu Pekerjaan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Waktu Tunggu Mendapatkan Pekerjaan</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Distribusi berapa lama alumni menunggu kerja setelah lulus</p>
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
                        <p class="text-sm text-gray-500 mt-0.5">Sebaran alumni berdasarkan skala perusahaan tempat bekerja</p>
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
                        <h3 class="text-lg font-bold text-gray-900">Distribusi Pendapatan Alumni</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Rentang pendapatan per bulan (Rupiah)</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="relative" style="height:300px">
                    <canvas id="pendapatanChart"></canvas>
                </div>
            </div>

            {{-- 4. Kesesuaian Pekerjaan dengan Prodi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Kesesuaian Pekerjaan dengan Program Studi</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Seberapa relevan pekerjaan alumni dengan jurusannya</p>
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

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/chart.umd.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color palette
    const maroon = '#800000';
    const maroonLight = 'rgba(128, 0, 0, 0.15)';
    const emerald = '#059669';
    const amber = '#d97706';
    const violet = '#7c3aed';
    const rose = '#e11d48';
    const cyan = '#0891b2';
    
    const colors = [emerald, rose, amber, violet, cyan, maroon, '#3b82f6', '#f97316', '#14b8a6', '#6366f1'];

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6b7280';

    // 1. Trend Line Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                label: 'Jumlah Respons',
                data: @json($monthCounts),
                borderColor: maroon,
                backgroundColor: maroonLight,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: maroon,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#f9fafb',
                    bodyColor: '#d1d5db',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 12,
                    padding: 12,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 11 } },
                    grid: { color: '#f3f4f6' },
                    border: { display: false }
                },
                x: {
                    ticks: { font: { size: 10 }, maxRotation: 45 },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });

    // 2. Bar: Sudah vs Belum per Prodi
    new Chart(document.getElementById('prodiCompareChart'), {
        type: 'bar',
        data: {
            labels: @json($prodiLabels),
            datasets: [
                {
                    label: 'Sudah Mengisi',
                    data: @json($sudahMengisi),
                    backgroundColor: emerald,
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                },
                {
                    label: 'Belum Mengisi',
                    data: @json($belumMengisi),
                    backgroundColor: '#e5e7eb',
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: { usePointStyle: true, pointStyleWidth: 12, font: { size: 11 }, padding: 16 }
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    cornerRadius: 12,
                    padding: 12,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 11 } },
                    grid: { color: '#f3f4f6' },
                    border: { display: false }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });

    // 3. Dynamic Charts Generation
    const dynamicChartsData = @json($dynamicCharts);
    dynamicChartsData.forEach((chartData, index) => {
        // Use Pie/Doughnut for shorter answer lists, Bar for longer
        const type = chartData.labels.length > 5 ? 'bar' : 'doughnut';
        
        new Chart(document.getElementById(chartData.id), {
            type: type,
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Total Pemilih',
                    data: chartData.data,
                    backgroundColor: type === 'bar' ? maroon : colors,
                    borderColor: type === 'bar' ? maroon : '#fff',
                    borderWidth: type === 'bar' ? 0 : 2,
                    borderRadius: type === 'bar' ? 6 : 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: type !== 'bar',
                        position: 'right',
                        labels: { usePointStyle: true, pointStyleWidth: 10, font: { size: 11 } }
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        cornerRadius: 12,
                        padding: 12,
                    }
                },
                scales: type === 'bar' ? {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { ticks: { maxRotation: 45, minRotation: 45 } }
                } : undefined
            }
        });
    });

    // 4. Bar: Alumni per Angkatan
    const angkatanData = @json($angkatanData);
    new Chart(document.getElementById('angkatanChart'), {
        type: 'bar',
        data: {
            labels: angkatanData.map(d => d.angkatan),
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: angkatanData.map(d => d.total),
                backgroundColor: cyan,
                borderRadius: 10,
                borderSkipped: false,
                barPercentage: 0.55,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1f2937', cornerRadius: 12, padding: 12 }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' }, border: { display: false } },
                x: { ticks: { font: { size: 12, weight: 500 } }, grid: { display: false }, border: { display: false } }
            }
        }
    });

    // 5. Waktu Tunggu (Bar)
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

    // 6. Skala Tempat Kerja (Doughnut)
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

    // 7. Pendapatan (Bar horizontal)
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

    // 8. Kesesuaian Pekerjaan (Pie)
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
