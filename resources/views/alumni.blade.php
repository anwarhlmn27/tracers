@extends('layouts.app')

@section('title', 'Alumni Data - Tracer Study')
@section('header', 'Alumni Data')

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

<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-[#800000]">Daftar Data Alumni</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola data alumni mahasiswa di sini</p>
            </div>
            
            <button onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#800000] to-[#a00000] px-5 py-2.5 text-white font-semibold shadow-lg shadow-red-900/20 transition-all duration-200 hover:shadow-xl hover:shadow-red-900/30 hover:-translate-y-0.5 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Alumni
            </button>
        </div>

        <div class="overflow-x-auto">
            <table id="alumniTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                        <th class="px-4 sm:px-6 py-4 font-semibold w-12">No</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">NIM</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Nama</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Prodi & Angkatan</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Status Alumni</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Pekerjaan</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Waktu Tunggu</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap">Response Rate</th>
                        <th class="px-4 sm:px-6 py-4 font-semibold whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($students as $index => $student)
                    @php
                        $alumniStatusColors = [
                            'aktif' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'lulus' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'cuti' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'drop_out' => 'bg-red-50 text-red-700 border-red-200',
                            'Bekerja (full time / part time)' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Wiraswasta' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'Melanjutkan Pendidikan' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'Tidak kerja tetapi sedang mencari kerja' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Belum memungkinkan bekerja' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $statusBadgeColors = [
                            'bekerja' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'wiraswasta' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'studi_lanjut' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'mencari_kerja' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'belum_memungkinkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $displayStatus = $student->status_alumni;
                        $badgeColor = $alumniStatusColors[$displayStatus] ?? ($statusBadgeColors[$displayStatus] ?? 'bg-gray-50 text-gray-700 border-gray-200');
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 text-gray-900 font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 sm:px-6 py-4 text-gray-700 font-mono text-xs">{{ $student->nim }}</td>
                        <td class="px-4 sm:px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if(isset($student->user->avatar) && $student->user->avatar)
                                    <img src="{{ filter_var($student->user->avatar, FILTER_VALIDATE_URL) ? $student->user->avatar : asset('storage/' . $student->user->avatar) }}" alt="{{ $student->nama_student }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-200">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#800000] to-[#b30000] text-white flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($student->nama_student, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-gray-900 font-medium whitespace-nowrap">{{ $student->nama_student }}</p>
                                    <p class="text-gray-400 text-xs">{{ $student->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">{{ $student->prodi->nama_prodi ?? '-' }}</div>
                            <div class="text-gray-400 text-xs">Angkatan <span class="font-semibold">{{ $student->angkatan }}</span></div>
                        </td>

                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            @if($student->status_alumni)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeColor }}">
                                    {{ $student->status_alumni }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            @if($student->nama_perusahaan || $student->jabatan)
                                <div class="text-gray-900 font-medium">{{ $student->nama_perusahaan ?? '-' }}</div>
                                <div class="text-gray-500 text-xs">
                                    {{ $student->jabatan ?? '-' }}
                                    @if($student->tempat_kerja)
                                        <span class="ml-1 text-[10px] uppercase font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">
                                            {{ $student->tempat_kerja }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $student->waktu_tunggu_kerja ?? '-' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            @if(isset($student->response_rate))
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-gray-700">{{ $student->response_rate }}%</span>
                                    <div class="w-16 bg-gray-100 rounded-full h-1.5 hidden sm:block">
                                        <div class="bg-red-600 h-1.5 rounded-full" style="width: {{ $student->response_rate }}%"></div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openEditModal('{{ $student->id }}', '{{ addslashes($student->nama_student) }}', '{{ $student->nim }}', '{{ $student->prodi_id }}', '{{ $student->angkatan }}', '{{ $student->status }}', '{{ addslashes($student->status_alumni ?? '') }}', '{{ addslashes($student->nama_perusahaan ?? '') }}', '{{ addslashes($student->jabatan ?? '') }}', '{{ addslashes($student->tempat_kerja ?? '') }}', '{{ $student->response_rate ?? '' }}', '{{ addslashes($student->waktu_tunggu_kerja ?? '') }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="confirmDelete('{{ $student->id }}', '{{ addslashes($student->nama_student) }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-8 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>Belum ada data alumni.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<dialog id="addAlumniModal" closedby="any" class="fixed inset-0 m-auto rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-2xl w-[calc(100%-2rem)] sm:w-full max-w-2xl bg-white overflow-y-auto max-h-[90vh]">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-[#800000]">Tambah Data Alumni</h3>
        <button type="button" onclick="document.getElementById('addAlumniModal').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <form action="{{ route('alumni.store') }}" method="POST" class="space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_nama">Nama Lengkap</label>
                <input id="add_nama" name="nama_student" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="Masukkan nama lengkap" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_nim">NIM</label>
                <input id="add_nim" name="nim" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="Contoh: 12345678" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_email">Email</label>
                <input id="add_email" name="email" type="email" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="email@example.com" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_prodi">Program Studi</label>
                <select id="add_prodi" name="prodi_id" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
                    <option value="">Pilih Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_angkatan">Angkatan</label>
                <input id="add_angkatan" name="angkatan" type="number" min="2000" max="2099" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="Contoh: 2024" required>
            </div>
            <input type="hidden" id="add_status" name="status" value="lulus">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_status_alumni">Status Alumni</label>
                <select id="add_status_alumni" name="status_alumni" onchange="toggleAddCompanyFields()" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm">
                    <option value="">Pilih Status Alumni</option>
                    <option value="Bekerja (full time / part time)">Bekerja (full time / part time)</option>
                    <option value="Wiraswasta">Wiraswasta</option>
                    <option value="Melanjutkan Pendidikan">Melanjutkan Pendidikan</option>
                    <option value="Tidak kerja tetapi sedang mencari kerja">Tidak kerja tetapi sedang mencari kerja</option>
                    <option value="Belum memungkinkan bekerja">Belum memungkinkan bekerja</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_waktu_tunggu_kerja">Waktu Tunggu Pekerjaan</label>
                <input id="add_waktu_tunggu_kerja" name="waktu_tunggu_kerja" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="Contoh: 3 bulan, 1 tahun, langsung">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_response_rate">Response Rate (%)</label>
                <input id="add_response_rate" name="response_rate" type="number" min="0" max="100" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="Contoh: 85">
            </div>

            <div id="add_company_fields" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4 hidden bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_nama_perusahaan">Nama Perusahaan</label>
                    <input id="add_nama_perusahaan" name="nama_perusahaan" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-[#800000] focus:border-[#800000] text-xs" placeholder="Nama perusahaan">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_jabatan">Jabatan</label>
                    <input id="add_jabatan" name="jabatan" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-[#800000] focus:border-[#800000] text-xs" placeholder="Jabatan">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_tempat_kerja">Tempat Kerja</label>
                    <select id="add_tempat_kerja" name="tempat_kerja" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-[#800000] focus:border-[#800000] text-xs">
                        <option value="">Pilih Tempat Kerja</option>
                        <option value="Lokal">Lokal</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Multinasional">Multinasional</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <button type="button" onclick="document.getElementById('addAlumniModal').close()" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-gray-700 font-semibold shadow-sm transition hover:bg-gray-50 text-sm">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[#800000] to-[#a00000] px-5 py-2.5 text-white font-semibold shadow-sm transition hover:shadow-lg text-sm">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editAlumniModal" closedby="any" class="fixed inset-0 m-auto rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-2xl w-[calc(100%-2rem)] sm:w-full max-w-2xl bg-white overflow-y-auto max-h-[90vh]">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-[#800000]">Edit Data Alumni</h3>
        <button type="button" onclick="document.getElementById('editAlumniModal').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <form id="editForm" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_nama">Nama Lengkap</label>
                <input id="edit_nama" name="nama_student" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_nim">NIM</label>
                <input id="edit_nim" name="nim" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_prodi">Program Studi</label>
                <select id="edit_prodi" name="prodi_id" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_angkatan">Angkatan</label>
                <input id="edit_angkatan" name="angkatan" type="number" min="2000" max="2099" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
            </div>
            <input type="hidden" id="edit_status" name="status" value="lulus">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_status_alumni">Status Alumni</label>
                <select id="edit_status_alumni" name="status_alumni" onchange="toggleEditCompanyFields()" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm">
                    <option value="">Pilih Status Alumni</option>
                    <option value="Bekerja (full time / part time)">Bekerja (full time / part time)</option>
                    <option value="Wiraswasta">Wiraswasta</option>
                    <option value="Melanjutkan Pendidikan">Melanjutkan Pendidikan</option>
                    <option value="Tidak kerja tetapi sedang mencari kerja">Tidak kerja tetapi sedang mencari kerja</option>
                    <option value="Belum memungkinkan bekerja">Belum memungkinkan bekerja</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_waktu_tunggu_kerja">Waktu Tunggu Pekerjaan</label>
                <input id="edit_waktu_tunggu_kerja" name="waktu_tunggu_kerja" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_response_rate">Response Rate (%)</label>
                <input id="edit_response_rate" name="response_rate" type="number" min="0" max="100" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm">
            </div>

            <div id="edit_company_fields" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4 hidden bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_nama_perusahaan">Nama Perusahaan</label>
                    <input id="edit_nama_perusahaan" name="nama_perusahaan" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-[#800000] focus:border-[#800000] text-xs">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_jabatan">Jabatan</label>
                    <input id="edit_jabatan" name="jabatan" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-[#800000] focus:border-[#800000] text-xs">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_tempat_kerja">Tempat Kerja</label>
                    <select id="edit_tempat_kerja" name="tempat_kerja" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-[#800000] focus:border-[#800000] text-xs">
                        <option value="">Pilih Tempat Kerja</option>
                        <option value="Lokal">Lokal</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Multinasional">Multinasional</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <button type="button" onclick="document.getElementById('editAlumniModal').close()" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-gray-700 font-semibold shadow-sm transition hover:bg-gray-50 text-sm">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[#800000] to-[#a00000] px-5 py-2.5 text-white font-semibold shadow-sm transition hover:shadow-lg text-sm">
                Update
            </button>
        </div>
    </form>
</dialog>

<!-- Delete Form (hidden) -->
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
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
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        if ($('#alumniTable tbody tr').length > 0 && !$('#alumniTable tbody tr td[colspan]').length) {
            $('#alumniTable').DataTable({
                responsive: true,
                order: [[0, 'asc']],
                language: {
                    url: '{{ asset('js/datatables-id.json') }}',
                }
            });
        }
    });

    function openAddModal() {
        document.getElementById('add_status_alumni').value = '';
        document.getElementById('add_waktu_tunggu_kerja').value = '';
        document.getElementById('add_response_rate').value = '';
        document.getElementById('add_nama_perusahaan').value = '';
        document.getElementById('add_jabatan').value = '';
        document.getElementById('add_tempat_kerja').value = '';
        toggleAddCompanyFields();
        document.getElementById('addAlumniModal').showModal();
    }

    function toggleAddCompanyFields() {
        const status = document.getElementById('add_status_alumni').value;
        const compFields = document.getElementById('add_company_fields');
        if (status === 'Bekerja (full time / part time)' || status === 'Wiraswasta') {
            compFields.classList.remove('hidden');
        } else {
            compFields.classList.add('hidden');
        }
    }

    function toggleEditCompanyFields() {
        const status = document.getElementById('edit_status_alumni').value;
        const compFields = document.getElementById('edit_company_fields');
        if (status === 'Bekerja (full time / part time)' || status === 'Wiraswasta') {
            compFields.classList.remove('hidden');
        } else {
            compFields.classList.add('hidden');
        }
    }

    function openEditModal(id, nama, nim, prodiId, angkatan, status, statusAlumni, namaPerusahaan, jabatan, tempatKerja, responseRate, waktuTungguKerja) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nim').value = nim;
        document.getElementById('edit_prodi').value = prodiId;
        document.getElementById('edit_angkatan').value = angkatan;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_status_alumni').value = statusAlumni || '';
        document.getElementById('edit_nama_perusahaan').value = namaPerusahaan || '';
        document.getElementById('edit_jabatan').value = jabatan || '';
        document.getElementById('edit_tempat_kerja').value = tempatKerja || '';
        document.getElementById('edit_response_rate').value = responseRate || '';
        document.getElementById('edit_waktu_tunggu_kerja').value = waktuTungguKerja || '';
        document.getElementById('editForm').action = '/alumni/' + id;
        toggleEditCompanyFields();
        document.getElementById('editAlumniModal').showModal();
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Data Alumni?',
            html: `Yakin ingin menghapus data <strong>${nama}</strong>? Tindakan ini tidak bisa dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#800000',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = '/alumni/' + id;
                form.submit();
            }
        });
    }

    // Dialog light-dismiss fallback
    document.querySelectorAll('dialog').forEach(dialog => {
        if (!('closedBy' in HTMLDialogElement.prototype)) {
            dialog.addEventListener('click', (event) => {
                if (event.target !== dialog) return;
                const rect = dialog.getBoundingClientRect();
                const isInside = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height &&
                    rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
                if (!isInside) dialog.close();
            });
        }
    });
</script>
@endpush
