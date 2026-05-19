@php
    $siteName = \App\Models\SiteInfo::where('key', 'site_name')->first()->value ?? 'BelajarKUY';
    $aboutUs = \App\Models\SiteInfo::where('key', 'about_us')->first()->value ?? 'Platform e-learning terkemuka untuk menguasai pemrograman, desain, dan bisnis secara praktis.';
    $address = \App\Models\SiteInfo::where('key', 'address')->first()->value ?? 'Bandung, Jawa Barat';
    $email = \App\Models\SiteInfo::where('key', 'email')->first()->value ?? 'support@belajarkuy.com';
    $phone = \App\Models\SiteInfo::where('key', 'phone')->first()->value ?? '+62 812-3456-7890';
    $copyright = \App\Models\SiteInfo::where('key', 'copyright')->first()->value ?? '© ' . date('Y') . ' BelajarKUY. Hak Cipta Dilindungi Undang-Undang.';
    
    $fb = \App\Models\SiteInfo::where('key', 'facebook')->first()->value ?? '#';
    $ig = \App\Models\SiteInfo::where('key', 'instagram')->first()->value ?? '#';
    $yt = \App\Models\SiteInfo::where('key', 'youtube')->first()->value ?? '#';

    $categories = \App\Models\Category::active()->take(4)->get();
@endphp

<footer class="bg-gray-950 text-gray-300 border-t border-gray-900 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Brand Column -->
            <div class="space-y-6">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                    <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent tracking-tight">Belajar<span class="font-extrabold text-indigo-300">KUY</span></span>
                </a>
                <p class="text-sm text-gray-400 leading-relaxed">{{ $aboutUs }}</p>
                
                <!-- Social Links -->
                <div class="flex items-center space-x-4 pt-2">
                    <a href="{{ $fb }}" class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all duration-300 border border-gray-800" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                    <a href="{{ $ig }}" class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-all duration-300 border border-gray-800" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="{{ $yt }}" class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all duration-300 border border-gray-800" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.11C19.517 3.545 12 3.545 12 3.545s-7.517 0-9.388.508a3.003 3.003 0 0 0-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 0 0 2.11 2.11c1.871.508 9.388.508 9.388.508s7.517 0 9.388-.508a3.003 3.003 0 0 0 2.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Categories -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Kategori Terpopuler</h3>
                <ul class="space-y-3.5 text-sm">
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('home') }}?category={{ $category->slug }}" class="hover:text-indigo-400 transition-colors duration-200 flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2 opacity-50"></span>
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Navigasi Cepat</h3>
                <ul class="space-y-3.5 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-400 transition-colors duration-200">Beranda</a></li>
                    <li><a href="{{ route('home') }}#courses" class="hover:text-indigo-400 transition-colors duration-200">Katalog Kursus</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-indigo-400 transition-colors duration-200">Masuk ke Akun</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-indigo-400 transition-colors duration-200">Gabung Gratis</a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Hubungi Kami</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $address }}</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:{{ $email }}" class="hover:text-indigo-400 transition-colors duration-200">{{ $email }}</a>
                    </li>
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ $phone }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Section -->
        <div class="border-t border-gray-900 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500">
            <p class="text-center sm:text-left mb-4 sm:mb-0">{{ $copyright }}</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-gray-400 transition-colors duration-200">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-gray-400 transition-colors duration-200">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>
