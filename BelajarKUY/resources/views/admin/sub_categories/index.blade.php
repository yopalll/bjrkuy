@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">

    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Sub Categories
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Manage course sub-categories under main categories.
        </p>
    </div>

    <a href="{{ route('admin.sub-categories.create') }}"
       class="bg-brand-accent-blue hover:opacity-90 text-white px-5 py-3 rounded-2xl text-sm font-medium shadow-lg shadow-blue-100 transition flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Add Sub Category
    </a>

</div>

<!-- TABLE -->
<div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <!-- TABLE HEADER -->
            <thead class="bg-brand-cream-card border-b border-slate-100">
                <tr class="text-brand-text-dark uppercase tracking-wider font-bold text-[11px]">
                    <th class="px-6 py-4 text-left">Sub Category Name</th>
                    <th class="px-6 py-4 text-left">Parent Category</th>
                    <th class="px-6 py-4 text-left">Slug</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <!-- TABLE BODY -->
            <tbody class="divide-y divide-slate-100">

                @forelse($subCategories as $subCategory)

                <tr class="hover:bg-brand-bg-soft/40 transition">

                    <!-- NAME -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-brand-cream-card flex items-center justify-center border border-amber-100/50">
                                <i data-lucide="layout-grid" class="w-5 h-5 text-brand-text-dark/70"></i>
                            </div>
                            <div>
                                <h1 class="font-semibold text-slate-800">
                                    {{ $subCategory->name }}
                                </h1>
                                <p class="text-xs text-slate-400 mt-1">
                                    Sub Category
                                </p>
                            </div>
                        </div>
                    </td>

                    <!-- PARENT CATEGORY -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-accent-blue"></span>
                            <span class="font-medium text-brand-text-dark">
                                {{ $subCategory->category->name ?? 'N/A' }}
                            </span>
                        </div>
                    </td>

                    <!-- SLUG -->
                    <td class="px-6 py-5">
                        <span class="bg-brand-cream-card text-brand-text-dark border border-amber-100/50 px-3 py-1 rounded-full text-xs font-medium">
                            {{ $subCategory->slug }}
                        </span>
                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.sub-categories.edit', $subCategory) }}"
                               class="w-10 h-10 rounded-2xl bg-brand-bg-soft hover:bg-brand-bg-soft/70 text-brand-text-dark flex items-center justify-center transition">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.sub-categories.destroy', $subCategory) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-10 h-10 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="4" class="px-6 py-14 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-brand-cream-card border border-amber-100/50 flex items-center justify-center mb-4">
                                <i data-lucide="folder-x" class="w-7 h-7 text-brand-text-dark/30"></i>
                            </div>
                            <h1 class="text-lg font-semibold text-brand-text-dark">
                                No Sub Categories Found
                            </h1>
                            <p class="text-sm text-slate-400 mt-1">
                                Start by creating your first sub category.
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
        {{ $subCategories->links() }}
    </div>

</div>

@endsection
