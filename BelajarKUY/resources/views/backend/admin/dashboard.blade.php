{{-- Admin Dashboard — Backend/Admin/DashboardController@index --}}
{{-- PIC: Quinsha Ilmi (Admin Panel) — Halaman ini akan dipercanggih di Phase P12 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🛡️ Dashboard Admin
            </h2>
            <span class="text-sm text-gray-500">Selamat datang, {{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert peran --}}
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-red-700 text-sm font-medium">
                    ⚠️ Kamu login sebagai <strong>Admin</strong>. Halaman ini sedang dalam pengembangan.
                </p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_students'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Siswa</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['total_instructors'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Instruktur</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['total_courses'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Kursus</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-rose-600">{{ $stats['total_orders'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Order Selesai</div>
                </div>
            </div>

            {{-- Placeholder content --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-2">📋 Menu Admin</h3>
                <ul class="list-disc list-inside text-gray-500 text-sm space-y-1">
                    <li>Manajemen Kursus (pending_review) — coming soon</li>
                    <li>Manajemen Pengguna — coming soon</li>
                    <li>Manajemen Kategori — coming soon</li>
                    <li>Manajemen Order — coming soon</li>
                    <li>Site Settings — coming soon</li>
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
