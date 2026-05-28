<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BelajarKUY Admin Login — Halaman masuk khusus administrator">
    <title>Admin Login — BelajarKUY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">

        {{-- Logo & Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-600 rounded-2xl mb-4 shadow-lg shadow-red-900/50">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">BelajarKUY Admin</h1>
            <p class="text-slate-400 text-sm mt-1">Masuk ke panel administrasi</p>

            {{-- Admin Badge --}}
            <span class="inline-flex items-center gap-1 mt-3 px-3 py-1 bg-red-900/40 border border-red-700/50 rounded-full text-red-400 text-xs font-medium">
                <span class="w-1.5 h-1.5 bg-red-400 rounded-full animate-pulse"></span>
                Akses Terbatas — Admin Only
            </span>
        </div>

        {{-- Card --}}
        <div class="bg-slate-800/60 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-8 shadow-2xl">

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-900/40 border border-green-700/50 rounded-lg text-green-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error Flash --}}
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-900/40 border border-red-700/50 rounded-lg text-red-400 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form id="admin-login-form" method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Admin</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="admin@belajarkuy.test"
                        class="w-full px-4 py-2.5 bg-slate-900/70 border border-slate-600 rounded-lg text-white placeholder-slate-500
                               focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors text-sm
                               @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 bg-slate-900/70 border border-slate-600 rounded-lg text-white placeholder-slate-500
                               focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors text-sm
                               @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center mb-6">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-red-600 focus:ring-red-500">
                    <label for="remember_me" class="ml-2 text-sm text-slate-400">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    id="admin-login-submit"
                    type="submit"
                    class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 active:bg-red-800
                           text-white font-semibold rounded-lg transition-colors duration-200
                           focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-800
                           shadow-lg shadow-red-900/30"
                >
                    Masuk sebagai Admin
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center my-5">
                <div class="flex-1 h-px bg-slate-700"></div>
                <span class="mx-3 text-slate-500 text-xs">atau</span>
                <div class="flex-1 h-px bg-slate-700"></div>
            </div>

            {{-- Back to regular login --}}
            <a href="{{ route('login') }}"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 border border-slate-600 rounded-lg
                       text-slate-400 hover:text-slate-200 hover:border-slate-500 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke halaman login biasa
            </a>
        </div>

        {{-- Footer --}}
        <p class="text-center text-slate-600 text-xs mt-6">
            BelajarKUY &copy; {{ date('Y') }} — Platform E-Learning
        </p>
    </div>

</body>
</html>
