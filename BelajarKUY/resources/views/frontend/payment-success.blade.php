@extends('layouts.app')

@section('title', 'Status Pembayaran — BelajarKUY')

@section('content')
<div class="py-20 bg-gray-50/50 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-xl w-full mx-auto px-4">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-8 text-center space-y-8 relative overflow-hidden">
            <!-- Decorative colored border-top -->
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-emerald-400 to-teal-500"></div>

            @if(request('status') === 'pending')
                <!-- Pending State Icon -->
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Header -->
                <div class="space-y-2">
                    <h2 class="text-2xl font-black text-gray-900">Menunggu Pembayaran</h2>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed">Selesaikan pembayaran Anda menggunakan detail instruksi transaksi yang tertera pada aplikasi perbankan atau dompet digital Anda.</p>
                </div>
            @else
                <!-- Success State Icon -->
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Header -->
                <div class="space-y-2">
                    <h2 class="text-2xl font-black text-gray-900">Pembayaran Berhasil!</h2>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed">Terima kasih atas kepercayaan Anda. Kursus Anda kini telah aktif dan dapat langsung diakses dari dasbor belajar Anda.</p>
                </div>
            @endif

            <!-- Details Block -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 text-left divide-y divide-gray-200/60 text-sm font-medium">
                @if($payment)
                    <div class="flex justify-between py-2.5">
                        <span class="text-gray-400">Order ID</span>
                        <span class="text-gray-900 font-bold font-mono">{{ $payment->midtrans_order_id }}</span>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <span class="text-gray-400">Metode Pembayaran</span>
                        <span class="text-gray-900 font-bold uppercase">{{ str_replace('_', ' ', $payment->payment_type ?? 'Midtrans') }}</span>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <span class="text-gray-400">Total Nominal</span>
                        <span class="text-indigo-600 font-extrabold">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between py-2.5">
                    <span class="text-gray-400">Status</span>
                    @if(request('status') === 'pending')
                        <span class="text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider">Pending</span>
                    @else
                        <span class="text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider">Settled</span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-indigo-50/50 transition-colors flex items-center justify-center space-x-2 flex-grow sm:flex-grow-0">
                    <span>Mulai Belajar Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
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
