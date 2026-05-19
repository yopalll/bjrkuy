@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Review Management
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Kelola ulasan dan penilaian (rating) yang diberikan oleh siswa untuk setiap kelas.
        </p>
    </div>
</div>

<!-- TABLE -->
<div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <!-- TABLE HEADER -->
            <thead class="bg-brand-cream-card border-b border-slate-100">
                <tr class="text-brand-text-dark uppercase tracking-wider font-bold text-[11px]">
                    <th class="px-6 py-4 text-left">Siswa</th>
                    <th class="px-6 py-4 text-left">Kelas</th>
                    <th class="px-6 py-4 text-left">Rating</th>
                    <th class="px-6 py-4 text-left">Ulasan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <!-- TABLE BODY -->
            <tbody class="divide-y divide-slate-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-brand-bg-soft/40 transition">
                    
                    <!-- SISWA -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center font-bold text-brand-text-dark/70 text-[10px]">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="font-medium text-slate-800">{{ $review->user->name }}</h1>
                                <p class="text-xs text-slate-400">{{ $review->user->email }}</p>
                            </div>
                        </div>
                    </td>

                    <!-- KELAS -->
                    <td class="px-6 py-5">
                        <span class="font-medium text-brand-text-dark line-clamp-1 max-w-[200px]" title="{{ $review->course->title }}">
                            {{ $review->course->title }}
                        </span>
                    </td>

                    <!-- RATING -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @else
                                    <svg class="w-4 h-4 text-slate-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endif
                            @endfor
                        </div>
                    </td>

                    <!-- ULASAN -->
                    <td class="px-6 py-5">
                        <span class="text-slate-600 text-xs italic line-clamp-2 max-w-[250px]" title="{{ $review->comment }}">
                            "{{ $review->comment ?? 'Tanpa ulasan tertulis.' }}"
                        </span>
                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-5 text-center">
                        @if($review->status === 'approved')
                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">
                                Approved
                            </span>
                        @elseif($review->status === 'rejected')
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">
                                Rejected
                            </span>
                        @else
                            <span class="bg-amber-50 text-amber-600 border border-amber-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">
                                Pending
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($review->status !== 'approved')
                                <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition" title="Approve">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif

                            @if($review->status !== 'rejected')
                                <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition btn-reject" title="Reject">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="px-6 py-14 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center mb-4">
                                <i data-lucide="message-square" class="w-7 h-7 text-brand-text-dark/30"></i>
                            </div>
                            <h1 class="text-lg font-semibold text-brand-text-dark">
                                Tidak Ada Ulasan
                            </h1>
                            <p class="text-sm text-slate-400 mt-1">
                                Belum ada siswa yang memberikan ulasan untuk saat ini.
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-amber-100/30 bg-brand-cream-card">
        {{ $reviews->links() }}
    </div>
</div>

<!-- SWEETALERT2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Success Toast
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Reject Confirmation
        const rejectButtons = document.querySelectorAll('.btn-reject');
        rejectButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Tolak Ulasan?',
                    text: "Ulasan ini akan disembunyikan dari halaman kelas.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl',
                        cancelButton: 'rounded-xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@endsection
