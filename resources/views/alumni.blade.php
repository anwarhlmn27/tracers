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
    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 p-4 text-green-700 flex justify-between items-center animate-fade-in">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700">
            <span>{{ session('error') }}</span>
        </div>
    @endif

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
                        <th class="px-4 sm:px-8 py-4 font-semibold w-16">No</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">NIM</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Nama</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Program Studi</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Angkatan</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Status</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($students as $index => $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-gray-900 font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-gray-700 font-mono text-xs">{{ $student->nim }}</td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#800000] to-[#b30000] text-white flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($student->nama_student, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-gray-900 font-medium">{{ $student->nama_student }}</p>
                                    <p class="text-gray-400 text-xs">{{ $student->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-gray-500 whitespace-nowrap">{{ $student->prodi->nama_prodi ?? '-' }}</td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-gray-500">{{ $student->angkatan }}</td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5">
                            @php
                                $statusColors = [
                                    'aktif' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'lulus' => 'bg-green-50 text-green-700 border-green-200',
                                    'cuti' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'drop_out' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$student->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                {{ ucfirst(str_replace('_', ' ', $student->status)) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openEditModal('{{ $student->id }}', '{{ $student->nama_student }}', '{{ $student->nim }}', '{{ $student->prodi_id }}', '{{ $student->angkatan }}', '{{ $student->status }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="confirmDelete('{{ $student->id }}', '{{ $student->nama_student }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-gray-400">
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
<dialog id="addAlumniModal" closedby="any" class="fixed inset-0 m-auto rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-2xl w-[calc(100%-2rem)] sm:w-full max-w-lg bg-white overflow-visible">
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
                <input id="add_angkatan" name="angkatan" type="number" min="2000" max="2099" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="2024" required>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_status">Status</label>
                <select id="add_status" name="status" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
                    <option value="aktif">Aktif</option>
                    <option value="lulus">Lulus</option>
                    <option value="cuti">Cuti</option>
                    <option value="drop_out">Drop Out</option>
                </select>
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
<dialog id="editAlumniModal" closedby="any" class="fixed inset-0 m-auto rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-2xl w-[calc(100%-2rem)] sm:w-full max-w-lg bg-white overflow-visible">
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
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_status">Status</label>
                <select id="edit_status" name="status" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
                    <option value="aktif">Aktif</option>
                    <option value="lulus">Lulus</option>
                    <option value="cuti">Cuti</option>
                    <option value="drop_out">Drop Out</option>
                </select>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        if ($('#alumniTable tbody tr').length > 0 && !$('#alumniTable tbody tr td[colspan]').length) {
            $('#alumniTable').DataTable({
                responsive: true,
                order: [[0, 'asc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                }
            });
        }
    });

    function openAddModal() {
        document.getElementById('addAlumniModal').showModal();
    }

    function openEditModal(id, nama, nim, prodiId, angkatan, status) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nim').value = nim;
        document.getElementById('edit_prodi').value = prodiId;
        document.getElementById('edit_angkatan').value = angkatan;
        document.getElementById('edit_status').value = status;
        document.getElementById('editForm').action = '/alumni/' + id;
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
