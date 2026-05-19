@php
    $categories = \App\Models\Category::active()->take(6)->get();
    $cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->count() : 0;
    $wishlistCount = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0;
@endphp

<header x-data="{ mobileMenuOpen: false, userDropdownOpen: false, categoryDropdownOpen: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo & Nav Links Wrapper -->
            <div class="flex items-center space-x-12">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <div class="bg-indigo-600 text-white p-2.5 rounded-xl shadow-md shadow-indigo-200 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent tracking-tight">Belajar<span class="font-extrabold text-indigo-800">KUY</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors duration-200">Beranda</a>
                    
                    <!-- Category Dropdown -->
                    <div class="relative">
                        <button @click="categoryDropdownOpen = !categoryDropdownOpen" @click.away="categoryDropdownOpen = false" class="flex items-center space-x-1 text-gray-600 hover:text-indigo-600 font-medium transition-colors duration-200 focus:outline-none">
                            <span>Kategori</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': categoryDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="categoryDropdownOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-3 w-56 rounded-2xl shadow-xl bg-white border border-gray-100 ring-1 ring-black ring-opacity-5 focus:outline-none divide-y divide-gray-50 overflow-hidden" style="display: none;">
                            <div class="py-2">
                                @foreach($categories as $category)
                                    <a href="{{ route('home') }}?category={{ $category->slug }}#courses" class="group flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-150">
                                        <span>{{ $category->name }}</span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-indigo-500 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('home') }}#courses" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors duration-200">Kursus</a>
                </nav>
            </div>

            <!-- Search Bar -->
            <div class="hidden lg:flex flex-1 max-w-md mx-8">
                <form action="{{ route('home') }}" method="GET" class="w-full relative">
                    <input type="text" name="search" placeholder="Cari kursus pemrograman, bisnis, desain..." value="{{ request('search') }}" class="w-full bg-gray-50/80 border border-gray-200 rounded-full px-5 py-2.5 pl-12 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-300 shadow-inner">
                    <button type="submit" class="absolute left-4 top-3 text-gray-400 hover:text-indigo-600 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Action Buttons / User Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Wishlist -->
                <a href="{{ route('user.wishlist') }}" class="relative text-gray-600 hover:text-red-500 p-1.5 rounded-full hover:bg-red-50 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    @if($wishlistCount > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-extrabold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full border-2 border-white animate-pulse">{{ $wishlistCount }}</span>
                    @endif
                </a>

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-indigo-600 p-1.5 rounded-full hover:bg-indigo-50 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-extrabold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-indigo-600 rounded-full border-2 border-white">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="flex items-center space-x-2 focus:outline-none">
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-indigo-500 shadow-sm" src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4F46E5&color=fff' }}" alt="{{ auth()->user()->name }}">
                            <div class="text-left hidden lg:block">
                                <p class="text-sm font-semibold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 mt-0.5 leading-none">{{ auth()->user()->role }}</p>
                            </div>
                        </button>

                        <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-3 w-52 rounded-2xl shadow-xl bg-white border border-gray-100 ring-1 ring-black ring-opacity-5 focus:outline-none divide-y divide-gray-50 overflow-hidden" style="display: none;">
                            <div class="px-4 py-3 bg-gray-50/50">
                                <p class="text-xs text-gray-500">Masuk sebagai</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2.5 text-sm text-left text-red-600 hover:bg-red-50 transition-colors duration-150 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-600 transition-colors duration-200">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 rounded-full shadow-md shadow-indigo-100 hover:shadow-indigo-200 hover:-translate-y-0.5 transform transition-all duration-200">Daftar</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center space-x-4">
                <!-- Cart for Mobile -->
                <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-indigo-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[9px] font-extrabold leading-none text-white bg-indigo-600 rounded-full border border-white">{{ $cartCount }}</span>
                    @endif
                </a>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-indigo-600 p-1.5 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden bg-white border-b border-gray-100" style="display: none;">
        <div class="px-4 pt-2 pb-6 space-y-4 divide-y divide-gray-100">
            <!-- Mobile Search -->
            <div class="pt-2">
                <form action="{{ route('home') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Cari kursus..." value="{{ request('search') }}" class="w-full bg-gray-50 border border-gray-200 rounded-full px-5 py-2 pl-12 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200">
                    <button type="submit" class="absolute left-4 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Mobile Navigation Links -->
            <div class="pt-4 flex flex-col space-y-3 font-medium">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="text-gray-700 hover:text-indigo-600 transition-colors duration-150">Beranda</a>
                <a href="{{ route('home') }}#courses" @click="mobileMenuOpen = false" class="text-gray-700 hover:text-indigo-600 transition-colors duration-150">Kursus</a>
                <span class="text-xs uppercase tracking-wider text-gray-400 font-bold mt-2">Kategori Populer</span>
                <div class="grid grid-cols-2 gap-2 mt-1">
                    @foreach($categories as $category)
                        <a href="{{ route('home') }}?category={{ $category->slug }}#courses" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-150">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Mobile User Profile / Auth Actions -->
            <div class="pt-4">
                @auth
                    <div class="flex items-center space-x-3 mb-4">
                        <img class="h-10 w-10 rounded-full object-cover border-2 border-indigo-500 shadow-sm" src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4F46E5&color=fff' }}" alt="{{ auth()->user()->name }}">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate mt-0.5 leading-none">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors duration-150">
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors duration-150">
                            <span>Profil Saya</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 text-sm font-medium text-left text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex flex-col space-y-2 mt-2">
                        <a href="{{ route('login') }}" class="w-full text-center text-sm font-semibold text-gray-700 hover:text-indigo-600 border border-gray-200 py-2.5 rounded-full transition-colors duration-200">Masuk</a>
                        <a href="{{ route('register') }}" class="w-full text-center text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 py-2.5 rounded-full shadow-md shadow-indigo-100 transition-colors duration-200">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
