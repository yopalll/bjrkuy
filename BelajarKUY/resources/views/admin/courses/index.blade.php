@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Course Management
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Review and approve courses submitted by instructors.
        </p>
    </div>
</div>

<!-- TABLE -->
<div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <!-- TABLE HEADER -->
            <thead class="bg-brand-cream-card border-b border-slate-100">
                <tr class="text-brand-text-dark uppercase tracking-wider font-bold text-[11px]">
                    <th class="px-6 py-4 text-left">Course</th>
                    <th class="px-6 py-4 text-left">Instructor</th>
                    <th class="px-6 py-4 text-left">Category</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <!-- TABLE BODY -->
            <tbody class="divide-y divide-slate-100">
                @forelse($courses as $course)
                <tr class="hover:bg-brand-bg-soft/40 transition">
                    
                    <!-- COURSE DETAILS -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            @if($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-16 h-12 rounded-xl object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-16 h-12 rounded-xl bg-brand-cream-card flex items-center justify-center border border-amber-100/50">
                                    <i data-lucide="video" class="w-5 h-5 text-brand-text-dark/70"></i>
                                </div>
                            @endif
                            <div>
                                <h1 class="font-semibold text-slate-800 line-clamp-1 max-w-[200px]">
                                    {{ $course->title }}
                                </h1>
                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Free' }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <!-- INSTRUCTOR -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center text-[10px] font-bold text-brand-text-dark/70">
                                {{ substr($course->instructor->name, 0, 1) }}
                            </div>
                            <span class="font-medium text-brand-text-dark">
                                {{ $course->instructor->name }}
                            </span>
                        </div>
                    </td>

                    <!-- CATEGORY -->
                    <td class="px-6 py-5">
                        <span class="text-slate-600 font-medium">
                            {{ $course->category->name ?? 'N/A' }}
                        </span>
                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-5">
                        @if($course->status === 'active')
                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-full text-xs font-semibold">
                                Active
                            </span>
                        @elseif($course->status === 'inactive')
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-xs font-semibold">
                                Inactive
                            </span>
                        @elseif($course->status === 'pending_review')
                            <span class="bg-amber-50 text-amber-600 border border-amber-100 px-3 py-1 rounded-full text-xs font-semibold">
                                Pending Review
                            </span>
                        @else
                            <span class="bg-brand-cream-card text-brand-text-dark border border-amber-100/50 px-3 py-1 rounded-full text-xs font-semibold">
                                Draft
                            </span>
                        @endif
                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.courses.show', $course) }}"
                               class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition">
                                Review
                            </a>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="px-6 py-14 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center mb-4">
                                <i data-lucide="video-off" class="w-7 h-7 text-brand-text-dark/30"></i>
                            </div>
                            <h1 class="text-lg font-semibold text-brand-text-dark">
                                No Courses Found
                            </h1>
                            <p class="text-sm text-slate-400 mt-1">
                                There are no courses available for review.
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-amber-100/30 bg-brand-cream-card">
        {{ $courses->links() }}
    </div>
</div>

@endsection
