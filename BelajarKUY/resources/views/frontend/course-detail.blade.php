@extends('layouts.app')

@section('title', $course->title . ' - BelajarKUY')

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

    <!-- Banner Header Section -->
    <section class="bg-gray-950 text-white py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-center">
                <!-- Course Intro info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Breadcrumbs -->
                    <nav class="flex items-center space-x-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <a href="{{ route('home') }}" class="hover:text-indigo-400">Beranda</a>
                        <span>/</span>
                        <a href="{{ route('home') }}?category={{ $course->category->slug }}#courses" class="hover:text-indigo-400">{{ $course->category->name }}</a>
                        @if($course->subCategory)
                            <span>/</span>
                            <span class="text-indigo-400">{{ $course->subCategory->name }}</span>
                        @endif
                    </nav>

                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2.5">
                        @if($course->bestseller)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm shadow-orange-500/20">
                                Bestseller
                            </span>
                        @endif
                        @if($course->featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-sm shadow-indigo-500/20">
                                Pilihan Editor
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                        {{ $course->title }}
                    </h1>

                    <!-- Description -->
                    <p class="text-sm md:text-base text-gray-300 font-medium leading-relaxed max-w-3xl">
                        {{ $course->description }}
                    </p>

                    <!-- Rating & Enrolled count -->
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm font-semibold">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-amber-400 text-base font-bold">{{ $course->average_rating }}</span>
                            <div class="flex items-center text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($course->average_rating) ? 'fill-current' : 'text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-gray-400">({{ $course->reviews->count() }} ulasan)</span>
                        </div>
                        <span class="text-gray-600">|</span>
                        <div class="flex items-center space-x-1.5 text-gray-300">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>{{ $course->enrollments()->count() }} siswa terdaftar</span>
                        </div>
                        <span class="text-gray-600">|</span>
                        <div class="text-gray-300">
                            Dibuat oleh <span class="text-indigo-400 hover:underline cursor-pointer">{{ $course->instructor->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Course Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- LEFT COLUMN: Description, goals, syllabus, instructor, reviews -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- 1. WHAT YOU'LL LEARN -->
                @if($course->goals->count() > 0)
                    <section class="bg-gray-50 rounded-3xl p-8 border border-gray-100 space-y-6">
                        <h2 class="text-xl font-black text-gray-900">Yang Akan Anda Pelajari</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($course->goals as $goal)
                                <div class="flex items-start space-x-3 text-sm font-semibold text-gray-700">
                                    <div class="p-1 bg-indigo-50 text-indigo-600 rounded-lg mt-0.5 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span>{{ $goal->goal }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- 2. ACCORDION CURRICULUM -->
                <section class="space-y-6">
                    <div class="space-y-1">
                        <h2 class="text-xl font-black text-gray-900">Kurikulum & Materi Kursus</h2>
                        <p class="text-sm text-gray-500 font-medium">Kursus ini memiliki {{ $course->sections->count() }} bab pembelajaran.</p>
                    </div>

                    <div x-data="{ activeSection: 0 }" class="space-y-3.5">
                        @forelse($course->sections as $index => $section)
                            <div class="border border-gray-100 rounded-3xl overflow-hidden bg-white shadow-sm transition-all duration-300">
                                <button @click="activeSection = (activeSection === {{ $index }} ? null : {{ $index }})" class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-900 focus:outline-none hover:bg-gray-50/50 transition-colors duration-200">
                                    <span class="text-base flex items-center space-x-3">
                                        <span class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 text-xs flex items-center justify-center font-black">{{ $index + 1 }}</span>
                                        <span>{{ $section->title }}</span>
                                    </span>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': activeSection === {{ $index }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="activeSection === {{ $index }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="border-t border-gray-50 px-6 py-5 space-y-4 bg-gray-50/20" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                                    @forelse($section->lectures as $lectIndex => $lecture)
                                        <div class="flex items-center justify-between text-sm font-semibold text-gray-700 hover:text-indigo-600 transition-colors duration-150">
                                            <div class="flex items-center space-x-3.5">
                                                <!-- Preview Check -->
                                                @if($lectIndex === 0)
                                                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-full flex-shrink-0 cursor-pointer hover:scale-105 transition-transform duration-200" title="Pratinjau Gratis">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                                                        </svg>
                                                    </span>
                                                @else
                                                    <span class="p-1.5 bg-gray-50 text-gray-400 rounded-full flex-shrink-0" title="Materi Terkunci">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                        </svg>
                                                    </span>
                                                @endif
                                                <span>{{ $lecture->title }}</span>
                                            </div>
                                            <div class="flex items-center space-x-3 text-xs text-gray-400">
                                                @if($lectIndex === 0)
                                                    <span class="text-indigo-600 font-extrabold uppercase tracking-wider bg-indigo-50 px-2 py-0.5 rounded-lg">Pratinjau</span>
                                                @endif
                                                <span>{{ $lecture->duration }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-400 font-medium">Belum ada video materi di bab ini.</p>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 font-semibold">Kurikulum pembelajaran sedang dipersiapkan.</p>
                        @endforelse
                    </div>
                </section>

                <!-- 3. INSTRUCTOR PROFILE -->
                <section class="border-t border-gray-100 pt-12 space-y-6">
                    <h2 class="text-xl font-black text-gray-900">Profil Instruktur</h2>
                    <div class="flex flex-col sm:flex-row items-start space-y-6 sm:space-y-0 sm:space-x-8 bg-white border border-gray-100 p-8 rounded-3xl shadow-sm">
                        <img class="h-20 w-20 rounded-full object-cover border-2 border-indigo-500 shadow-sm flex-shrink-0" src="{{ $course->instructor->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) . '&background=4F46E5&color=fff' }}" alt="{{ $course->instructor->name }}">
                        <div class="space-y-4 flex-1">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 cursor-pointer transition-colors duration-200">{{ $course->instructor->name }}</h3>
                                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-0.5">Instruktur Utama</p>
                            </div>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                {{ $course->instructor->bio ?? 'Instruktur terpercaya di BelajarKUY.' }}
                            </p>
                            @if($course->instructor->website)
                                <div>
                                    <a href="{{ $course->instructor->website }}" target="_blank" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-500 bg-indigo-50 hover:bg-indigo-100/70 px-4 py-2 rounded-xl transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Kunjungi YouTube / Website
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- 4. REVIEWS & TESTIMONIALS -->
                <section class="border-t border-gray-100 pt-12 space-y-8">
                    <h2 class="text-xl font-black text-gray-900">Ulasan Pengguna</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center bg-gray-50 p-8 rounded-3xl border border-gray-100">
                        <div class="text-center space-y-2">
                            <p class="text-5xl font-black text-indigo-600">{{ $course->average_rating }}</p>
                            <div class="flex items-center justify-center text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($course->average_rating) ? 'fill-current' : 'text-gray-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Peringkat Kursus</p>
                        </div>
                        <div class="md:col-span-2 space-y-2.5">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $ratingMatches = $course->reviews->where('rating', $star)->count();
                                    $totalReviews = $course->reviews->count();
                                    $pct = $totalReviews > 0 ? ($ratingMatches / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center text-sm font-semibold text-gray-700">
                                    <span class="w-12 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $star }} Bintang</span>
                                    <div class="flex-1 h-3 bg-gray-200 rounded-full mx-4 overflow-hidden relative shadow-inner">
                                        <div class="absolute inset-y-0 left-0 bg-indigo-600 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="w-8 text-right text-xs font-bold text-gray-400">{{ round($pct) }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($showReviewForm)
                        <div class="bg-indigo-50/50 border border-indigo-100 rounded-3xl p-6 sm:p-8 space-y-6">
                            <div class="space-y-1">
                                <h3 class="text-lg font-black text-gray-900">Tulis Ulasan Anda</h3>
                                <p class="text-xs text-gray-500 font-semibold">Bagikan pengalaman belajar Anda untuk membantu siswa lainnya.</p>
                            </div>
                            <form action="{{ route('course.review.store', $course->id) }}" method="POST" class="space-y-5">
                                @csrf

                                <!-- Rating Selector -->
                                <div class="space-y-2" x-data="{ rating: 5, hoverRating: 0 }">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider block">Peringkat Bintang</label>
                                    <div class="flex items-center space-x-2">
                                        <template x-for="i in 5">
                                            <button type="button" 
                                                @click="rating = i" 
                                                @mouseenter="hoverRating = i" 
                                                @mouseleave="hoverRating = 0"
                                                class="text-gray-300 hover:scale-110 transition duration-150 focus:outline-none">
                                                <svg class="w-8 h-8" :class="(hoverRating ? hoverRating >= i : rating >= i) ? 'text-amber-400 fill-current' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </button>
                                        </template>
                                        <input type="hidden" name="rating" :value="rating" />
                                        <span class="text-xs font-bold text-gray-500 bg-white px-2.5 py-1 rounded-lg border border-gray-200" x-text="rating + ' Bintang'"></span>
                                    </div>
                                </div>

                                <!-- Review Comment -->
                                <div class="space-y-1.5">
                                    <label for="comment" class="text-xs font-bold text-gray-700 uppercase tracking-wider block">Tulis Komentar Ulasan</label>
                                    <textarea id="comment" name="comment" rows="4" required placeholder="Tuliskan pendapat jujur Anda mengenai materi, penjelasan instruktur, dan kualitas kursus ini..." class="block w-full bg-white border border-gray-200 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 font-semibold placeholder:text-gray-400"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 hover:-translate-y-0.5 active:translate-y-0 transform transition-all duration-200 shadow-md shadow-indigo-600/20">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="space-y-6">
                        @forelse($course->reviews as $review)
                            <div class="border border-gray-100 rounded-3xl p-6 space-y-4 bg-white shadow-sm hover:shadow transition-shadow duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3.5">
                                        <img class="h-10 w-10 rounded-full object-cover border border-indigo-100" src="{{ $review->user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=4F46E5&color=fff' }}" alt="{{ $review->user->name }}">
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">{{ $review->user->name }}</h4>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-amber-400 bg-amber-50 px-2.5 py-1 rounded-lg">
                                        <span class="text-xs font-bold mr-1">{{ $review->rating }}.0</span>
                                        <svg class="w-3.5 h-3.5 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 font-medium leading-relaxed">
                                    {{ $review->comment ?? 'Tidak ada komentar ulasan tertulis.' }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 font-semibold">Belum ada ulasan untuk kursus ini. Jadilah yang pertama memberikan penilaian!</p>
                        @endforelse
                    </div>
                </section>

            </div>

            <!-- RIGHT COLUMN: STICKY PURCHASE CARD -->
            <div class="space-y-8">
                <div class="lg:sticky lg:top-24 bg-white border border-gray-100 rounded-[2rem] shadow-xl shadow-indigo-50/50 overflow-hidden">
                    
                    <!-- Teaser Video Container -->
                    @if($course->video_url)
                        @php
                            $embedUrl = $course->video_url;
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|[^/]+\?v=)|youtu\.be/)([^"&?/ ]{11})%i', $course->video_url, $match)) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $match[1];
                            }
                        @endphp
                        <div class="relative bg-gray-950 aspect-video">
                            <!-- Youtube Embed -->
                            <iframe class="w-full h-full object-cover" src="{{ $embedUrl }}" title="Teaser Kursus" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="relative bg-gray-900 aspect-video flex items-center justify-center">
                            <img class="absolute inset-0 w-full h-full object-cover opacity-50" src="{{ $course->thumbnail }}" alt="{{ $course->title }}">
                            <div class="relative z-10 p-3 bg-white/95 rounded-full text-indigo-600 shadow-md">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                                </svg>
                            </div>
                        </div>
                    @endif

                    <!-- Purchase Actions Panel -->
                    <div class="p-8 space-y-6">
                        <!-- Pricing Grid -->
                        <div class="space-y-1">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Investasi Skill</span>
                            <div class="flex items-baseline space-x-3">
                                @if($course->discount > 0)
                                    <span class="text-3xl font-black text-indigo-600">Rp {{ number_format($course->discounted_price, 0, ',', '.') }}</span>
                                    <span class="text-base text-gray-400 line-through font-semibold">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">-{{ $course->discount }}%</span>
                                @else
                                    @if($course->price == 0)
                                        <span class="text-3xl font-black text-emerald-600">Gratis</span>
                                    @else
                                        <span class="text-3xl font-black text-gray-950">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-3 pt-2">
                            <!-- Enroll/Buy Now Button -->
                            <form action="{{ route('cart.add', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-center py-4 px-6 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 hover:-translate-y-0.5 active:translate-y-0 transform transition-all duration-200 shadow-lg shadow-indigo-600/30">
                                    Daftar Sekarang / Beli
                                </button>
                            </form>

                            <!-- Add to Cart -->
                            <form action="{{ route('cart.add', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-center py-3.5 px-6 rounded-full text-sm font-bold text-gray-700 hover:text-indigo-600 bg-gray-50 hover:bg-indigo-50 transition-all duration-200 border border-gray-100">
                                    Tambah ke Keranjang
                                </button>
                            </form>

                            <!-- Add to Wishlist -->
                            <form action="{{ route('wishlist.add', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3.5 px-6 rounded-full text-sm font-bold text-gray-700 hover:text-red-500 bg-white hover:bg-red-50 transition-all duration-200 border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span>Simpan ke Wishlist</span>
                                </button>
                            </form>
                        </div>

                        <!-- Perks check list -->
                        <div class="border-t border-gray-50 pt-6 space-y-3.5 text-sm font-semibold text-gray-600">
                            <span class="text-xs font-black uppercase tracking-wider text-gray-400">Kursus ini mencakup:</span>
                            <div class="flex items-center space-x-3.5">
                                <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span><strong>{{ $course->enrollments()->count() }}</strong> siswa sudah terdaftar</span>
                            </div>
                            <div class="flex items-center space-x-3.5">
                                <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Total video materi: {{ $course->duration }}</span>
                            </div>
                            <div class="flex items-center space-x-3.5">
                                <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Akses penuh selamanya</span>
                            </div>
                            <div class="flex items-center space-x-3.5">
                                <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>Belajar fleksibel di hp & laptop</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- 5. RELATED COURSES RECOMMENDATIONS -->
        @if($relatedCourses->count() > 0)
            <section class="border-t border-gray-100 pt-16 mt-16 space-y-8">
                <div class="space-y-1">
                    <span class="text-xs font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Rekomendasi</span>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Kursus Terkait</h2>
                    <p class="text-sm text-gray-500 font-medium">Jelajahi kursus menarik lainnya dalam kategori {{ $course->category->name }} yang serupa dengan materi ini.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedCourses as $relCourse)
                        <x-course-card :course="$relCourse" />
                    @endforeach
                </div>
            </section>
        @endif

    </div>
@endsection
