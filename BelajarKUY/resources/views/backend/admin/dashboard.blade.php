<x-admin-layout>
    <x-slot name="header">
        <h2 class="admin-page-title">Dashboard Admin</h2>
        <p class="admin-page-subtitle">Selamat datang kembali di panel administrasi BelajarKUY.</p>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="admin-card flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kursus</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ \App\Models\Course::count() }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="admin-card flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Siswa</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ \App\Models\User::where('role', 'user')->count() }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="admin-card flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ \App\Models\Order::count() }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="admin-card flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Ulasan</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">{{ \App\Models\Review::count() }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                <i data-lucide="star" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3 class="text-base font-bold text-gray-900 mb-4">Informasi Sistem</h3>
        <p class="text-sm text-gray-500 leading-relaxed font-medium">
            Gunakan menu di bilah sisi sebelah kiri untuk mengelola berbagai data platform, seperti kategori, kursus, ulasan, pesanan, dan pengaturan website.
        </p>
    </div>
</x-admin-layout>
