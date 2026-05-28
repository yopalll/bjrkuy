@extends('layouts.app')

@section('title', 'Ubah Profil — BelajarKUY')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-10 space-y-2">
            <span class="text-xs font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3.5 py-1 rounded-full">
                Akun Saya
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ubah Profil</h1>
            <p class="text-sm text-gray-500">Kelola informasi data diri, deskripsi bio singkat, serta foto profil Anda.</p>
        </div>

        <!-- Dashboard Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 lg:sticky lg:top-28">
                @include('backend.student.layouts.sidebar')
            </div>

            <!-- Dashboard Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm space-y-8">
                    <h2 class="text-xl font-bold text-gray-900 border-b border-gray-50 pb-5">Informasi Profil</h2>

                    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Photo Section -->
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 pb-6 border-b border-gray-50">
                            <!-- Avatar Preview -->
                            <div class="relative w-28 h-28 flex-shrink-0 mx-auto sm:mx-0">
                                <img id="photo-preview" class="w-full h-full rounded-full object-cover border-4 border-indigo-50 shadow-sm" 
                                     src="{{ $user->photo ? asset($user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4F46E5&color=fff' }}" 
                                     alt="{{ $user->name }}">
                            </div>
                            
                            <!-- File Input Wrapper -->
                            <div class="flex-grow space-y-3 text-center sm:text-left">
                                <h4 class="text-sm font-bold text-gray-900">Foto Profil</h4>
                                <p class="text-xs text-gray-500 max-w-sm">Mendukung format JPG, JPEG, PNG, GIF, atau SVG. Ukuran berkas maksimal 2MB.</p>
                                <div>
                                    <label class="relative cursor-pointer inline-flex items-center px-5 py-2.5 rounded-full text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 shadow-sm border border-indigo-100 transition-colors duration-200">
                                        <span>Pilih Foto Baru</span>
                                        <input type="file" name="photo" id="photo-input" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                    </label>
                                </div>
                                @error('photo')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="space-y-1.5">
                                <label for="name" class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner">
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email (Readonly) -->
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-gray-700">Alamat Email</label>
                                <input type="email" value="{{ $user->email }}" readonly
                                       class="w-full bg-gray-100 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm text-gray-500 cursor-not-allowed">
                                <p class="text-[10px] text-gray-400 mt-1">Email tidak dapat diubah setelah melakukan registrasi.</p>
                            </div>

                            <!-- Phone -->
                            <div class="space-y-1.5">
                                <label for="phone" class="text-sm font-semibold text-gray-700">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                                       class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner">
                                @error('phone')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div class="space-y-1.5">
                                <label for="website" class="text-sm font-semibold text-gray-700">Situs Web Pribadi</label>
                                <input type="url" name="website" id="website" value="{{ old('website', $user->website) }}" placeholder="https://example.com"
                                       class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner">
                                @error('website')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="space-y-1.5">
                            <label for="bio" class="text-sm font-semibold text-gray-700">Bio Singkat</label>
                            <textarea name="bio" id="bio" rows="4" placeholder="Ceritakan latar belakang, ketertarikan belajar, atau pekerjaan Anda..."
                                      class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner resize-none">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="space-y-1.5">
                            <label for="address" class="text-sm font-semibold text-gray-700">Alamat Tempat Tinggal</label>
                            <textarea name="address" id="address" rows="3" placeholder="Tuliskan kota atau detail alamat tinggal Anda saat ini..."
                                      class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner resize-none">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4 border-t border-gray-50">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3.5 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
