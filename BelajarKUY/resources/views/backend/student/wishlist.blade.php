@extends('layouts.app')

@section('title', 'Daftar Keinginan — BelajarKUY')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-10 space-y-2">
            <span class="text-xs font-black uppercase tracking-wider text-red-500 bg-red-50 px-3.5 py-1 rounded-full">
                Favorit
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Keinginan</h1>
            <p class="text-sm text-gray-500">Kumpulan kelas incaran yang ingin Anda pelajari di masa mendatang.</p>
        </div>

        <!-- Dashboard Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 lg:sticky lg:top-28">
                @include('backend.student.layouts.sidebar')
            </div>

            <!-- Dashboard Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm space-y-6">
                    <h2 class="text-xl font-bold text-gray-900 border-b border-gray-50 pb-5">Semua Item Wishlist</h2>

                    @if(count($wishlists) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($wishlists as $wishlist)
                                @php $course = $wishlist->course; @endphp
                                @if($course)
                                    <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group h-full justify-between">
                                        
                                        <!-- Thumbnail & Info -->
                                        <div>
                                            <div class="aspect-[16/9] w-full overflow-hidden relative border-b border-gray-50">
                                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $course->thumbnail }}" alt="{{ $course->title }}">
                                                <span class="absolute top-4 left-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 text-indigo-600 backdrop-blur-sm shadow-sm">
                                                    {{ $course->category->name }}
                                                </span>
                                            </div>

                                            <div class="p-6 space-y-4">
                                                <div class="space-y-1">
                                                    <h3 class="font-bold text-gray-900 text-base leading-snug group-hover:text-indigo-600 transition-colors duration-200 line-clamp-2 min-h-[2.75rem]">
                                                        {{ $course->title }}
                                                    </h3>
                                                    <p class="text-xs text-gray-500 font-medium">Instruktur: <span class="font-semibold text-gray-700">{{ $course->instructor->name }}</span></p>
                                                </div>

                                                <!-- Price info -->
                                                <div class="flex items-baseline space-x-2">
                                                    @if($course->discount > 0)
                                                        <span class="text-lg font-extrabold text-indigo-600">Rp{{ number_format($course->discounted_price, 0, ',', '.') }}</span>
                                                        <span class="text-xs text-gray-400 line-through">Rp{{ number_format($course->price, 0, ',', '.') }}</span>
                                                        <span class="text-[10px] font-bold uppercase tracking-wider text-green-600 bg-green-50 px-2 py-0.5 rounded-md">
                                                            DISKON {{ $course->discount }}%
                                                        </span>
                                                    @else
                                                        <span class="text-lg font-extrabold text-indigo-600">Rp{{ number_format($course->price, 0, ',', '.') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions (Footer card) -->
                                        <div class="p-6 pt-0 flex items-center space-x-3">
                                            <!-- Add to Cart Form -->
                                            <form action="{{ route('cart.add', $course->id) }}" method="POST" class="flex-grow">
                                                @csrf
                                                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-sm transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    Tambah Keranjang
                                                </button>
                                            </form>

                                            <!-- Delete Wishlist Form -->
                                            <form action="{{ route('student.wishlist.remove', $wishlist->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-3 text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 rounded-2xl transition-colors duration-200 focus:outline-none" title="Hapus dari Wishlist">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <!-- Wishlist Empty State -->
                        <div class="text-center py-16 space-y-6 max-w-sm mx-auto">
                            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-xl font-bold text-gray-900">Wishlist Kosong</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">Belum ada kursus favorit Anda di sini. Tandai kursus yang Anda suka dan lihat kapan saja!</p>
                            </div>
                            <div>
                                <a href="{{ route('home') }}#courses" class="inline-flex items-center px-6 py-3 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                                    Cari Kelas Menarik
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
