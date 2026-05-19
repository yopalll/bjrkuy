@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Pengaturan Situs
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Kelola informasi umum dan tampilan platform BelajarKUY.
        </p>
    </div>
</div>

<!-- FORM -->
<div class="max-w-5xl">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- SECTION: INFORMASI UMUM -->
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-cream-card border border-amber-100/50 flex items-center justify-center text-brand-text-dark/70">
                    <i data-lucide="layout" class="w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-brand-text-dark">Informasi Umum</h1>
                    <p class="text-sm text-slate-400 mt-1">Nama platform dan logo utama.</p>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- SITE NAME -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                            Nama Situs <span class="text-xs text-rose-500 font-normal">*Wajib</span>
                        </label>
                        <input type="text"
                               name="site_name"
                               value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                               placeholder="Contoh: BelajarKUY"
                               class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                        @error('site_name')
                            <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- TAGLINE -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                            Tagline Slogan
                        </label>
                        <input type="text"
                               name="tagline"
                               value="{{ old('tagline', $settings['tagline'] ?? '') }}"
                               placeholder="Contoh: Platform Belajar Terpercaya"
                               class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                        @error('tagline')
                            <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- GAMBAR UPLOAD -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                        Logo Situs Utama <span class="text-xs text-slate-400 font-normal">(Opsional, biarkan kosong jika tidak ingin mengubah)</span>
                    </label>
                    
                    <div class="w-full border-2 border-dashed border-slate-300 rounded-3xl bg-brand-bg-soft p-10 flex flex-col items-center justify-center relative hover:bg-brand-bg-soft/40 transition cursor-pointer group" onclick="document.getElementById('logo').click()">
                        
                        @if(isset($settings['logo']))
                            <img id="image-preview" src="{{ $settings['logo'] }}" alt="Preview" class="absolute inset-0 w-full h-full object-contain p-6 rounded-3xl z-10" />
                            
                            <div id="upload-placeholder" class="flex flex-col items-center z-20 transition-transform opacity-0 hover:opacity-100 bg-slate-900/40 absolute inset-0 rounded-3xl justify-center">
                                <div class="w-16 h-16 rounded-full bg-brand-cream-card shadow-sm flex items-center justify-center mb-4">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-brand-text-dark/50"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-white">Klik untuk mengganti logo</h3>
                            </div>
                        @else
                            <img id="image-preview" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-contain p-6 rounded-3xl z-10" />
                            
                            <div id="upload-placeholder" class="flex flex-col items-center z-20 group-hover:scale-105 transition-transform">
                                <div class="w-16 h-16 rounded-full bg-brand-cream-card shadow-sm flex items-center justify-center mb-4">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-brand-text-dark/50"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-brand-text-dark">Klik untuk mengunggah logo baru</h3>
                                <p class="text-xs text-slate-400 mt-1">SVG, PNG, WEBP maksimal 2MB</p>
                            </div>
                        @endif

                        <input type="file" name="logo" id="logo" class="hidden" accept="image/png, image/svg+xml, image/webp, image/jpeg" onchange="previewImage(this)">
                    </div>
                    @error('logo')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION: KONTAK & ALAMAT -->
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-brand-text-dark">Kontak & Alamat</h1>
                    <p class="text-sm text-slate-400 mt-1">Informasi yang tampil di bagian footer.</p>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-text-dark mb-3">Email Bantuan</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $settings['email'] ?? '') }}"
                               placeholder="Contoh: support@belajarkuy.com"
                               class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                    </div>

                    <!-- PHONE -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-text-dark mb-3">Nomor Telepon / WhatsApp</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $settings['phone'] ?? '') }}"
                               placeholder="Contoh: +62 812 3456 7890"
                               class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                    </div>
                </div>

                <!-- ADDRESS -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">Alamat Kantor</label>
                    <textarea name="address"
                              rows="3"
                              placeholder="Masukkan alamat lengkap kantor..."
                              class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">{{ old('address', $settings['address'] ?? '') }}</textarea>
                </div>

                <!-- FOOTER TEXT -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">Teks Copyright / Footer</label>
                    <input type="text"
                           name="footer_text"
                           value="{{ old('footer_text', $settings['footer_text'] ?? '') }}"
                           placeholder="Contoh: © 2026 BelajarKUY. All rights reserved."
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                </div>
            </div>
        </div>

        <!-- SECTION: SOSIAL MEDIA -->
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <i data-lucide="share-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-brand-text-dark">Sosial Media</h1>
                    <p class="text-sm text-slate-400 mt-1">Tautan akun jejaring sosial resmi.</p>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- FACEBOOK -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">Facebook URL</label>
                    <input type="url"
                           name="facebook"
                           value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                           placeholder="https://facebook.com/..."
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                </div>

                <!-- INSTAGRAM -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">Instagram URL</label>
                    <input type="url"
                           name="instagram"
                           value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                           placeholder="https://instagram.com/..."
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                </div>

                <!-- TWITTER -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">Twitter / X URL</label>
                    <input type="url"
                           name="twitter"
                           value="{{ old('twitter', $settings['twitter'] ?? '') }}"
                           placeholder="https://twitter.com/..."
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                </div>

                <!-- YOUTUBE -->
                <div>
                    <label class="block text-sm font-semibold text-brand-text-dark mb-3">YouTube URL</label>
                    <input type="url"
                           name="youtube"
                           value="{{ old('youtube', $settings['youtube'] ?? '') }}"
                           placeholder="https://youtube.com/..."
                           class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                </div>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end pt-4">
            <button type="submit"
                    class="bg-brand-accent-blue hover:opacity-90 text-white px-8 py-4 rounded-2xl text-sm font-semibold shadow-lg shadow-blue-100 transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Semua Pengaturan
            </button>
        </div>

    </form>
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
        
        @if(session('error'))
            const ErrorToast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            });

            ErrorToast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
    });

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('upload-placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                // For fresh upload vs replacing logic on placeholder
                if(placeholder) {
                    placeholder.classList.add('opacity-0');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection
