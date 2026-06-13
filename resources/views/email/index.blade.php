@extends('layouts.app')

@section('title', 'Email Blast')
@section('header', 'Email Blast')

@push('styles')
<!-- Quill CSS -->
<link href="{{ asset('assets/css/quill.snow.css') }}" rel="stylesheet">
<!-- TomSelect CSS -->
<link href="{{ asset('assets/css/tom-select.css') }}" rel="stylesheet">
<style>
    /* Quill Editor Customizations */
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        border-color: #d1d5db !important;
        background-color: #f9fafb;
        padding: 12px 16px !important;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        border-color: #d1d5db !important;
        font-family: 'Inter', sans-serif;
        background-color: #ffffff;
    }
    .ql-editor {
        min-height: 250px;
        font-size: 15px;
        color: #374151;
        padding: 1rem !important;
    }
    .ql-editor.ql-blank::before {
        font-style: normal;
        color: #9ca3af;
    }
    .ql-snow .ql-picker-label:hover, .ql-snow .ql-picker-label.ql-active, .ql-snow .ql-picker-item:hover, .ql-snow .ql-picker-item.ql-selected, .ql-snow .ql-stroke {
        stroke: #800000 !important;
    }
    .ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill {
        fill: #800000 !important;
    }
    .ql-snow .ql-picker-item.ql-selected, .ql-snow .ql-picker-item:hover {
        color: #800000 !important;
    }

    /* TomSelect Customizations */
    .ts-control {
        border-radius: 0.5rem !important;
        border-color: #d1d5db !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
        min-height: 42px !important;
        background-color: #ffffff !important;
    }
    .ts-control.focus {
        border-color: #800000 !important;
        box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1) !important;
    }
    .ts-dropdown {
        border-radius: 0.5rem !important;
        border-color: #d1d5db !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        margin-top: 0.25rem !important;
        font-size: 0.875rem !important;
    }
    .ts-dropdown .option.active {
        background-color: #800000 !important;
        color: white !important;
    }
    .ts-control .item {
        background-color: #800000 !important;
        color: white !important;
        border-radius: 0.375rem !important;
        padding: 2px 8px !important;
        font-weight: 500;
        margin-bottom: 2px !important;
    }
    .ts-control .item a.remove {
        color: rgba(255,255,255,0.7) !important;
    }
    .ts-control .item a.remove:hover {
        color: white !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ targetType: 'group' }">
    
    <!-- Title Area -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kirim Email Massal</h1>
            <p class="text-sm text-gray-500 mt-1">Buat dan kirim pengumuman atau undangan kuesioner ke alumni dan atasan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h3 class="font-bold">Berhasil!</h3>
                <p class="text-sm mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h3 class="font-bold">Terjadi Kesalahan</h3>
                @if(session('error'))
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                @endif
                @if($errors->any())
                    <ul class="list-disc pl-5 mt-2 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-[#800000] to-[#990000] px-6 py-4 border-b border-[#600000] flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-lg font-bold text-white">Komposisi Email</h2>
        </div>

        <form action="{{ route('email.send') }}" method="POST" enctype="multipart/form-data" id="emailForm" class="p-6 sm:p-8">
            @csrf

            <!-- Target Selection Area -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-8 border-b border-gray-100 pb-8">
                <div class="md:col-span-4">
                    <label class="block text-sm font-bold text-gray-800 mb-1">Tujuan Pengiriman</label>
                    <p class="text-xs text-gray-500 mb-4">Pilih apakah email ini untuk satu grup peran tertentu atau beberapa orang terpilih.</p>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition-colors" :class="targetType === 'group' ? 'border-[#800000] bg-red-50' : 'border-gray-200 hover:bg-gray-50'">
                            <input type="radio" name="target_type" value="group" x-model="targetType">
                            <div>
                                <span class="block font-semibold text-gray-800 text-sm">Berdasarkan Grup</span>
                                <span class="block text-xs text-gray-500">Kirim ke semua anggota grup</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition-colors" :class="targetType === 'individual' ? 'border-[#800000] bg-red-50' : 'border-gray-200 hover:bg-gray-50'">
                            <input type="radio" name="target_type" value="individual" x-model="targetType">
                            <div>
                                <span class="block font-semibold text-gray-800 text-sm">Pilih Individual</span>
                                <span class="block text-xs text-gray-500">Cari dan pilih per orang</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-8">
                    <!-- Group Selection -->
                    <div x-show="targetType === 'group'" x-transition.opacity>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Pilih Grup Penerima</label>
                        <select name="group" class="w-full rounded-lg border-gray-300 focus:border-[#800000] focus:ring focus:ring-[#800000] focus:ring-opacity-50 py-2.5 bg-gray-50 text-gray-800">
                            <option value="alumni">Grup: Alumni (Seluruh Alumni Terdaftar)</option>
                            <option value="atasan">Grup: Atasan (Seluruh Atasan Terdaftar)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pastikan format email user valid agar email dapat terkirim.
                        </p>
                    </div>

                    <!-- Individual Selection -->
                    <div x-show="targetType === 'individual'" style="display: none;" x-transition.opacity>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Pilih Pengguna</label>
                        <select name="users[]" id="users-select" multiple class="w-full no-ts" placeholder="Cari nama atau email pengguna...">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Subject & Content Area -->
            <div class="space-y-6">
                <!-- Subject -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Subjek Email</label>
                    <input type="text" name="subject" required class="w-full rounded-lg border-gray-300 focus:border-[#800000] focus:ring focus:ring-[#800000] focus:ring-opacity-50 text-gray-800 py-2.5 px-4" placeholder="Contoh: Undangan Pengisian Kuesioner Tracer Study 2026">
                </div>

                <!-- Attachment -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Lampiran Dokumen</label>
                    <div class="flex flex-col gap-4">
                        <input type="file" name="attachments[]" id="attachment" class="hidden" multiple>
                        <label for="attachment" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-3 text-[#E6A442]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="mb-1 text-sm text-gray-600"><span class="font-bold text-[#800000]">Klik untuk unggah</span> atau seret file ke sini</p>
                                <p class="text-xs text-gray-500">Bisa memilih lebih dari satu file (Maks 10MB/file)</p>
                            </div>
                        </label>
                        <!-- File Cards Container -->
                        <div id="file-cards-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2 empty:hidden"></div>
                    </div>
                </div>

                <!-- Body (Quill) -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Pesan Email</label>
                    <input type="hidden" name="body" id="bodyInput">
                    <div class="shadow-sm rounded-lg">
                        <div id="editor-container"></div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end items-center gap-4 mt-10 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#800000] text-white font-bold rounded-lg shadow-md hover:bg-[#600000] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#800000] focus:ring-offset-2 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Blast Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Quill JS -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<!-- TomSelect JS -->
<script src="{{ asset('assets/js/tom-select.complete.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TomSelect for individual users
        new TomSelect('#users-select', {
            plugins: ['remove_button'],
            maxOptions: null,
            placeholder: 'Pilih satu atau lebih pengguna...',
        });

        // Initialize Quill Editor
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Halo,\n\nTerima kasih atas kontribusi Anda...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Sync Quill content to hidden input before submit
        var form = document.getElementById('emailForm');
        form.onsubmit = function() {
            var bodyInput = document.getElementById('bodyInput');
            // Get HTML content
            bodyInput.value = quill.root.innerHTML;
            
            // Basic validation
            if (quill.getText().trim().length === 0) {
                alert('Pesan email tidak boleh kosong.');
                return false;
            }
            return true;
        };

        // File upload multiple display and remove logic
        const fileInput = document.getElementById('attachment');
        const fileCardsContainer = document.getElementById('file-cards-container');
        let dataTransfer = new DataTransfer();
        
        fileInput.addEventListener('change', function() {
            // Add new files to dataTransfer
            for (let i = 0; i < this.files.length; i++) {
                dataTransfer.items.add(this.files[i]);
            }
            // Update input files
            fileInput.files = dataTransfer.files;
            // Render UI
            renderFileCards();
        });

        function renderFileCards() {
            fileCardsContainer.innerHTML = '';
            Array.from(dataTransfer.files).forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'flex items-center justify-between p-3 border border-gray-200 rounded-xl bg-white shadow-sm';
                card.innerHTML = `
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="p-2 bg-red-50 rounded-lg text-[#800000] shrink-0 border border-red-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate" title="${file.name}">${file.name}</p>
                            <p class="text-xs text-gray-500 font-medium">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        </div>
                    </div>
                    <button type="button" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors shrink-0" onclick="removeFile(${index})" title="Hapus File">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                fileCardsContainer.appendChild(card);
            });
        }

        window.removeFile = function(index) {
            const newDt = new DataTransfer();
            Array.from(dataTransfer.files).forEach((file, i) => {
                if (i !== index) newDt.items.add(file);
            });
            dataTransfer = newDt;
            fileInput.files = dataTransfer.files;
            renderFileCards();
        }
    });
</script>
@endpush
