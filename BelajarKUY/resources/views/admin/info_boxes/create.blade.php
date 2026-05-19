@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Tambah Info Box
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Buat kotak informasi baru untuk fitur atau keunggulan platform.
        </p>
    </div>

    <a href="{{ route('admin.info-boxes.index') }}"
       class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-5 py-3 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>
</div>

<!-- FORM -->
<div class="max-w-4xl">
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
        
        <div class="px-8 py-6 border-b border-slate-100">
            <h1 class="text-lg font-semibold text-brand-text-dark">Informasi Box</h1>
            <p class="text-sm text-slate-400 mt-1">Isi detail di bawah ini dengan lengkap.</p>
        </div>

        <form action="{{ route('admin.info-boxes.store') }}" method="POST" class="p-8">
            @csrf

            <!-- JUDUL -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Judul <span class="text-xs text-rose-500 font-normal">*Wajib</span>
                </label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       placeholder="Contoh: Akses Seumur Hidup"
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                @error('title')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- DESKRIPSI -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Deskripsi Singkat <span class="text-xs text-rose-500 font-normal">*Wajib</span>
                </label>
                <textarea name="description"
                          rows="3"
                          placeholder="Penjelasan singkat mengenai fitur/keunggulan..."
                          class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- IKON -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                        Kode Ikon (Lucide) <span class="text-xs text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text"
                           name="icon"
                           value="{{ old('icon') }}"
                           placeholder="Contoh: award, book, clock"
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                    @error('icon')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-2">Lihat daftar ikon di <a href="https://lucide.dev/icons/" target="_blank" class="text-blue-500 hover:underline">lucide.dev</a></p>
                </div>

                <!-- URUTAN -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                        Urutan Penampilan <span class="text-xs text-rose-500 font-normal">*Wajib</span>
                    </label>
                    <input type="number"
                           name="order_position"
                           value="{{ old('order_position', 0) }}"
                           min="0"
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                    @error('order_position')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- BUTTON -->
            <div class="flex items-center gap-4">
                <button type="submit"
                        class="bg-brand-accent-blue hover:opacity-90 text-white px-6 py-4 rounded-2xl text-sm font-semibold shadow-lg shadow-blue-100 transition flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Info Box
                </button>
                <a href="{{ route('admin.info-boxes.index') }}"
                   class="text-slate-500 hover:text-slate-800 text-sm font-medium transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
