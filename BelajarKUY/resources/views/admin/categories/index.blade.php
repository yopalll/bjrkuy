<!-- FILE:
resources/views/admin/categories/index.blade.php
-->

@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">

    <div>

        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Categories
        </h1>

        <p class="text-sm text-slate-500 mt-2">
            Manage course categories and learning topics.
        </p>

    </div>

    <a href="{{ route('admin.categories.create') }}"
       class="bg-brand-accent-blue hover:opacity-90 text-white px-5 py-3 rounded-2xl text-sm font-medium shadow-lg shadow-blue-100 transition flex items-center gap-2">

        <i data-lucide="plus"
           class="w-4 h-4"></i>

        Add Category

    </a>

</div>

<!-- FILTER -->
<div class="flex flex-col lg:flex-row items-center justify-between gap-4 mb-5">

    <!-- SEARCH -->
    <div class="relative w-full lg:w-[380px]">

        <i data-lucide="search"
           class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>

        <input type="text"
               placeholder="Search categories..."
               class="w-full bg-brand-cream-card border border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">

    </div>

    <!-- FILTER ACTION -->
    <div class="flex items-center gap-3 w-full lg:w-auto">

        <select class="bg-brand-cream-card border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-600 outline-none focus:ring-4 focus:ring-brand-accent-blue/20">

            <option>
                All Status
            </option>

            <option>
                Active
            </option>

            <option>
                Inactive
            </option>

        </select>

        <button class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 rounded-2xl px-4 py-3 text-slate-600 transition">

            <i data-lucide="sliders-horizontal"
               class="w-4 h-4"></i>

        </button>

    </div>

</div>

<!-- TABLE -->
<div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <!-- TABLE HEADER -->
            <thead class="bg-brand-cream-card border-b border-slate-200/60">

            <tr class="text-brand-text-dark uppercase tracking-wider text-[11px] font-bold">
                    <th class="px-6 py-4 text-left">
                        Category
                    </th>

                    <th class="px-6 py-4 text-left">
                        Slug
                    </th>

                    <th class="px-6 py-4 text-left">
                        Status
                    </th>

                    <th class="px-6 py-4 text-right">
                        Actions
                    </th>

                </tr>

            </thead>

            <!-- TABLE BODY -->
            <tbody class="divide-y divide-slate-200/60 bg-brand-cream-card">

             @forelse($categories as $category)

              <tr class="hover:bg-brand-bg-soft/40 transition">
                    <!-- CATEGORY -->
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3">

                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-11 h-11 rounded-2xl object-cover border border-slate-200">
                            @else
                                <div class="w-11 h-11 rounded-2xl bg-brand-cream-card flex items-center justify-center border border-amber-100/50">
                                    <i data-lucide="folder" class="w-5 h-5 text-brand-text-dark/70"></i>
                                </div>
                            @endif

                            <div>

                                <h1 class="font-semibold text-slate-800">
                                    {{ $category->name }}
                                </h1>

                                <p class="text-xs text-slate-400 mt-1">
                                    Learning Category
                                </p>

                            </div>

                        </div>

                    </td>

                    <!-- SLUG -->
                    <td class="px-6 py-5">

                        <span class="bg-brand-cream-card text-brand-text-dark border border-amber-100/50 px-3 py-1 rounded-full text-xs font-medium">
                            {{ $category->slug }}
                        </span>

                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-5">

                        @if($category->status)

                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-full text-xs font-medium">
                                Active
                            </span>

                        @else

                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-xs font-medium">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-5">

                        <div class="flex items-center justify-end gap-2">

                            <!-- EDIT -->
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="w-10 h-10 rounded-2xl bg-brand-bg-soft hover:bg-brand-bg-soft/70 text-brand-text-dark flex items-center justify-center transition">

                                <i data-lucide="pencil"
                                   class="w-4 h-4"></i>

                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="w-10 h-10 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition">

                                    <i data-lucide="trash-2"
                                       class="w-4 h-4"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4"
                        class="px-6 py-14 text-center">

                        <div class="flex flex-col items-center">

                            <div class="w-16 h-16 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center mb-4">

                                <i data-lucide="folder-x"
                                   class="w-7 h-7 text-brand-text-dark/30"></i>

                            </div>

                            <h1 class="text-lg font-semibold text-brand-text-dark">
                                No Categories Found
                            </h1>

                            <p class="text-sm text-slate-400 mt-1">
                                Start by creating your first category.
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

        {{ $categories->links() }}

    </div>

</div>

@endsection