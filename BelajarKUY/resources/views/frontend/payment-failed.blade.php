@extends('layouts.app')

@section('title', 'Pembayaran Gagal — BelajarKUY')

@section('content')
<div class="py-20 bg-gray-50/50 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-xl w-full mx-auto px-4">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-8 text-center space-y-8 relative overflow-hidden">
            <!-- Decorative colored border-top -->
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-rose-500 to-red-600"></div>

            <!-- Error State Icon -->
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <!-- Header -->
            <div class="space-y-2">
                <h2 class="text-2xl font-black text-gray-900">Pembayaran Gagal</h2>
                <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed">Mohon maaf, transaksi pembayaran Anda tidak berhasil atau dibatalkan. Silakan periksa limit saldo atau koneksi internet Anda dan coba lagi.</p>
            </div>

            <!-- Details Block -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 text-left divide-y divide-gray-200/60 text-sm font-medium">
                @if($payment)
                    <div class="flex justify-between py-2.5">
                        <span class="text-gray-400">Order ID</span>
                        <span class="text-gray-900 font-bold font-mono">{{ $payment->midtrans_order_id }}</span>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <span class="text-gray-400">Total Nominal</span>
                        <span class="text-rose-600 font-extrabold">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between py-2.5">
                    <span class="text-gray-400">Status</span>
                    <span class="text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider">FAILED / CANCELLED</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('checkout') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-indigo-50/50 transition-colors flex items-center justify-center space-x-2 flex-grow sm:flex-grow-0">
                    <span>Coba Ulang Pembayaran</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"></path>
                    </svg>
                </a>
                <a href="{{ route('home') }}" class="bg-gray-100 hover:bg-gray-200/80 text-gray-800 font-extrabold py-3.5 px-6 rounded-2xl transition-colors flex items-center justify-center space-x-2">
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
