<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'BelajarKUY — Platform E-Learning Modern untuk Pelajar Indonesia')">

    <title>@yield('title', 'BelajarKUY — Platform E-Learning')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased" style="font-family: 'Inter', 'Poppins', sans-serif;">

    <!-- ===== NAVBAR ===== -->
    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-sm group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">BelajarKUY</span>
                </a>

                <!-- Nav Links (Desktop) -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Beranda</a>
                    <a href="{{ route('home') }}#courses" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Kursus</a>
                </div>

                <!-- Auth Section -->
                <div class="flex items-center space-x-3">
                    @auth
                        {{-- Logged In: show role badge + dashboard link + logout --}}
                        <div class="flex items-center space-x-3">
                            {{-- Role Badge --}}
                            @php
                                $roleLabel = match(Auth::user()->role) {
                                    'admin' => ['label' => 'Admin', 'class' => 'bg-red-100 text-red-700 border-red-200'],
                                    'instructor' => ['label' => 'Instruktur', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
                                    default => ['label' => 'Siswa', 'class' => 'bg-indigo-100 text-indigo-700 border-indigo-200'],
                                };
                                $dashboardRoute = match(Auth::user()->role) {
                                    'admin' => route('admin.dashboard'),
                                    'instructor' => route('instructor.dashboard'),
                                    default => route('student.dashboard'),
                                };
                            @endphp

                            <a href="{{ $dashboardRoute }}"
                               class="hidden sm:flex items-center space-x-2 px-3 py-1.5 rounded-lg border {{ $roleLabel['class'] }} text-xs font-semibold transition-opacity hover:opacity-80">
                                <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=fff&size=32' }}"
                                     class="w-5 h-5 rounded-full" alt="">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="opacity-60">·</span>
                                <span>{{ $roleLabel['label'] }}</span>
                            </a>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 text-xs font-semibold text-gray-600 hover:text-red-600 border border-gray-200 hover:border-red-200 rounded-lg transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Guest: Login + Register + Google --}}
                        <a href="{{ route('login') }}"
                           id="nav-login-btn"
                           class="px-4 py-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 border border-indigo-200 hover:border-indigo-400 rounded-lg transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           id="nav-register-btn"
                           class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-sm shadow-indigo-200">
                            Daftar
                        </a>
                        <a href="{{ route('auth.google') }}"
                           id="nav-google-btn"
                           title="Masuk dengan Google"
                           class="hidden sm:flex items-center justify-center w-9 h-9 border border-gray-200 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>
    <!-- ===== END NAVBAR ===== -->

    <!-- ===== MAIN CONTENT ===== -->
    <main>
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>
    <!-- ===== END MAIN CONTENT ===== -->

    <!-- ===== FOOTER ===== -->
    <footer class="bg-gray-900 text-white mt-20 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center space-x-2 mb-3">
                <div class="w-6 h-6 bg-indigo-500 rounded-md flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-lg font-bold">BelajarKUY</span>
            </div>
            <p class="text-gray-400 text-sm">Platform pembelajaran online modern untuk pelajar Indonesia.</p>
            <p class="text-gray-600 text-xs mt-4">© {{ date('Y') }} BelajarKUY. All rights reserved.</p>
        </div>
    </footer>
    <!-- ===== END FOOTER ===== -->

    @stack('scripts')
</body>
</html>