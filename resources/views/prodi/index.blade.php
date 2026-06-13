@extends('layouts.app')

@section('title', 'Master Program Studi - Tracer Study')
@section('header', 'Master Program Studi')

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
        <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 p-4 text-green-700 flex justify-between items-center shadow-sm animate-fade-in">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700 shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-[#800000]">Daftar Program Studi</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola data program studi mahasiswa di sini</p>
            </div>
            
            <button onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#800000] to-[#a00000] px-5 py-2.5 text-white font-semibold shadow-lg shadow-red-900/20 transition-all duration-200 hover:shadow-xl hover:shadow-red-900/30 hover:-translate-y-0.5 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Program Studi
            </button>
        </div>

        <div class="overflow-x-auto">
            <table id="prodiTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                        <th class="px-4 sm:px-8 py-4 font-semibold w-16">No</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Kode Prodi</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap">Nama Program Studi</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap text-center">Jumlah Alumni</th>
                        <th class="px-4 sm:px-8 py-4 font-semibold whitespace-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($prodis as $index => $prodi)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-gray-900 font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-gray-700 font-mono text-xs">{{ $prodi->kode_prodi }}</td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-gray-900 font-medium">{{ $prodi->nama_prodi }}</td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                {{ $prodi->students_count }} Alumni
                            </span>
                        </td>
                        <td class="px-4 sm:px-8 py-4 sm:py-5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openEditModal('{{ $prodi->id }}', '{{ addslashes($prodi->nama_prodi) }}', '{{ addslashes($prodi->kode_prodi) }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="confirmDelete('{{ $prodi->id }}', '{{ addslashes($prodi->nama_prodi) }}', {{ $prodi->students_count }})"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <span>Belum ada data program studi.</span>
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
<dialog id="addProdiModal" closedby="any" class="fixed inset-0 m-auto rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-2xl w-[calc(100%-2rem)] sm:w-full max-w-md bg-white overflow-visible">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-[#800000]">Tambah Program Studi</h3>
        <button type="button" onclick="document.getElementById('addProdiModal').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <form action="{{ route('prodi.store') }}" method="POST" class="space-y-5">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_kode_prodi">Kode Program Studi</label>
                <input id="add_kode_prodi" name="kode_prodi" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="Contoh: IF, SI, TI" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="add_nama_prodi">Nama Program Studi</label>
                <input id="add_nama_prodi" name="nama_prodi" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" placeholder="Masukkan nama program studi" required>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <button type="button" onclick="document.getElementById('addProdiModal').close()" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-gray-700 font-semibold shadow-sm transition hover:bg-gray-50 text-sm">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[#800000] to-[#a00000] px-5 py-2.5 text-white font-semibold shadow-sm transition hover:shadow-lg text-sm">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editProdiModal" closedby="any" class="fixed inset-0 m-auto rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-2xl w-[calc(100%-2rem)] sm:w-full max-w-md bg-white overflow-visible">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-[#800000]">Edit Program Studi</h3>
        <button type="button" onclick="document.getElementById('editProdiModal').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <form id="editForm" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_kode_prodi">Kode Program Studi</label>
                <input id="edit_kode_prodi" name="kode_prodi" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5" for="edit_nama_prodi">Nama Program Studi</label>
                <input id="edit_nama_prodi" name="nama_prodi" type="text" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:ring-[#800000] focus:border-[#800000] text-sm" required>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <button type="button" onclick="document.getElementById('editProdiModal').close()" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-gray-700 font-semibold shadow-sm transition hover:bg-gray-50 text-sm">
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
        if ($('#prodiTable tbody tr').length > 0 && !$('#prodiTable tbody tr td[colspan]').length) {
            $('#prodiTable').DataTable({
                responsive: true,
                order: [[1, 'asc']],
                language: {
                    url: '{{ asset('assets/locales/id.json') }}',
                }
            });
        }
    });

    function openAddModal() {
        document.getElementById('addProdiModal').showModal();
    }

    function openEditModal(id, nama, kode) {
        document.getElementById('edit_nama_prodi').value = nama;
        document.getElementById('edit_kode_prodi').value = kode;
        document.getElementById('editForm').action = '/prodi/' + id;
        document.getElementById('editProdiModal').showModal();
    }

    function confirmDelete(id, nama, count) {
        if (count > 0) {
            Swal.fire({
                title: 'Tidak Bisa Menghapus!',
                html: `Program Studi <strong>${nama}</strong> masih memiliki <strong>${count}</strong> data alumni terkait. Silakan pindahkan atau hapus data alumni tersebut terlebih dahulu.`,
                icon: 'error',
                confirmButtonColor: '#800000',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-2xl' }
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Program Studi?',
            html: `Yakin ingin menghapus program studi <strong>${nama}</strong>? Tindakan ini tidak bisa dibatalkan.`,
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
                form.action = '/prodi/' + id;
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
