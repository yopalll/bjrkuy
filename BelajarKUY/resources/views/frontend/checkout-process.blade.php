@extends('layouts.app')

@section('title', 'Memproses Pembayaran — BelajarKUY')

@section('content')
<div class="py-20 bg-gray-50/50 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 text-center">
        <!-- Interactive Loader & Card -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-8 space-y-6">
            <!-- Pulsing Loading Indicator -->
            <div class="relative w-20 h-20 mx-auto">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-50/50"></div>
                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                <div class="absolute inset-4 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>

            <!-- Loading Message -->
            <div class="space-y-2">
                <h2 class="text-xl font-black text-gray-900">Menghubungkan Pembayaran</h2>
                <p class="text-sm text-gray-500 leading-relaxed">Harap tunggu sebentar, kami sedang membuka gerbang pembayaran aman Midtrans Snap untuk Anda.</p>
            </div>

            <!-- Total Amount Info -->
            <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-50 text-center">
                <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wider block">Total Tagihan</span>
                <span class="text-2xl font-black text-indigo-600 mt-1 block">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
            </div>

            <!-- Manual Trigger Button -->
            <button id="pay-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg transition-colors flex items-center justify-center space-x-2">
                <span>Buka Pembayaran Manual</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Load Midtrans Snap JS SDK (Sandbox URL) -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const snapToken = '{{ $snapToken }}';
        const payButton = document.getElementById('pay-button');

        function triggerPayment() {
            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    console.log('Success Callback:', result);
                    window.location.href = "{{ route('payment.success') }}?order_id={{ $payment->midtrans_order_id }}&status=success";
                },
                onPending: function (result) {
                    console.log('Pending Callback:', result);
                    window.location.href = "{{ route('payment.success') }}?order_id={{ $payment->midtrans_order_id }}&status=pending";
                },
                onError: function (result) {
                    console.error('Error Callback:', result);
                    window.location.href = "{{ route('payment.failed') }}?order_id={{ $payment->midtrans_order_id }}";
                },
                onClose: function () {
                    console.log('Modal Closed by user');
                    window.location.href = "{{ route('checkout') }}";
                }
            });
        }

        // Auto trigger on load
        setTimeout(triggerPayment, 800);

        // Click trigger
        payButton.addEventListener('click', function (e) {
            e.preventDefault();
            triggerPayment();
        });
    });
</script>
@endsection
