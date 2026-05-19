@extends('layouts.app')

@section('title', 'Kursus Saya — BelajarKUY')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-10 space-y-2">
            <span class="text-xs font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3.5 py-1 rounded-full">
                Belajar Aktif
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kursus Saya</h1>
            <p class="text-sm text-gray-500">Kelola dan pelajari semua materi kursus premium yang telah Anda beli.</p>
        </div>

        <!-- Dashboard Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 lg:sticky lg:top-28">
                @include('backend.student.layouts.sidebar')
            </div>

            <!-- Dashboard Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm space-y-6">
                    <h2 class="text-xl font-bold text-gray-900 border-b border-gray-50 pb-5">Daftar Semua Kursus Anda</h2>

                    @if(count($enrolledCoursesData) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($enrolledCoursesData as $data)
                                <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group h-full">
                                    <!-- Course Thumbnail -->
                                    <div class="aspect-[16/9] w-full overflow-hidden relative border-b border-gray-50">
                                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $data['course']->thumbnail }}" alt="{{ $data['course']->title }}">
                                        <span class="absolute top-4 left-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 text-indigo-600 backdrop-blur-sm shadow-sm">
                                            {{ $data['course']->category->name }}
                                        </span>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6 flex flex-col justify-between flex-grow space-y-4">
                                        <div class="space-y-2">
                                            <h3 class="font-bold text-gray-900 text-base leading-snug group-hover:text-indigo-600 transition-colors duration-200 line-clamp-2 min-h-[2.75rem]">
                                                {{ $data['course']->title }}
                                            </h3>
                                            <p class="text-xs text-gray-500 font-medium">Oleh: <span class="font-semibold text-gray-700">{{ $data['course']->instructor->name }}</span></p>
                                        </div>

                                        <!-- Progress -->
                                        <div class="space-y-2 border-t border-gray-50 pt-4">
                                            <div class="flex justify-between text-xs font-semibold text-gray-600">
                                                <span>{{ $data['progress'] }}% Selesai</span>
                                                <span>{{ $data['completed_count'] }}/{{ $data['lectures_count'] }} Materi</span>
                                            </div>
                                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full transition-all duration-500 ease-out" style="width: {{ $data['progress'] }}%"></div>
                                            </div>
                                        </div>

                                        <!-- Action -->
                                        <div>
                                            <a href="{{ route('course.detail', $data['course']->slug) }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-sm transition-all duration-200">
                                                Lanjutkan Belajar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Enrolled Courses Empty State -->
                        <div class="text-center py-16 space-y-6 max-w-sm mx-auto">
                            <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto shadow-inner">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-xl font-bold text-gray-900">Belum Ada Kursus</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">Anda belum terdaftar dalam kelas apa pun. Dapatkan akses ke kelas premium kami sekarang juga!</p>
                            </div>
                            <div>
                                <a href="{{ route('home') }}#courses" class="inline-flex items-center px-6 py-3 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                                    Mulai Cari Kelas
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
