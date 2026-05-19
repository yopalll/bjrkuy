@extends('layouts.app')

@section('title', 'BelajarKUY - Kuasai Skill Tech Masa Depanmu')

@section('content')
    <!-- Global SweetAlert Sessions -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    customClass: {
                        popup: 'rounded-3xl shadow-xl border border-gray-100 p-6'
                    }
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#4F46E5',
                    customClass: {
                        popup: 'rounded-3xl shadow-xl border border-gray-100 p-6',
                        confirmButton: 'rounded-full px-6 py-2.5 font-semibold text-sm'
                    }
                });
            });
        </script>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-16">
        
        <!-- 1. HERO SLIDER SECTION -->
        @if($sliders->count() > 0)
            <section x-data="{ activeSlide: 0, slidesCount: {{ $sliders->count() }} }" x-init="setInterval(() => { activeSlide = (activeSlide + 1) % slidesCount }, 8000)" class="relative rounded-[2rem] overflow-hidden shadow-2xl shadow-indigo-100/50 bg-gray-950 aspect-[16/9] md:aspect-[21/8]">
                <!-- Slide Container -->
                <div class="relative w-full h-full">
                    @foreach($sliders as $index => $slider)
                        <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-4" class="absolute inset-0 w-full h-full" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                            <!-- Image overlay -->
                            <img class="absolute inset-0 w-full h-full object-cover opacity-45" src="{{ $slider->image }}" alt="{{ $slider->title }}">
                            <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/70 to-transparent"></div>
                            
                            <!-- Content -->
                            <div class="absolute inset-0 flex flex-col justify-center px-8 sm:px-16 lg:px-24 max-w-2xl text-white space-y-4 sm:space-y-6">
                                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-none bg-gradient-to-r from-white via-indigo-100 to-indigo-200 bg-clip-text text-transparent">
                                    {{ $slider->title }}
                                </h1>
                                <p class="text-sm sm:text-base lg:text-lg text-gray-300 font-medium leading-relaxed max-w-xl">
                                    {{ $slider->description }}
                                </p>
                                @if($slider->button_text)
                                    <div>
                                        <a href="{{ $slider->button_url ?? '#' }}" class="inline-flex items-center px-6 py-3.5 sm:px-8 sm:py-4 rounded-full text-sm sm:text-base font-bold bg-indigo-600 hover:bg-indigo-500 hover:-translate-y-0.5 active:translate-y-0 transform transition-all duration-200 shadow-lg shadow-indigo-600/30">
                                            <span>{{ $slider->button_text }}</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Slide Dots Indicator -->
                <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2.5 z-10">
                    @foreach($sliders as $index => $slider)
                        <button @click="activeSlide = {{ $index }}" class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="activeSlide === {{ $index }} ? 'bg-indigo-500 w-8' : 'bg-white/40 hover:bg-white/60'"></button>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- 2. INFO BOXES SECTION -->
        @if($infoBoxes->count() > 0)
            <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($infoBoxes as $box)
                    <div class="flex items-start space-x-5 p-8 bg-white rounded-3xl border border-gray-100 hover:border-indigo-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl">
                            @if($box->icon == 'academic-cap')
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            @elseif($box->icon == 'user-group')
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @else
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $box->title }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $box->description }}</p>
                        </div>
                    </div>
                @endforeach
            </section>
        @endif

        <!-- 3. DYNAMIC SHOWCASE SECTION (SEARCH / FILTERS OR HOMEPAGE SHOWCASE) -->
        @if($isSearchingOrFiltering)
            <section id="courses" class="scroll-mt-24 space-y-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-gray-100 pb-5 gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">
                            @if(request('search'))
                                Hasil Pencarian: <span class="text-indigo-600 font-extrabold">"{{ request('search') }}"</span>
                            @elseif(request('category'))
                                Kategori: <span class="text-indigo-600 font-extrabold">"{{ \App\Models\Category::where('slug', request('category'))->first()->name ?? request('category') }}"</span>
                            @endif
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Ditemukan {{ $filteredCourses->count() }} kursus yang cocok</p>
                    </div>
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500 px-4 py-2 bg-indigo-50 rounded-full transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

                @if($filteredCourses->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($filteredCourses as $course)
                            <x-course-card :course="$course" />
                        @endforeach
                    </div>
                @else
                    <!-- Empty State Design -->
                    <div class="text-center py-16 px-4 bg-gray-50 rounded-3xl border border-dashed border-gray-200 max-w-xl mx-auto space-y-6">
                        <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold text-gray-900">Maaf, Kursus Tidak Ditemukan</h3>
                            <p class="text-sm text-gray-500 max-w-md mx-auto">Kami tidak dapat menemukan kursus apa pun yang cocok dengan pencarian Anda. Coba kata kunci lain atau jelajahi kategori terpopuler.</p>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transition-all duration-200">
                                Lihat Semua Kursus
                            </a>
                        </div>
                    </div>
                @endif
            </section>
        @else
            <!-- 4. CATEGORIES SECTION -->
            @if($categories->count() > 0)
                <section id="categories" class="scroll-mt-24 space-y-8">
                    <div class="text-center max-w-xl mx-auto space-y-2">
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Kategori Terpopuler</h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($categories as $category)
                            <a href="{{ route('home') }}?category={{ $category->slug }}#courses" class="group bg-white rounded-3xl overflow-hidden border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50/30 transform hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-44 relative">
                                <!-- Background Image Overlay -->
                                <img class="absolute inset-0 w-full h-full object-cover opacity-10 group-hover:scale-105 transition-transform duration-500" src="{{ $category->image }}" alt="{{ $category->name }}">
                                <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/80 to-white/95"></div>

                                <div class="relative p-6 flex flex-col justify-between h-full">
                                    <div class="bg-indigo-50 text-indigo-600 p-3 rounded-2xl w-max group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200 leading-tight">{{ $category->name }}</h3>
                                        <p class="text-xs text-gray-400 font-medium">{{ $category->courses_count }} Kursus</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- 5. FEATURED COURSES SECTION -->
            @if($featuredCourses->count() > 0)
                <section id="courses" class="scroll-mt-24 space-y-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-xs font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Pilihan Terbaik</span>
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Kursus Unggulan</h2>
                            <p class="text-sm text-gray-500">Kumpulan kursus terbaik yang paling direkomendasikan untuk menunjang karirmu.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @forelse($featuredCourses as $course)
                            <x-course-card :course="$course" />
                        @empty
                            <p class="text-gray-500">Tidak ada kursus unggulan saat ini.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            <!-- 6. BESTSELLER COURSES SECTION -->
            @if($bestsellerCourses->count() > 0)
                <section class="space-y-8">
                    <div class="space-y-1">
                        <span class="text-xs font-black uppercase tracking-wider text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Sangat Populer</span>
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Kursus Terlaris</h2>
                        <p class="text-sm text-gray-500">Kursus dengan tingkat pendaftaran tertinggi dan ulasan terbaik oleh ribuan siswa.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @forelse($bestsellerCourses as $course)
                            <x-course-card :course="$course" />
                        @empty
                            <p class="text-gray-500">Tidak ada kursus terlaris saat ini.</p>
                        @endforelse
                    </div>
                </section>
            @endif
        @endif



    </div>
@endsection
