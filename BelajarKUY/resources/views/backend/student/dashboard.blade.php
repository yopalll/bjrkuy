{{-- Student Dashboard — Backend/Student/DashboardController@index --}}
{{-- PIC: Vascha U (Frontend) — Halaman ini akan dipercanggih di Phase P9 --}}
{{-- Note: "Student" di UI = role 'user' di DB (ADR-007, GLOSSARY.md) --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📖 Dashboard Siswa
            </h2>
            <span class="text-sm text-gray-500">Selamat datang, {{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_enrolled'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Kursus Diikuti</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['total_completed_lectures'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Materi Selesai</div>
                </div>

            </div>

            {{-- Kursus yang sedang diikuti --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">🎯 Kursus yang Diikuti</h3>
                    <a href="{{ route('courses.index') ?? '#' }}" class="text-indigo-600 hover:underline text-sm">
                        Jelajahi Kursus →
                    </a>
                </div>

                @if ($enrollments->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-8">
                        Kamu belum mengikuti kursus apapun.
                        <a href="#" class="text-indigo-600 hover:underline">Mulai belajar sekarang!</a>
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach ($enrollments as $enrollment)
                            <div class="flex items-center gap-4 p-4 rounded-lg border border-gray-100 hover:border-indigo-200 transition">
                                {{-- Thumbnail placeholder --}}
                                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-2xl">📚</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-800 truncate">
                                        {{ $enrollment->course->title ?? 'Kursus' }}
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Instruktur: {{ $enrollment->course->instructor->name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Enrolled: {{ $enrollment->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                {{-- Tombol lanjutkan belajar — akan diaktifkan di F13 --}}
                                <span class="text-xs text-gray-400 italic flex-shrink-0">Course player: coming soon</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
