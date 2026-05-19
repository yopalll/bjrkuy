@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-start justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Review Course
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Detailed information for course approval.
        </p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.courses.index') }}"
           class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-5 py-3 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back
        </a>
        
        <!-- STATUS ACTION BUTTONS -->
        @if($course->status !== 'active')
        <form action="{{ route('admin.courses.update-status', $course) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="active">
            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-lg shadow-emerald-100 transition flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Approve Course
            </button>
        </form>
        @endif

        @if($course->status !== 'inactive')
        <form action="{{ route('admin.courses.update-status', $course) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="inactive">
            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-lg shadow-rose-100 transition flex items-center gap-2">
                <i data-lucide="x-circle" class="w-4 h-4"></i>
                Reject / Deactivate
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- LEFT CONTENT: COURSE DETAILS -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- MAIN INFO CARD -->
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm p-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-brand-text-dark mb-2">{{ $course->title }}</h2>
                    <div class="flex items-center gap-4 text-sm text-slate-500">
                        <span class="flex items-center gap-1">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            {{ $course->instructor->name }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="folder" class="w-4 h-4"></i>
                            {{ $course->category->name ?? 'N/A' }} 
                            @if($course->subCategory)
                                &raquo; {{ $course->subCategory->name }}
                            @endif
                        </span>
                    </div>
                </div>
                
                <!-- STATUS BADGE -->
                <div>
                    @if($course->status === 'active')
                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Active
                        </span>
                    @elseif($course->status === 'inactive')
                        <span class="bg-rose-50 text-rose-600 border border-rose-100 px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-rose-500"></div> Inactive
                        </span>
                    @elseif($course->status === 'pending_review')
                        <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div> Pending Review
                        </span>
                    @else
                        <span class="bg-slate-100 text-slate-600 border border-slate-200 px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-slate-500"></div> Draft
                        </span>
                    @endif
                </div>
            </div>

            @if($course->thumbnail)
                <img src="{{ $course->thumbnail }}" alt="Thumbnail" class="w-full h-80 object-cover rounded-2xl mb-6">
            @else
                <div class="w-full h-80 bg-brand-bg-soft rounded-2xl mb-6 flex items-center justify-center">
                    <i data-lucide="image" class="w-12 h-12 text-slate-300"></i>
                </div>
            @endif

            <h3 class="text-lg font-bold text-slate-800 mb-3">Description</h3>
            <div class="prose prose-slate max-w-none text-sm text-slate-600 leading-relaxed">
                {!! nl2br(e($course->description)) !!}
            </div>
        </div>

    </div>

    <!-- RIGHT CONTENT: META DATA -->
    <div class="space-y-6">
        
        <!-- PRICING & SETTINGS -->
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Course Settings</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Price</span>
                    <span class="text-base font-bold text-slate-800">{{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Free' }}</span>
                </div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Discount</span>
                    <span class="text-sm font-semibold text-rose-500">{{ $course->discount }}%</span>
                </div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Duration</span>
                    <span class="text-sm font-medium text-slate-800">{{ $course->duration ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Featured</span>
                    <span class="text-sm font-medium {{ $course->featured ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $course->featured ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Bestseller</span>
                    <span class="text-sm font-medium {{ $course->bestseller ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $course->bestseller ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
