@extends('layouts.app')

@section('title', 'Dashboard Siswa — BelajarKUY')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Banner with Glassmorphism / Vibrant Gradient -->
        <div class="relative rounded-[2.5rem] overflow-hidden bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 p-8 sm:p-12 shadow-xl shadow-indigo-100 mb-10">
            <!-- Decorative shapes -->
            <div class="absolute right-0 top-0 -mt-12 -mr-12 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute left-1/3 bottom-0 -mb-16 w-48 h-48 bg-purple-500/20 rounded-full blur-xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-3 text-white max-w-xl">
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md">
                        ✨ Selamat Datang Kembali
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                        Halo, {{ $user->name }}!
                    </h1>
                    <p class="text-indigo-100 text-sm sm:text-base font-medium leading-relaxed">
                        Senang melihat Anda kembali. Lanjutkan perjalanan belajar Anda hari ini dan kuasai keahlian baru untuk masa depan cemerlang Anda!
                    </p>
                </div>
                <!-- Learning Progress Highlight Ring -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl flex items-center space-x-4 max-w-sm">
                    <div class="relative w-16 h-16 flex-shrink-0 flex items-center justify-center">
                        <!-- Progress circle SVG -->
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-white/20" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-white transition-all duration-1000 ease-out" stroke-dasharray="{{ $overallProgress }}, 100" stroke-linecap="round" stroke-width="3.5" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-sm font-black text-white">{{ $overallProgress }}%</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white leading-tight">Progres Belajar Total</h4>
                        <p class="text-xs text-indigo-100 mt-1">Akumulasi dari seluruh kursus yang Anda ikuti</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 lg:sticky lg:top-28">
                @include('backend.student.layouts.sidebar')
            </div>

            <!-- Dashboard Content Area -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Quick Stats Row -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Stat 1: Enrolled -->
                    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center space-x-5 group">
                        <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-500 block leading-tight">Kursus Saya</span>
                            <span class="text-3xl font-extrabold text-gray-900 mt-1 block">{{ $enrollmentsCount }}</span>
                        </div>
                    </div>

                    <!-- Stat 2: Wishlist -->
                    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center space-x-5 group">
                        <div class="p-4 bg-red-50 text-red-500 rounded-2xl group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-500 block leading-tight">Wishlist</span>
                            <span class="text-3xl font-extrabold text-gray-900 mt-1 block">{{ $wishlistCount }}</span>
                        </div>
                    </div>

                    <!-- Stat 3: Reviews -->
                    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center space-x-5 group">
                        <div class="p-4 bg-amber-50 text-amber-500 rounded-2xl group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.87a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.87a1 1 0 00-1.175 0l-3.97 2.87c-.784.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.87c-.784-.57-.373-1.81.588-1.81h4.906a1 1 0 00.95-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-500 block leading-tight">Ulasan Anda</span>
                            <span class="text-3xl font-extrabold text-gray-900 mt-1 block">{{ $reviewsCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Active / Recent Enrolled Courses -->
                <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-50 pb-5">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Kursus Yang Sedang Dipelajari</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Kursus terdaftar terakhir Anda beserta progresnya</p>
                        </div>
                        <a href="{{ route('user.my-courses') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                            Lihat Semua →
                        </a>
                    </div>

                    @if(count($enrolledCoursesData) > 0)
                        <div class="divide-y divide-gray-50">
                            @foreach($enrolledCoursesData as $data)
                                <div class="py-5 first:pt-0 last:pb-0 flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-20 h-14 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100 shadow-sm relative">
                                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $data['course']->thumbnail }}" alt="{{ $data['course']->title }}">
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="font-bold text-gray-900 text-sm sm:text-base leading-snug group-hover:text-indigo-600 transition-colors duration-200 line-clamp-1">
                                                {{ $data['course']->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 font-medium">Instruktur: <span class="font-semibold text-gray-700">{{ $data['course']->instructor->name }}</span></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress & Action -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 md:w-1/2 justify-end">
                                        <!-- Progress Bar -->
                                        <div class="flex-grow max-w-xs space-y-2">
                                            <div class="flex justify-between text-xs font-semibold text-gray-600">
                                                <span>{{ $data['progress'] }}% Selesai</span>
                                                <span>{{ $data['completed_count'] }}/{{ $data['lectures_count'] }} Materi</span>
                                            </div>
                                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full transition-all duration-500 ease-out" style="width: {{ $data['progress'] }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Learn Button -->
                                        <a href="{{ route('course.detail', $data['course']->slug) }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-sm transition-all duration-200">
                                            Lanjutkan Belajar
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Enrolled Courses Empty State -->
                        <div class="text-center py-10 space-y-4 max-w-sm mx-auto">
                            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h4 class="font-bold text-gray-900">Belum Terdaftar di Kursus Apa Pun</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Anda belum membeli kelas. Jelajahi katalog kelas premium kami dan temukan materi belajar terbaik Anda!</p>
                            </div>
                            <div>
                                <a href="{{ route('home') }}#courses" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md">
                                    Jelajahi Kursus
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Profile Details & Bio Section -->
                <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-50 pb-5">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Profil Saya</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Informasi kontak dan deskripsi bio singkat Anda</p>
                        </div>
                        <a href="{{ route('user.profile') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-full text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors duration-200">
                            Edit Profil
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Bio Singkat -->
                        <div class="md:col-span-2 space-y-3 bg-gray-50/50 rounded-2xl p-5 border border-gray-100">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Bio Singkat</span>
                            <div class="text-sm text-gray-600 leading-relaxed font-medium">
                                @if($user->bio)
                                    <p class="italic text-gray-700 font-normal">"{{ $user->bio }}"</p>
                                @else
                                    <p class="text-gray-400 italic">Belum ada bio singkat yang ditambahkan.</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Kontak & Info Lain -->
                        <div class="md:col-span-1 space-y-4 bg-indigo-50/30 rounded-2xl p-5 border border-indigo-50">
                            <span class="text-xs font-bold text-indigo-500 uppercase tracking-wider block">Kontak & Informasi</span>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Nomor Telepon</span>
                                    @if($user->phone)
                                        <span class="text-sm font-semibold text-gray-800 flex items-center gap-1.5 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $user->phone }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400 italic mt-0.5">Belum ditambahkan</span>
                                    @endif
                                </div>
                                
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Alamat</span>
                                    @if($user->address)
                                        <span class="text-sm font-medium text-gray-800 block mt-0.5 leading-snug">
                                            {{ $user->address }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400 italic mt-0.5">Belum ditambahkan</span>
                                    @endif
                                </div>

                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Situs Web</span>
                                    @if($user->website)
                                        <a href="{{ $user->website }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-indigo-600 hover:underline inline-flex items-center gap-1 mt-0.5">
                                            {{ preg_replace('/(^https?:\/\/)/', '', $user->website) }}
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-400 italic mt-0.5">Belum ditambahkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
