{{-- Instructor Dashboard — Backend/Instructor/DashboardController@index --}}
{{-- PIC: Albariqi Tarigan (Auth + Course) — Halaman ini akan dipercanggih di Phase P8 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🎓 Dashboard Instruktur
            </h2>
            <span class="text-sm text-gray-500">Selamat datang, {{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_courses'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Kursus Saya</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['total_enrollments'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Siswa Enrolled</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-amber-600">
                        Rp {{ number_format($stats['gross_revenue'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 mt-1">Gross Revenue</div>
                </div>

            </div>

            {{-- Daftar Kursus Saya --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">📚 Kursus Saya</h3>
                    {{-- Tombol buat kursus baru — akan diaktifkan di Phase P5 --}}
                    <span class="text-xs text-gray-400 italic">Buat Kursus: coming soon</span>
                </div>

                @if ($courses->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-8">
                        Kamu belum punya kursus. Mulai buat kursus pertamamu!
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="border-b text-gray-500 text-xs uppercase">
                                    <th class="pb-3 pr-6">Judul Kursus</th>
                                    <th class="pb-3 pr-6">Status</th>
                                    <th class="pb-3 pr-6">Siswa</th>
                                    <th class="pb-3">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($courses as $course)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 pr-6 font-medium text-gray-800">
                                            {{ $course->title }}
                                        </td>
                                        <td class="py-3 pr-6">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($course->status === 'active') bg-green-100 text-green-700
                                                @elseif($course->status === 'pending_review') bg-yellow-100 text-yellow-700
                                                @elseif($course->status === 'draft') bg-gray-100 text-gray-600
                                                @else bg-red-100 text-red-700 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $course->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-6 text-gray-600">{{ $course->enrollments_count }}</td>
                                        <td class="py-3 text-gray-600">
                                            Rp {{ number_format($course->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
