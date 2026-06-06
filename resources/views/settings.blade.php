@extends('layouts.app')

@section('title', 'Settings - Tracer Study')
@section('header', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Profile Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Profil Pengguna</h3>
            <p class="text-sm text-gray-500 mt-0.5">Informasi akun dan pengaturan profil</p>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#800000] to-[#b30000] text-white flex items-center justify-center text-2xl font-bold shadow-lg shadow-red-900/20">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">{{ Auth::user()->name ?? 'User' }}</h4>
                    <p class="text-sm text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider mt-1
                        @if(Auth::user()->role === 'admin') bg-purple-100 text-purple-700
                        @elseif(Auth::user()->role === 'alumni') bg-emerald-100 text-emerald-700
                        @else bg-amber-100 text-amber-700
                        @endif
                    ">
                        {{ Auth::user()->role === 'alumni' ? 'Student' : ucfirst(Auth::user()->role) }}
                    </span>
                </div>
            </div>
            <div class="mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#800000] to-[#a00000] px-5 py-2.5 text-white font-semibold shadow-sm transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Informasi Sistem</h3>
            <p class="text-sm text-gray-500 mt-0.5">Detail sistem Tracer Study</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Versi Sistem</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">Tracer Study v1.0</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Framework</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">Laravel {{ app()->version() }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">PHP Version</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ phpversion() }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Terakhir Login</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ now()->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Access Control Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Hak Akses Role</h3>
            <p class="text-sm text-gray-500 mt-0.5">Daftar menu yang tersedia berdasarkan role</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3 px-4 text-left text-gray-500 font-medium">Menu</th>
                            <th class="py-3 px-4 text-center text-gray-500 font-medium">Admin</th>
                            <th class="py-3 px-4 text-center text-gray-500 font-medium">Student</th>
                            <th class="py-3 px-4 text-center text-gray-500 font-medium">Dosen</th>
                            <th class="py-3 px-4 text-center text-gray-500 font-medium">Atasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $menus = [
                                ['Dashboard', true, false, true, false],
                                ['Master Form', true, false, false, false],
                                ['Form', false, true, false, true],
                                ['Alumni Data', true, false, true, false],
                                ['Questionnaires', true, false, true, false],
                                ['Reports & Analytics', true, false, true, false],
                                ['Settings', true, false, true, false],
                            ];
                        @endphp
                        @foreach($menus as $menu)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $menu[0] }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($menu[1])
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($menu[2])
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($menu[3])
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if(isset($menu[4]) && $menu[4])
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
