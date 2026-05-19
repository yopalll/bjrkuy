<x-admin-layout>
    <x-slot name="header">
        <h2 class="admin-page-title">Manajemen Ulasan</h2>
        <p class="admin-page-subtitle">Setujui atau tolak ulasan kursus dari para siswa.</p>
    </x-slot>

    <!-- Global SweetAlert Sessions for Admin -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'rounded-3xl shadow-xl border border-gray-100 p-6'
                    }
                });
            });
        </script>
    @endif

    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="pb-4 pt-2">Siswa</th>
                        <th class="pb-4 pt-2">Kursus</th>
                        <th class="pb-4 pt-2">Peringkat</th>
                        <th class="pb-4 pt-2">Komentar</th>
                        <th class="pb-4 pt-2">Tanggal</th>
                        <th class="pb-4 pt-2">Status</th>
                        <th class="pb-4 pt-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm font-semibold text-gray-700">
                    @forelse($reviews as $review)
                        <tr>
                            <!-- User info -->
                            <td class="py-4">
                                <div class="flex items-center space-x-3">
                                    <img class="h-9 w-9 rounded-full object-cover border border-gray-100" src="{{ $review->user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=4F46E5&color=fff' }}" alt="{{ $review->user->name }}">
                                    <div>
                                        <div class="text-gray-900">{{ $review->user->name }}</div>
                                        <div class="text-xs text-gray-400 font-medium">{{ $review->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Course title -->
                            <td class="py-4">
                                <div class="max-w-[200px] truncate text-gray-900" title="{{ $review->course->title }}">
                                    {{ $review->course->title }}
                                </div>
                            </td>

                            <!-- Rating stars -->
                            <td class="py-4">
                                <div class="flex items-center text-amber-400 bg-amber-50 px-2 py-0.5 rounded-lg w-max">
                                    <span class="text-xs font-bold mr-1">{{ $review->rating }}.0</span>
                                    <svg class="w-3.5 h-3.5 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            </td>

                            <!-- Comment -->
                            <td class="py-4">
                                <div class="max-w-[250px] truncate text-gray-500 font-medium" title="{{ $review->comment }}">
                                    {{ $review->comment ?? 'Tidak ada komentar ulasan tertulis.' }}
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="py-4 text-xs font-medium text-gray-400">
                                {{ $review->created_at->format('d M Y H:i') }}
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4">
                                @if($review->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if(!$review->status)
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 transition-colors shadow-sm">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-100/50 transition-colors">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 font-semibold">
                                Belum ada ulasan yang masuk di platform BelajarKUY.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>
</x-admin-layout>
