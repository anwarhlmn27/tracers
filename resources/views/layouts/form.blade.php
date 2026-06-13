<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Form Kuesioner')</title>
    <link href="{{ asset('assets/css/inter.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom-forms.css') }}" rel="stylesheet" />
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="{{ asset('assets/js/tailwindcss-browser.js') }}"></script>
    @endif
    
    <script defer src="{{ asset('assets/js/alpine.min.js') }}"></script>
    <link href="{{ asset('assets/css/tom-select.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/js/init-tomselect.js') }}"></script>

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #FAF7F7; /* Soft maroon/gray tone */
        }
        /* TomSelect Customizations */
        .ts-control { border-radius: 0.5rem !important; border-color: #d1d5db !important; padding: 0.5rem 0.75rem !important; font-size: 0.875rem !important; background-color: #ffffff !important; }
        .ts-control.focus { border-color: #800000 !important; box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1) !important; }
        .ts-dropdown { border-radius: 0.5rem !important; border-color: #d1d5db !important; }
        .ts-dropdown .option.active { background-color: #800000 !important; color: white !important; }
    </style>
    @stack('styles')
</head>
<body class="text-gray-900 antialiased min-h-screen flex flex-col">
    <!-- Header Minimalis -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#800000] flex items-center justify-center shadow-inner">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-lg font-bold text-[#800000] hidden sm:block tracking-tight">Tracer Study</span>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-2 sm:gap-3 focus:outline-none hover:bg-gray-50 p-1.5 sm:px-3 sm:py-1.5 rounded-xl transition-all border border-transparent hover:border-gray-200">
                    @if(Auth::user()->avatar)
                        <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#800000] to-[#b30000] text-white flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-sm text-left hidden sm:block">
                        <p class="font-semibold text-gray-800 leading-none">{{ Auth::user()->name ?? 'User' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition.opacity.duration.200ms
                     class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl py-2 border border-gray-100 z-50" style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <p class="text-xs text-gray-500">Masuk sebagai</p>
                        <p class="text-sm font-bold text-gray-800 truncate" title="{{ Auth::user()->email }}">{{ Auth::user()->email ?? '' }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-600">
                            Role: {{ Auth::user()->role === 'alumni' ? 'Alumni' : ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#800000] transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Edit Profil
                    </a>
                    
                    <div class="border-t border-gray-50 my-1"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 w-full max-w-3xl mx-auto px-4 py-8 sm:py-10">
        @yield('content')
    </main>

    <footer class="py-8 text-center text-xs text-gray-400 font-medium">
        &copy; {{ date('Y') }} Tracer Study System
    </footer>

    @stack('scripts')
</body>
</html>
