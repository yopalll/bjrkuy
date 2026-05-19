@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            User Management
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            View all registered users on the platform.
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
                    <th class="px-6 py-4 text-left">User</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-center">Role</th>
                    <th class="px-6 py-4 text-right">Date Registered</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <!-- TABLE BODY -->
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-brand-bg-soft/40 transition">
                    
                    <!-- USER DETAILS -->
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            @if($user->photo)
                                <img src="{{ $user->photo }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs border border-slate-200 shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h1 class="font-semibold text-slate-800">
                                    {{ $user->name }}
                                </h1>
                            </div>
                        </div>
                    </td>

                    <!-- EMAIL -->
                    <td class="px-6 py-5">
                        <span class="text-slate-600 font-medium">
                            {{ $user->email }}
                        </span>
                    </td>

                    <!-- ROLE BADGE -->
                    <td class="px-6 py-5 text-center">
                        @if($user->role === 'admin')
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">
                                Admin
                            </span>
                        @elseif($user->role === 'instructor')
                            <span class="bg-purple-50 text-purple-600 border border-purple-100 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">
                                Instructor
                            </span>
                        @else
                            <span class="bg-brand-bg-soft text-brand-text-dark border border-slate-200 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide">
                                Student
                            </span>
                        @endif
                    </td>

                    <!-- DATE REGISTERED -->
                    <td class="px-6 py-5 text-right">
                        <span class="text-slate-500 font-medium text-xs">
                            {{ $user->created_at->format('d M Y') }}
                        </span>
                    </td>

                    <!-- ACTIONS -->
                    <td class="px-6 py-5 text-right">
                        <button disabled class="text-slate-400 cursor-not-allowed px-3 py-1 text-xs font-semibold" title="View-only access">
                            No Actions
                        </button>
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
                                No Users Found
                            </h1>
                            <p class="text-sm text-slate-400 mt-1">
                                There are no users registered on the platform yet.
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
        {{ $users->links() }}
    </div>
</div>

@endsection
