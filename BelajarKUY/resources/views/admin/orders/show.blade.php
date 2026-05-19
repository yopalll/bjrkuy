@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-start justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Order Details
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Invoice and payment information for Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}.
        </p>
    </div>
    
    <a href="{{ route('admin.orders.index') }}"
       class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-5 py-3 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Orders
    </a>
</div>

<!-- INVOICE LAYOUT -->
<div class="max-w-4xl mx-auto">
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
        
        <!-- INVOICE HEADER -->
        <div class="px-8 py-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-brand-bg-soft flex items-center justify-center border border-slate-200">
                    <i data-lucide="receipt" class="w-6 h-6 text-brand-text-dark"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Invoice #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ $order->created_at->format('d M Y, H:i A') }}</p>
                </div>
            </div>

            <div>
                @if($order->status === 'completed')
                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Completed
                    </span>
                @elseif($order->status === 'pending')
                    <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4"></i> Pending
                    </span>
                @elseif($order->status === 'cancelled')
                    <span class="bg-slate-100 text-slate-600 border border-slate-200 px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Cancelled
                    </span>
                @elseif($order->status === 'refunded')
                    <span class="bg-rose-50 text-rose-600 border border-rose-100 px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refunded
                    </span>
                @endif
            </div>

        </div>

        <!-- DETAILS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-8 py-8 border-b border-slate-100">
            
            <!-- STUDENT INFO -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Billed To</h3>
                <h4 class="text-lg font-bold text-slate-800">{{ $order->user->name }}</h4>
                <p class="text-sm text-slate-600 mt-1">{{ $order->user->email }}</p>
                @if($order->user->phone)
                    <p class="text-sm text-slate-600 mt-1">{{ $order->user->phone }}</p>
                @endif
            </div>

            <!-- PAYMENT INFO -->
            <div class="md:text-right">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Payment Details</h3>
                @if($order->payment)
                    <p class="text-sm text-slate-800 font-medium"><span class="text-slate-500">Method:</span> <span class="uppercase">{{ str_replace('_', ' ', $order->payment->payment_type) }}</span></p>
                    <p class="text-sm text-slate-800 font-medium mt-1"><span class="text-slate-500">Gateway ID:</span> {{ $order->payment->midtrans_transaction_id ?? 'N/A' }}</p>
                @else
                    <p class="text-sm text-slate-400 italic">No payment details recorded.</p>
                @endif
            </div>

        </div>

        <!-- PURCHASED ITEMS -->
        <div class="px-8 py-8">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Purchased Course</h3>
            
            <div class="flex items-start gap-4 mb-8">
                @if($order->course->thumbnail)
                    <img src="{{ $order->course->thumbnail }}" alt="Thumbnail" class="w-24 h-16 rounded-xl object-cover border border-slate-200">
                @else
                    <div class="w-24 h-16 rounded-xl bg-slate-100 flex items-center justify-center">
                        <i data-lucide="book-open" class="text-slate-400"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <h4 class="text-base font-bold text-slate-800">{{ $order->course->title }}</h4>
                    <p class="text-sm text-slate-500 mt-1">Instructor: {{ $order->instructor->name }}</p>
                </div>
            </div>

            <!-- PRICING BREAKDOWN -->
            <div class="bg-brand-bg-soft rounded-2xl p-6 border border-slate-100">
                <div class="space-y-3 border-b border-slate-200 pb-4 mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500 font-medium">Original Price</span>
                        <span class="text-sm text-slate-800 font-semibold">{{ $order->original_price > 0 ? 'Rp ' . number_format($order->original_price, 0, ',', '.') : 'Free' }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500 font-medium">
                            Discount 
                            @if($order->coupon)
                                <span class="ml-2 bg-brand-bg-soft text-brand-text-dark px-2 py-0.5 rounded text-xs">{{ $order->coupon->code }}</span>
                            @endif
                        </span>
                        <span class="text-sm text-rose-500 font-semibold">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-base text-slate-800 font-bold uppercase tracking-wide">Total Paid</span>
                    <span class="text-xl text-emerald-600 font-bold">{{ $order->final_price > 0 ? 'Rp ' . number_format($order->final_price, 0, ',', '.') : 'Free' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
