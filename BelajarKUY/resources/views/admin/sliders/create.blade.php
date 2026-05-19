@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Tambah Slider
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Unggah banner baru untuk ditampilkan di halaman utama.
        </p>
    </div>

    <a href="{{ route('admin.sliders.index') }}"
       class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-5 py-3 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>
</div>

<!-- FORM -->
<div class="max-w-4xl">
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
        
        <div class="px-8 py-6 border-b border-slate-100">
            <h1 class="text-lg font-semibold text-brand-text-dark">Informasi Slider</h1>
            <p class="text-sm text-slate-400 mt-1">Isi detail banner slider di bawah ini.</p>
        </div>

        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <!-- JUDUL -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Judul <span class="text-xs text-slate-400 font-normal">(Opsional)</span>
                </label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       placeholder="Contoh: Promo BelajarKUY"
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                @error('title')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- SUB JUDUL -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Sub-judul <span class="text-xs text-slate-400 font-normal">(Opsional)</span>
                </label>
                <input type="text"
                       name="sub_title"
                       value="{{ old('sub_title') }}"
                       placeholder="Contoh: Diskon 50% untuk semua kelas"
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                @error('sub_title')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- TAUTAN LINK -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                        Tautan (Link) <span class="text-xs text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text"
                           name="link"
                           value="{{ old('link') }}"
                           placeholder="Contoh: https://belajarkuy.com/promo"
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                    @error('link')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URUTAN -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                        Urutan Penampilan <span class="text-xs text-slate-400 font-normal">(Wajib)</span>
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

            <!-- GAMBAR UPLOAD -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Banner Gambar <span class="text-xs text-slate-400 font-normal">(Wajib, disarankan 16:9 rasio)</span>
                </label>
                
                <div class="w-full border-2 border-dashed border-slate-300 rounded-3xl bg-brand-bg-soft p-10 flex flex-col items-center justify-center relative hover:bg-brand-bg-soft/80 transition cursor-pointer group" onclick="document.getElementById('image').click()">
                    
                    <img id="image-preview" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-3xl z-10" />
                    
                    <div id="upload-placeholder" class="flex flex-col items-center z-20 group-hover:scale-105 transition-transform">
                        <div class="w-16 h-16 rounded-full bg-brand-cream-card shadow-sm flex items-center justify-center mb-4">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-blue-500"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-brand-text-dark">Klik untuk mengunggah gambar</h3>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, WEBP maksimal 2MB</p>
                    </div>

                    <input type="file" name="image" id="image" class="hidden" accept="image/png, image/jpeg, image/webp" onchange="previewImage(this)">
                </div>
                @error('image')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <div class="flex items-center gap-4">
                <button type="submit"
                        class="bg-brand-accent-blue hover:opacity-90 text-white px-6 py-4 rounded-2xl text-sm font-semibold shadow-lg shadow-blue-100 transition flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Slider
                </button>
                <a href="{{ route('admin.sliders.index') }}"
                   class="text-slate-500 hover:text-slate-800 text-sm font-medium transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('upload-placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('opacity-0');
        }
    }
</script>

@endsection
