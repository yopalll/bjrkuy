@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER & FILTERS -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Orders
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Monitor all course purchases and transactions.
        </p>
    </div>

    <!-- FILTER -->
    <div class="flex items-center gap-2 bg-brand-cream-card border border-slate-200 p-1.5 rounded-2xl shadow-sm">
        <a href="{{ route('admin.orders.index') }}" 
           class="px-4 py-2 text-sm font-semibold rounded-xl transition {{ !$status ? 'bg-brand-cream-card text-brand-text-dark border border-slate-200/80' : 'text-slate-500 hover:text-slate-700' }}">
            All
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 text-sm font-semibold rounded-xl transition {{ $status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'text-slate-500 hover:text-slate-700' }}">
            Completed
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 text-sm font-semibold rounded-xl transition {{ $status === 'pending' ? 'bg-amber-50 text-amber-600' : 'text-slate-500 hover:text-slate-700' }}">
            Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 text-sm font-semibold rounded-xl transition {{ $status === 'cancelled' ? 'bg-brand-cream-card text-brand-text-dark border border-slate-200/80' : 'text-slate-500 hover:text-slate-700' }}">
            Cancelled
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'refunded']) }}" 
           class="px-4 py-2 text-sm font-semibold rounded-xl transition {{ $status === 'refunded' ? 'bg-rose-50 text-rose-600' : 'text-slate-500 hover:text-slate-700' }}">
            Refunded
        </a>
    </div>
</div>

<!-- TABLE -->
<div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-cream-card border-b border-slate-100">
                <tr class="text-brand-text-dark uppercase tracking-wider font-bold text-[11px]">
                    <th class="px-6 py-4 text-left">Order ID</th>
                    <th class="px-6 py-4 text-left">Student</th>
                    <th class="px-6 py-4 text-left">Course</th>
                    <th class="px-6 py-4 text-left">Amount</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                <tr class="hover:bg-brand-bg-soft/40 transition">
                    
                    <!-- ORDER ID -->
                    <td class="px-6 py-5">
                        <span class="font-bold text-slate-800">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>

                    <!-- STUDENT -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center font-bold text-brand-text-dark/70 text-[10px]">
                                {{ substr($order->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="font-medium text-slate-800">{{ $order->user->name }}</h1>
                                <p class="text-xs text-slate-400">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </td>

                    <!-- COURSE -->
                    <td class="px-6 py-5">
                        <span class="font-medium text-brand-text-dark line-clamp-1 max-w-[200px]" title="{{ $order->course->title }}">
                            {{ $order->course->title }}
                        </span>
                    </td>

                    <!-- AMOUNT -->
                    <td class="px-6 py-5">
                        <span class="font-bold text-slate-800">
                            {{ $order->final_price > 0 ? 'Rp ' . number_format($order->final_price, 0, ',', '.') : 'Free' }}
                        </span>
                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-5 text-center">
                        @if($order->status === 'completed')
                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">Completed</span>
                        @elseif($order->status === 'pending')
                            <span class="bg-amber-50 text-amber-600 border border-amber-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">Pending</span>
                        @elseif($order->status === 'cancelled')
                            <span class="bg-brand-cream-card text-brand-text-dark/60 border border-amber-100/50 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">Cancelled</span>
                        @elseif($order->status === 'refunded')
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">Refunded</span>
                        @endif
                    </td>

                    <!-- DATE -->
                    <td class="px-6 py-5 text-right">
                        <span class="text-xs text-slate-500 font-medium">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </span>
                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="w-8 h-8 rounded-xl bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-slate-600 flex items-center justify-center transition" title="View Details">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-14 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center mb-4">
                                <i data-lucide="shopping-cart" class="w-7 h-7 text-brand-text-dark/30"></i>
                            </div>
                            <h1 class="text-lg font-semibold text-brand-text-dark">No Orders Found</h1>
                            <p class="text-sm text-slate-400 mt-1">There are no orders matching your current filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-amber-100/30 bg-brand-cream-card">
        {{ $orders->links() }}
    </div>
</div>

@endsection
