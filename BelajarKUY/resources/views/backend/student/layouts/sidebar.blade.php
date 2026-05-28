<div class="space-y-6">
    <!-- Student Quick Profile Card -->
    <div class="bg-white rounded-3xl border border-gray-100 p-6 text-center shadow-sm relative overflow-hidden group">
        <!-- Background decorative circles -->
        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="absolute bottom-0 left-0 -ml-6 -mb-6 w-16 h-16 bg-purple-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
        
        <div class="relative z-10 space-y-4">
            <!-- Profile Photo with Gradient ring -->
            <div class="relative w-24 h-24 mx-auto flex items-center justify-center">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-full animate-spin-slow opacity-75 blur-[2px]"></div>
                <img class="w-20 h-20 rounded-full object-cover border-4 border-white relative z-10" 
                     src="{{ $user->photo ? asset($user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4F46E5&color=fff' }}" 
                     alt="{{ $user->name }}">
            </div>
            
            <!-- User Info -->
            <div>
                <h3 class="font-bold text-gray-900 text-lg leading-snug truncate">{{ $user->name }}</h3>
                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $user->email }}</p>
                
                @if($user->phone)
                    <p class="text-xs text-gray-600 mt-2 flex items-center justify-center gap-1.5 font-semibold">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>{{ $user->phone }}</span>
                    </p>
                @endif

                @if($user->bio)
                    <p class="text-xs text-gray-500 mt-2.5 border-t border-gray-50 pt-2 line-clamp-2 px-1 text-center italic" title="{{ $user->bio }}">
                        "{{ $user->bio }}"
                    </p>
                @endif
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 mt-3 border border-indigo-100">
                    <svg class="w-3 h-3 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Siswa BelajarKUY
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu Card -->
    <div class="bg-white rounded-3xl border border-gray-100 p-4 shadow-sm">
        <nav class="space-y-1.5">
            <!-- Dashboard Link -->
            <a href="{{ route('student.dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- My Courses Link -->
            <a href="{{ route('student.my-courses') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('student.my-courses') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Kursus Saya</span>
            </a>

            <!-- Wishlist Link -->
            <a href="{{ route('student.wishlist') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('student.wishlist') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span>Daftar Keinginan</span>
            </a>

            <!-- Edit Profile Link -->
            <a href="{{ route('student.profile') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('student.profile') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Ubah Profil</span>
            </a>

            <!-- Settings Link -->
            <a href="{{ route('student.setting') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('student.setting') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Ubah Kata Sandi</span>
            </a>

            <div class="h-px bg-gray-100 my-2"></div>

            <!-- Logout Link -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-semibold text-red-600 hover:bg-red-50 transition-all duration-200 text-left focus:outline-none">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </nav>
    </div>
</div>

<style>
    @keyframes spin-slow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
