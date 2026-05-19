@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Info Box Management
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Kelola kotak informasi (fitur/keunggulan) yang tampil di halaman utama.
        </p>
    </div>

    <a href="{{ route('admin.info-boxes.create') }}"
       class="bg-brand-accent-blue hover:opacity-90 text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-lg shadow-blue-100 transition flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Tambah Info Box Baru
    </a>
</div>

<!-- TABLE -->
<div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-cream-card border-b border-slate-100">
                <tr class="text-brand-text-dark uppercase tracking-wider font-bold text-[11px]">
                    <th class="px-6 py-4 text-left">Ikon</th>
                    <th class="px-6 py-4 text-left">Judul</th>
                    <th class="px-6 py-4 text-left">Deskripsi</th>
                    <th class="px-6 py-4 text-center">Urutan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($infoBoxes as $infoBox)
                <tr class="hover:bg-brand-bg-soft/40 transition">
                    
                    <!-- IKON -->
                    <td class="px-6 py-5">
                        <div class="w-12 h-12 rounded-xl bg-brand-cream-card text-brand-text-dark/70 flex items-center justify-center border border-amber-100/50">
                            @if($infoBox->icon)
                                <i data-lucide="{{ $infoBox->icon }}" class="w-6 h-6"></i>
                            @else
                                <i data-lucide="box" class="w-6 h-6"></i>
                            @endif
                        </div>
                    </td>

                    <!-- JUDUL -->
                    <td class="px-6 py-5">
                        <h1 class="font-semibold text-slate-800">
                            {{ $infoBox->title }}
                        </h1>
                        <p class="text-[10px] text-slate-400 font-mono mt-1" title="Kode Ikon">
                            {{ $infoBox->icon ?? 'Tanpa Ikon' }}
                        </p>
                    </td>

                    <!-- DESKRIPSI -->
                    <td class="px-6 py-5">
                        <span class="text-slate-600 text-xs line-clamp-2 max-w-[250px]">
                            {{ $infoBox->description }}
                        </span>
                    </td>

                    <!-- URUTAN -->
                    <td class="px-6 py-5 text-center">
                        <span class="bg-brand-cream-card text-brand-text-dark border border-amber-100/50 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $infoBox->order_position }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.info-boxes.edit', $infoBox) }}"
                               class="w-8 h-8 rounded-xl bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-slate-600 flex items-center justify-center transition" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            
                            <form action="{{ route('admin.info-boxes.destroy', $infoBox) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition btn-delete" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="px-6 py-14 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center mb-4">
                                <i data-lucide="box" class="w-7 h-7 text-brand-text-dark/30"></i>
                            </div>
                            <h1 class="text-lg font-semibold text-brand-text-dark">
                                Tidak Ada Info Box
                            </h1>
                            <p class="text-sm text-slate-400 mt-1">
                                Silakan tambah info box baru untuk menampilkannya.
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
        {{ $infoBoxes->links() }}
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

        // Delete Confirmation
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data Info Box ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3B5973',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, hapus!',
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
