@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Instructors
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            View and manage platform instructors.
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
                    <th class="px-6 py-4 text-left">Instructor</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-center">Courses</th>
                    <th class="px-6 py-4 text-center">Coupons</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <!-- TABLE BODY -->
            <tbody class="divide-y divide-slate-100">
                @forelse($instructors as $instructor)
                <tr class="hover:bg-brand-bg-soft/40 transition">
                    
                    <!-- INSTRUCTOR DETAILS -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            @if($instructor->photo)
                                <img src="{{ $instructor->photo }}" alt="{{ $instructor->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-full bg-brand-accent-blue text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ substr($instructor->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h1 class="font-semibold text-slate-800">
                                    {{ $instructor->name }}
                                </h1>
                                <p class="text-xs text-slate-400 mt-1">
                                    Joined {{ $instructor->created_at->format('M Y') }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <!-- EMAIL -->
                    <td class="px-6 py-5">
                        <span class="text-slate-600 font-medium">
                            {{ $instructor->email }}
                        </span>
                    </td>

                    <!-- COURSES COUNT -->
                    <td class="px-6 py-5 text-center">
                        <span class="bg-brand-cream-card text-brand-text-dark border border-amber-100/50 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $instructor->courses_count }}
                        </span>
                    </td>

                    <!-- COUPONS COUNT -->
                    <td class="px-6 py-5 text-center">
                        <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $instructor->coupons_count }}
                        </span>
                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.instructors.show', $instructor) }}"
                               class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-2">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                            </a>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="px-6 py-14 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center mb-4">
                                <i data-lucide="users" class="w-7 h-7 text-brand-text-dark/30"></i>
                            </div>
                            <h1 class="text-lg font-semibold text-brand-text-dark">
                                No Instructors Found
                            </h1>
                            <p class="text-sm text-slate-400 mt-1">
                                There are no active instructors on the platform yet.
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
        {{ $instructors->links() }}
    </div>
</div>

@endsection
