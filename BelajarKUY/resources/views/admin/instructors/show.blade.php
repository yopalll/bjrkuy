@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-start justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Instructor Profile
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Detailed information and statistics for {{ $instructor->name }}.
        </p>
    </div>
    
    <a href="{{ route('admin.instructors.index') }}"
       class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-5 py-3 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Instructors
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <!-- LEFT: PROFILE SUMMARY -->
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm p-8 flex flex-col items-center text-center">
        @if($instructor->photo)
            <img src="{{ $instructor->photo }}" alt="{{ $instructor->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-blue-50 shadow-sm mb-4">
        @else
            <div class="w-24 h-24 rounded-full bg-brand-accent-blue text-white flex items-center justify-center font-bold text-2xl shadow-sm border-4 border-brand-bg-soft mb-4">
                {{ substr($instructor->name, 0, 1) }}
            </div>
        @endif
        
        <h2 class="text-xl font-bold text-brand-text-dark">{{ $instructor->name }}</h2>
        <p class="text-sm text-slate-500 mb-6">{{ $instructor->email }}</p>

        <div class="w-full pt-6 border-t border-slate-100 flex flex-col gap-4 text-left">
            @if($instructor->phone)
            <div class="flex items-center gap-3">
                <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                <span class="text-sm text-slate-600">{{ $instructor->phone }}</span>
            </div>
            @endif
            @if($instructor->website)
            <div class="flex items-center gap-3">
                <i data-lucide="globe" class="w-4 h-4 text-slate-400"></i>
                <a href="{{ $instructor->website }}" target="_blank" class="text-sm text-brand-accent-blue hover:underline line-clamp-1">{{ $instructor->website }}</a>
            </div>
            @endif
            <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <span class="text-sm text-slate-600 leading-relaxed">{{ $instructor->address ?? 'No address provided.' }}</span>
            </div>
            <div class="flex items-center gap-3">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                <span class="text-sm text-slate-600">Joined {{ $instructor->created_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- RIGHT: BIO & STATS -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- STATISTICS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-brand-accent-blue rounded-3xl p-6 text-white shadow-lg shadow-brand-accent-blue/20">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium opacity-90">Total Courses</h3>
                    <div class="w-10 h-10 rounded-xl bg-brand-cream-card/20 flex items-center justify-center backdrop-blur-sm">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-bold">{{ $instructor->courses_count }}</h1>
            </div>

            <div class="bg-gradient-to-br from-purple-600 to-purple-500 rounded-3xl p-6 text-white shadow-lg shadow-purple-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium opacity-90">Active Coupons</h3>
                    <div class="w-10 h-10 rounded-xl bg-brand-cream-card/20 flex items-center justify-center backdrop-blur-sm">
                        <i data-lucide="ticket" class="w-5 h-5"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-bold">{{ $instructor->coupons_count }}</h1>
            </div>
        </div>

        <!-- BIO -->
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Biography</h3>
            @if($instructor->bio)
                <div class="prose prose-slate max-w-none text-sm text-slate-600 leading-relaxed">
                    {!! nl2br(e($instructor->bio)) !!}
                </div>
            @else
                <p class="text-sm text-slate-400 italic">This instructor has not provided a biography yet.</p>
            @endif
        </div>

    </div>

</div>

<!-- INSTRUCTOR COURSES -->
<div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Courses by {{ $instructor->name }}</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-cream-card border-b border-slate-100">
                <tr class="text-brand-text-dark uppercase tracking-wider font-bold text-[11px]">
                    <th class="px-6 py-4 text-left">Course</th>
                    <th class="px-6 py-4 text-left">Category</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($courses as $course)
                <tr class="hover:bg-brand-bg-soft/40 transition">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            @if($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-16 h-12 rounded-xl object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-16 h-12 rounded-xl bg-brand-bg-soft flex items-center justify-center border border-slate-200">
                                    <i data-lucide="video" class="w-5 h-5 text-blue-500"></i>
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
                    <td class="px-6 py-5">
                        <span class="text-slate-600 font-medium">
                            {{ $course->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        @if($course->status === 'active')
                            <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-semibold">Active</span>
                        @elseif($course->status === 'inactive')
                            <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-full text-xs font-semibold">Inactive</span>
                        @elseif($course->status === 'pending_review')
                            <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                        @else
                            <span class="bg-brand-bg-soft text-brand-text-dark px-3 py-1 rounded-full text-xs font-semibold">Draft</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-right">
                        <a href="{{ route('admin.courses.show', $course) }}"
                           class="text-brand-accent-blue hover:underline text-xs font-semibold">
                            Review Course
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">
                        No courses created yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($courses->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-brand-bg-soft">
        {{ $courses->links() }}
    </div>
    @endif
</div>

@endsection
