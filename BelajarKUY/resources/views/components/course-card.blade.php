@props(['course'])

@php
    $averageRating = $course->average_rating;
    $reviewCount = $course->reviews()->where('status', true)->count();
    $hasDiscount = $course->discount > 0;
@endphp

<div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 hover:shadow-xl hover:shadow-indigo-50/50 hover:-translate-y-1.5 transform transition-all duration-300 flex flex-col h-full relative">
    
    <!-- Badges Overlay -->
    <div class="absolute top-4 left-4 z-10 flex flex-col gap-1.5">
        @if($course->bestseller)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm shadow-orange-100">
                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                Bestseller
            </span>
        @endif
        @if($course->featured)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-sm shadow-indigo-100">
                Featured
            </span>
        @endif
    </div>

    <!-- Wishlist Button Overlay -->
    <form action="{{ route('wishlist.add', $course->id) }}" method="POST" class="absolute top-4 right-4 z-10">
        @csrf
        <button type="submit" class="w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm border border-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:scale-105 active:scale-95 shadow-sm hover:shadow transition-all duration-200 focus:outline-none" aria-label="Tambah ke Wishlist">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </button>
    </form>

    <!-- Course Thumbnail -->
    <a href="{{ route('course.detail', $course->slug) }}" class="block overflow-hidden bg-gray-50 aspect-video relative">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $course->title }}">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </a>

    <!-- Course Info Content -->
    <div class="p-6 flex flex-col flex-1">
        <!-- Category Badge -->
        <span class="inline-flex items-center text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg w-max mb-3 uppercase tracking-wider">
            {{ $course->category->name }}
        </span>

        <!-- Course Title -->
        <h3 class="text-base font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition-colors duration-200 mb-2 line-clamp-2 min-h-[2.75rem]">
            <a href="{{ route('course.detail', $course->slug) }}">{{ $course->title }}</a>
        </h3>

        <!-- Instructor Row -->
        <div class="flex items-center space-x-2.5 mb-4">
            <img class="h-6 w-6 rounded-full object-cover border border-indigo-100" src="{{ $course->instructor->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) . '&background=4F46E5&color=fff' }}" alt="{{ $course->instructor->name }}">
            <span class="text-xs font-medium text-gray-600 truncate">{{ $course->instructor->name }}</span>
        </div>

        <!-- Rating & Stats -->
        <div class="flex items-center space-x-1.5 mb-4">
            <div class="flex items-center text-amber-400">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @endfor
            </div>
            <span class="text-sm font-bold text-gray-800">{{ $averageRating }}</span>
            <span class="text-xs text-gray-400">({{ $reviewCount }} ulasan)</span>
        </div>

        <!-- Price & Action Button Row -->
        <div class="mt-auto border-t border-gray-50 pt-4 flex items-center justify-between">
            <div class="flex flex-col">
                @if($hasDiscount)
                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                    <span class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($course->discounted_price, 0, ',', '.') }}</span>
                @else
                    @if($course->price == 0)
                        <span class="text-lg font-extrabold text-emerald-600">Gratis</span>
                    @else
                        <span class="text-lg font-extrabold text-gray-900">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                    @endif
                @endif
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add', $course->id) }}" method="POST">
                @csrf
                <button type="submit" class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white hover:scale-105 active:scale-95 transition-all duration-200 focus:outline-none" aria-label="Tambah ke Keranjang">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
