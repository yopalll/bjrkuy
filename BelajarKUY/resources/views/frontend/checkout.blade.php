@extends('layouts.app')

@section('title', 'Halaman Pembayaran — BelajarKUY')

@section('content')
<div class="py-12 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Halaman Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">Selesaikan pembayaran untuk mulai belajar sekarang.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Side: Order Items & Coupon -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Course Items List Card -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50">
                        <h2 class="font-bold text-gray-900 text-lg">Item Pembelian</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($cartItems as $item)
                            <div class="p-6 flex items-start space-x-4">
                                <div class="w-24 sm:w-32 aspect-video rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex-shrink-0">
                                    <img class="w-full h-full object-cover" src="{{ $item->course->thumbnail ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $item->course->title }}">
                                </div>
                                <div class="flex-grow min-w-0">
                                    <span class="inline-flex items-center text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md uppercase tracking-wider mb-1">
                                        {{ $item->course->category->name }}
                                    </span>
                                    <h3 class="font-bold text-gray-900 text-sm sm:text-base leading-snug truncate hover:text-indigo-600">
                                        <a href="{{ route('course.detail', $item->course->slug) }}">{{ $item->course->title }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Oleh {{ $item->course->instructor->name }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    @if($item->course->discount > 0)
                                        <span class="text-xs text-gray-400 line-through block">Rp {{ number_format($item->course->price, 0, ',', '.') }}</span>
                                        <span class="text-base font-extrabold text-indigo-600">Rp {{ number_format($item->course->discounted_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-base font-extrabold text-gray-950">Rp {{ number_format($item->course->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Coupon Section Card -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Gunakan Kupon Belajar</h3>
                    
                    @if($coupon)
                        <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 rounded-2xl mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-emerald-500 text-white rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-emerald-800">Kupon Terpasang: "{{ $coupon->code }}"</p>
                                    <p class="text-xs text-emerald-600">Hemat {{ $coupon->discount_percent }}% untuk kursus yang memenuhi syarat!</p>
                                </div>
                            </div>
                            <a href="{{ route('checkout') }}" class="text-xs font-bold text-red-600 hover:text-red-500 bg-red-50 hover:bg-red-100/50 px-3 py-1.5 rounded-lg transition-colors">
                                Hapus
                            </a>
                        </div>
                    @else
                        <form action="{{ route('checkout') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-grow">
                                <input type="text" name="coupon_code" placeholder="Masukkan kode kupon diskon..." value="{{ request('coupon_code') }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all shadow-inner">
                            </div>
                            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white font-bold text-sm px-6 py-3 rounded-2xl shadow transition-colors shrink-0">
                                Terapkan Kupon
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Right Side: Order Summary & Pay CTA -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-6 sticky top-24">
                    <h3 class="font-bold text-gray-950 text-lg border-b border-gray-50 pb-4">Ringkasan Pembayaran</h3>

                    <div class="space-y-3.5 text-sm font-medium">
                        <div class="flex justify-between text-gray-500">
                            <span>Total Harga Kursus</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($discountAmount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Potongan Kupon</span>
                                <span>-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-500">
                            <span>Biaya Transaksi</span>
                            <span class="text-emerald-600">Gratis</span>
                        </div>
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-end">
                            <span class="font-bold text-gray-950">Total Pembayaran</span>
                            <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Checkout Form to Process -->
                    <form action="{{ route('checkout.process') }}" method="POST" class="pt-4">
                        @csrf
                        @if($coupon)
                            <input type="hidden" name="coupon_code" value="{{ $coupon->code }}">
                        @endif
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-extrabold text-base py-4 rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150 flex items-center justify-center space-x-2">
                            <span>Lanjutkan ke Pembayaran</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>

                    <div class="flex items-center justify-center space-x-2 text-xs text-gray-400 font-semibold pt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Pembayaran terenkripsi dan 100% aman</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
