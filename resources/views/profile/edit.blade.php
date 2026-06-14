@extends(in_array(Auth::user()->role, ['alumni', 'atasan']) ? 'layouts.form' : 'layouts.app')

@section('title', 'Profile Settings - Tracer Study')
@section('header', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(in_array(Auth::user()->role, ['alumni', 'atasan']))
        <div class="mb-6">
            <a href="{{ route('form.create') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#800000] transition-colors font-medium bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    @endif
    
    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 p-4 text-green-700 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 text-amber-700 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="font-medium">{{ session('warning') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Header Section -->
        <div class="px-6 py-8 sm:p-10 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <!-- Avatar placeholder -->
                <div class="relative group">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover shadow-inner border-4 border-white">
                    @else
                        <div class="w-24 h-24 rounded-full bg-[#800000]/10 text-[#800000] flex items-center justify-center text-3xl font-bold shadow-inner border-4 border-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <div class="text-center sm:text-left">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500 mt-1 flex items-center justify-center sm:justify-start gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $user->email }}
                    </p>
                    <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#800000]/10 text-[#800000]">
                        Role: {{ ucfirst($user->role) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-6 sm:p-10">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Avatar File Input -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="avatar" class="block text-sm font-semibold text-gray-700 font-medium">Foto Profil</label>
                        <input type="file" name="avatar" id="avatar" class="w-full rounded-2xl border border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-2.5 bg-gray-50 focus:bg-white text-sm" accept="image/*">
                        <p class="text-xs text-gray-400">Format yang didukung: JPG, JPEG, PNG, GIF. Maksimal 2MB.</p>
                        @error('avatar')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="pl-11 w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white" required>
                        </div>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="pl-11 w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white" required>
                        </div>
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($user->role === 'alumni')
                    <hr class="border-gray-100">
                    
                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <h4 class="text-lg font-bold text-[#800000]">Informasi Alumni (Tracer Study)</h4>
                        </div>
                        <p class="text-sm text-gray-500">Lengkapi data akademik dan pekerjaan Anda untuk mendukung basis data tracer study.</p>

                        <!-- Academic Info (Editable) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- NIM -->
                            <div class="space-y-2">
                                <label for="nim" class="block text-sm font-semibold text-gray-700">NIM (Nomor Induk Mahasiswa)</label>
                                <input type="text" name="nim" id="nim" value="{{ old('nim', $user->student->nim ?? '') }}" class="w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white text-sm" placeholder="Masukkan NIM Anda" required>
                                @error('nim')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Program Studi -->
                            <div class="space-y-2">
                                <label for="prodi_id" class="block text-sm font-semibold text-gray-700">Program Studi</label>
                                <select name="prodi_id" id="prodi_id" class="w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white text-sm" required>
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}" {{ old('prodi_id', $user->student->prodi_id ?? '') === $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                                @error('prodi_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Angkatan -->
                            <div class="space-y-2">
                                <label for="angkatan" class="block text-sm font-semibold text-gray-700">Angkatan</label>
                                <input type="number" name="angkatan" id="angkatan" min="2000" max="2099" value="{{ old('angkatan', $user->student->angkatan ?? '') }}" class="w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white text-sm" placeholder="Contoh: 2020" required>
                                @error('angkatan')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Status Alumni -->
                            <div class="space-y-2">
                                <label for="status_alumni" class="block text-sm font-semibold text-gray-700">Status Alumni</label>
                                <select name="status_alumni" id="status_alumni" onchange="toggleProfileCompanyFields()" class="w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white text-sm" required>
                                    <option value="">Pilih Status Alumni</option>
                                    <option value="Bekerja (full time / part time)" {{ old('status_alumni', $user->student->status_alumni ?? '') === 'Bekerja (full time / part time)' ? 'selected' : '' }}>Bekerja (full time / part time)</option>
                                    <option value="Wiraswasta" {{ old('status_alumni', $user->student->status_alumni ?? '') === 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="Melanjutkan Pendidikan" {{ old('status_alumni', $user->student->status_alumni ?? '') === 'Melanjutkan Pendidikan' ? 'selected' : '' }}>Melanjutkan Pendidikan</option>
                                    <option value="Tidak kerja tetapi sedang mencari kerja" {{ old('status_alumni', $user->student->status_alumni ?? '') === 'Tidak kerja tetapi sedang mencari kerja' ? 'selected' : '' }}>Tidak kerja tetapi sedang mencari kerja</option>
                                    <option value="Belum memungkinkan bekerja" {{ old('status_alumni', $user->student->status_alumni ?? '') === 'Belum memungkinkan bekerja' ? 'selected' : '' }}>Belum memungkinkan bekerja</option>
                                </select>
                                @error('status_alumni')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Waktu Tunggu Kerja -->
                            <div class="space-y-2">
                                <label for="waktu_tunggu_kerja" class="block text-sm font-semibold text-gray-700">Waktu Tunggu Pekerjaan</label>
                                <input type="text" name="waktu_tunggu_kerja" id="waktu_tunggu_kerja" value="{{ old('waktu_tunggu_kerja', $user->student->waktu_tunggu_kerja ?? '') }}" class="w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white text-sm" placeholder="Contoh: 3 bulan, 1 tahun, langsung">
                                @error('waktu_tunggu_kerja')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Response Rate -->
                            <div class="space-y-2">
                                <label for="response_rate" class="block text-sm font-semibold text-gray-700">Response Rate (%)</label>
                                <input type="number" name="response_rate" id="response_rate" min="0" max="100" value="{{ old('response_rate', $user->student->response_rate ?? '') }}" class="w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white text-sm" placeholder="Contoh: 85">
                                @error('response_rate')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Company specific fields -->
                        <div id="profile_company_fields" class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-6 rounded-2xl border border-gray-100 hidden">
                            <div class="space-y-2">
                                <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $user->student->nama_perusahaan ?? '') }}" class="w-full rounded-xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-2.5 bg-white text-sm" placeholder="Nama perusahaan">
                                @error('nama_perusahaan')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="jabatan" class="block text-sm font-semibold text-gray-700">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $user->student->jabatan ?? '') }}" class="w-full rounded-xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-2.5 bg-white text-sm" placeholder="Jabatan">
                                @error('jabatan')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="tempat_kerja" class="block text-sm font-semibold text-gray-700">Tempat Bekerja</label>
                                <select name="tempat_kerja" id="tempat_kerja" class="w-full rounded-xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-2.5 bg-white text-sm">
                                    <option value="">Pilih Tempat Kerja</option>
                                    <option value="Lokal" {{ old('tempat_kerja', $user->student->tempat_kerja ?? '') === 'Lokal' ? 'selected' : '' }}>Lokal</option>
                                    <option value="Nasional" {{ old('tempat_kerja', $user->student->tempat_kerja ?? '') === 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                    <option value="Multinasional" {{ old('tempat_kerja', $user->student->tempat_kerja ?? '') === 'Multinasional' ? 'selected' : '' }}>Multinasional</option>
                                </select>
                                @error('tempat_kerja')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <hr class="border-gray-100">

                <div class="space-y-4">
                    <h4 class="text-lg font-bold text-gray-900">Ubah Password</h4>
                    <p class="text-sm text-gray-500">Kosongkan jika Anda tidak ingin mengubah password saat ini.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password" class="pl-11 w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white" placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="pl-11 w-full rounded-2xl border-gray-200 focus:border-[#800000] focus:ring focus:ring-[#800000]/20 transition-all px-4 py-3 bg-gray-50 focus:bg-white" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#800000] px-8 py-3 text-white font-semibold shadow-sm shadow-[#800000]/30 transition-all hover:bg-[#5d0000] hover:shadow-md hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@if($user->role === 'alumni')
@push('scripts')
<script>
    function toggleProfileCompanyFields() {
        const status = document.getElementById('status_alumni').value;
        const compFields = document.getElementById('profile_company_fields');
        if (!compFields) return;
        if (status === 'Bekerja (full time / part time)' || status === 'Wiraswasta') {
            compFields.classList.remove('hidden');
        } else {
            compFields.classList.add('hidden');
        }
    }

    // Run when the DOM is fully loaded to set the correct initial state
    document.addEventListener('DOMContentLoaded', function() {
        toggleProfileCompanyFields();
    });
</script>
@endpush
@endif
