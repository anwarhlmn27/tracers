<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Tracer Study</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @endif
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F3F4F6] min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-6xl h-[80vh] min-h-[600px] bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex relative border-4 border-[#800000]/10">
        
        <!-- Left Side: Login Form -->
        <div class="w-full lg:w-1/2 h-full flex flex-col justify-center p-12 lg:p-16 bg-white">
            
            <!-- Form Content -->
            <div class="max-w-md w-full mx-auto">
                <h1 class="text-3xl font-bold text-[#800000] mb-2">Welcome Back!</h1>
                <p class="text-sm text-gray-500 mb-8 font-medium">Please Log in to your account.</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    @if($errors->any())
                        <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-medium text-gray-400 ml-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F9FAFB] text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#800000] focus:border-[#800000] transition-colors" placeholder="admin@gmail.com">
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-medium text-gray-400 ml-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-[#F9FAFB] text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#800000] focus:border-[#800000] transition-colors tracking-widest" placeholder="•••••••">
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-[#800000] focus:ring-[#800000] border-gray-300 rounded cursor-pointer">
                            <label for="remember" class="ml-2 block text-sm text-gray-700 font-semibold cursor-pointer">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="text-sm font-semibold text-[#800000] hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="flex-1 py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-[#800000]/20 text-sm font-bold text-white bg-[#800000] hover:bg-[#5e0000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800000] transition-colors">
                            Login
                        </button>
                        <button type="button" class="flex-1 py-3 px-4 border-2 border-gray-200 rounded-xl text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors">
                            Create account
                        </button>
                    </div>
                </form>
                
                <!-- Footer Text -->
                <p class="text-[10px] text-gray-400 font-medium max-w-xs leading-relaxed mt-12">
                    By sign up you agree to our term and that you have read our data policy
                </p>
            </div>
        </div>

        <!-- Right Side: Image -->
        <div class="hidden lg:flex w-1/2 h-full relative bg-[#F9FAFB] items-center justify-center p-12 border-l border-gray-100">
            <!-- Menampilkan gambar referensi freepik dari asset local -->
            <img src="{{ asset('img/login-security.png') }}" 
                 onerror="this.src='https://img.freepik.com/free-vector/cyber-security-concept_23-2148532223.jpg'" 
                 alt="Security Concept" 
                 class="w-full max-w-md h-auto object-contain drop-shadow-xl hover:scale-105 transition-transform duration-500">
            
            <!-- Bottom right pagination indicator -->
            <div class="absolute bottom-8 right-8 flex gap-2">
                <div class="w-8 h-8 rounded bg-gray-200 flex items-center justify-center cursor-pointer text-gray-600 hover:bg-gray-300 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </div>
                <div class="w-8 h-8 rounded bg-white shadow-md flex items-center justify-center cursor-pointer text-[#800000] font-bold text-sm">
                    1
                </div>
            </div>
        </div>
        
    </div>

</body>
</html>
