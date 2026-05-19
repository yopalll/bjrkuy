@extends('layouts.app')

@section('title', 'Ubah Kata Sandi — BelajarKUY')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-10 space-y-2">
            <span class="text-xs font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3.5 py-1 rounded-full">
                Keamanan
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ubah Kata Sandi</h1>
            <p class="text-sm text-gray-500">Jaga keamanan akun Anda dengan memperbarui kata sandi secara berkala.</p>
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
                    <h2 class="text-xl font-bold text-gray-900 border-b border-gray-50 pb-5">Pengaturan Keamanan</h2>

                    <form action="{{ route('user.setting.update') }}" method="POST" class="space-y-6 max-w-xl">
                        @csrf

                        <!-- Current Password -->
                        <div class="space-y-1.5">
                            <label for="current_password" class="text-sm font-semibold text-gray-700">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner">
                            @error('current_password')
                                <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="space-y-1.5">
                            <label for="password" class="text-sm font-semibold text-gray-700">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner">
                            @error('password')
                                <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all duration-200 shadow-inner">
                            @error('password_confirmation')
                                <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4 border-t border-gray-50">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3.5 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
