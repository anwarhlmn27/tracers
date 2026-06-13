<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tracer Study Dashboard')</title>
    <link href="{{ asset('assets/css/inter.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom-forms.css') }}" rel="stylesheet" />
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="{{ asset('assets/js/tailwindcss-browser.js') }}"></script>
    @endif
    
    <!-- AlpineJS for dropdowns -->
    <script defer src="{{ asset('assets/js/alpine.min.js') }}"></script>
    <!-- TomSelect -->
    <link href="{{ asset('assets/css/tom-select.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/js/init-tomselect.js') }}"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* TomSelect Customizations */
        .ts-control { border-radius: 0.5rem !important; border-color: #d1d5db !important; padding: 0.5rem 0.75rem !important; font-size: 0.875rem !important; background-color: #ffffff !important; }
        .ts-control.focus { border-color: #800000 !important; box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1) !important; }
        .ts-dropdown { border-radius: 0.5rem !important; border-color: #d1d5db !important; }
        .ts-dropdown .option.active { background-color: #800000 !important; color: white !important; }
        .sidebar-link {
            position: relative;
            overflow: hidden;
        }
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #E6A442;
            border-radius: 0 4px 4px 0;
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }
        .sidebar-link.active::before,
        .sidebar-link:hover::before {
            transform: scaleY(1);
        }
        .role-badge-admin { background: #7c3aed; }
        .role-badge-alumni { background: #059669; }
        .role-badge-dosen { background: #d97706; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
    <!-- Preloader -->
    <div id="page-preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/60 backdrop-blur-md transition-all duration-500">
        <div class="flex flex-col items-center justify-center gap-4">
            <div class="relative flex items-center justify-center">
                <div class="w-16 h-16 border-4 border-red-200 border-t-[#800000] rounded-full animate-spin"></div>
                <div class="absolute w-6 h-6 bg-[#800000] rounded-full animate-pulse opacity-80"></div>
            </div>
            <span class="text-[#800000] font-semibold tracking-widest animate-pulse text-sm">LOADING...</span>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('page-preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.backdropFilter = 'blur(0px)';
                setTimeout(() => preloader.style.display = 'none', 500);
            }
        });
        // Fallback in case load event was missed
        if (document.readyState === 'complete') {
            window.dispatchEvent(new Event('load'));
        }
    </script>
    <div class="min-h-screen flex relative overflow-hidden">
        
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden"
             @click="sidebarOpen = false"
             style="display: none;"></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#800000] text-white flex flex-col transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-auto shadow-2xl"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="h-16 flex items-center justify-between px-6 border-b border-white/10">
                <div class="flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="4" width="24" height="4" fill="white" rx="2"/>
                        <rect y="10" width="18" height="4" fill="#E6A442" rx="2"/>
                        <rect y="16" width="12" height="4" fill="#F3F4F6" rx="2"/>
                    </svg>
                    <span class="text-xl font-bold tracking-wide">Tracer Study</span>
                </div>
                <!-- Close button for mobile -->
                <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- User info in sidebar -->
            <div class="px-5 py-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border border-white/20">
                    @else
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mt-0.5
                            @if(Auth::user()->role === 'admin') role-badge-admin
                            @elseif(Auth::user()->role === 'alumni') role-badge-alumni
                            @elseif(Auth::user()->role === 'atasan') bg-indigo-100 text-indigo-700
                            @else role-badge-dosen
                            @endif
                        ">
                            {{ Auth::user()->role === 'alumni' ? 'Student' : ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
                <p class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-white/40">Menu Utama</p>

                {{-- Dashboard: admin, dosen --}}
                @if(in_array(Auth::user()->role, ['admin', 'dosen']))
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                @endif

                {{-- Form: alumni (student), atasan --}}
                @if(in_array(Auth::user()->role, ['alumni', 'atasan']))
                <a href="{{ route('form.create') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('form.create') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('form.create') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Form
                </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin', 'dosen']))
                <p class="px-3 py-2 mt-4 text-[10px] font-bold uppercase tracking-widest text-white/40">Data & Laporan</p>

                {{-- Alumni Data: admin, dosen --}}
                <a href="{{ route('alumni.index') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('alumni.*') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('alumni.*') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Alumni Data
                </a>

                {{-- Master Prodi: admin, dosen --}}
                <a href="{{ route('prodi.index') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('prodi.*') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('prodi.*') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Master Prodi
                </a>

                {{-- Questionnaires: admin, dosen --}}
                <a href="{{ route('questionnaires.index') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('questionnaires.*') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('questionnaires.*') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Questionnaires
                </a>

                {{-- Reports & Analytics: admin, dosen --}}
                <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('reports.*') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Reports & Analytics
                </a>

                {{-- Email Blast: admin, dosen --}}
                <a href="{{ route('email.index') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('email.*') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('email.*') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Email Blast
                </a>

                <p class="px-3 py-2 mt-4 text-[10px] font-bold uppercase tracking-widest text-white/40">Pengaturan</p>

                {{-- Master Form: admin only --}}
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('master-form.index') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('master-form.*') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('master-form.*') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Master Form
                </a>
                @endif

                {{-- Settings: admin, dosen --}}
                <a href="{{ route('settings') }}" class="sidebar-link flex items-center px-3 py-2.5 {{ request()->routeIs('settings') ? 'active bg-white/15 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }} rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('settings') ? 'text-[#E6A442]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
                @endif
            </nav>

            <!-- Sidebar footer -->
            <div class="px-5 py-4 border-t border-white/10">
                <p class="text-[10px] text-white/30 text-center">© {{ date('Y') }} Tracer Study System</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-8 z-10 shrink-0 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <!-- Hamburger menu -->
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg font-semibold text-[#800000] truncate">@yield('header', 'Overview')</h2>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Notifications -->
                    <button class="text-gray-400 hover:text-[#800000] relative transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-3 focus:outline-none hover:bg-gray-50 p-1.5 rounded-lg transition-colors">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-9 h-9 rounded-full bg-[#800000] text-white flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="text-sm text-left hidden sm:block">
                                <p class="font-medium text-gray-700 leading-none">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs mt-1">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                        @if(Auth::user()->role === 'admin') bg-purple-100 text-purple-700
                                        @elseif(Auth::user()->role === 'alumni') bg-emerald-100 text-emerald-700
                                        @else bg-amber-100 text-amber-700
                                        @endif
                                    ">
                                        {{ Auth::user()->role === 'alumni' ? 'Student' : ucfirst(Auth::user()->role) }}
                                    </span>
                                </p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 border border-gray-100 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#800000]">Profile Settings</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Log Out
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Page Content -->
            <div class="p-4 sm:p-8 flex-1 overflow-auto bg-[#F3F4F6]">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
