@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">

    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Create Sub Category
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Add a new sub-category and assign it to a parent category.
        </p>
    </div>

    <a href="{{ route('admin.sub-categories.index') }}"
       class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-5 py-3 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back
    </a>

</div>

<!-- FORM -->
<div class="max-w-4xl">

    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">

        <!-- FORM HEADER -->
        <div class="px-8 py-6 border-b border-slate-100">
            <h1 class="text-lg font-semibold text-brand-text-dark">
                Sub Category Information
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Fill all required fields below.
            </p>
        </div>

        <!-- FORM BODY -->
        <form action="{{ route('admin.sub-categories.store') }}" method="POST" class="p-8">

            @csrf

            <!-- PARENT CATEGORY -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Parent Category
                </label>
                <div class="relative">
                    <select name="category_id"
                            class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all appearance-none cursor-pointer">
                        <option value="" disabled selected>Select a parent category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
                @error('category_id')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- NAME -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Sub Category Name
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Example: UI Design"
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                @error('name')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- SLUG -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Slug <span class="text-xs text-slate-400 font-normal">(Optional, generated automatically)</span>
                </label>
                <input type="text"
                       name="slug"
                       value="{{ old('slug') }}"
                       placeholder="Example: ui-design"
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">
                @error('slug')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <div class="flex items-center gap-4">
                <button type="submit"
                        class="bg-brand-accent-blue hover:opacity-90 text-white px-6 py-4 rounded-2xl text-sm font-semibold shadow-lg shadow-blue-100 transition flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Sub Category
                </button>
                <a href="{{ route('admin.sub-categories.index') }}"
                   class="text-slate-500 hover:text-slate-800 text-sm font-medium transition">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
