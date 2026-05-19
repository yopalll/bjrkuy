@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-2">Welcome back! Here's what's happening today.</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/40 text-brand-text-dark px-4 py-2.5 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2 cursor-pointer">
            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
            This Month
        </button>
        <button class="bg-brand-text-dark hover:bg-brand-text-dark/90 text-white px-4 py-2.5 rounded-2xl text-sm font-medium shadow-md transition flex items-center gap-2 cursor-pointer">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export
        </button>
    </div>
</div>

<!-- STATS -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <!-- Total Students -->
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden flex flex-col justify-between h-[180px] group">
        <div>
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-2xl bg-brand-bg-soft flex items-center justify-center transition-transform group-hover:scale-105 duration-300">
                    <i data-lucide="users" class="w-5 h-5 text-brand-text-dark"></i>
                </div>
                <span class="bg-emerald-50 text-emerald-600 text-xs font-semibold px-2 py-1 rounded-lg border border-emerald-100">+12%</span>
            </div>
            <p class="text-sm text-slate-500 mt-4 mb-1 font-medium">Total Students</p>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight">12,845</h1>
        </div>
        <div class="flex justify-between items-end mt-2 z-10">
            <span class="text-xs text-slate-400 font-medium">+1.5% vs yesterday</span>
            <!-- Sparkline SVG -->
            <svg class="w-24 h-8 text-emerald-500 opacity-80" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 25 L15 20 L30 23 L45 10 L60 15 L75 5 L90 8 L100 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-teal-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
    </div>

    <!-- Active Courses -->
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden flex flex-col justify-between h-[180px] group">
        <div>
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center transition-transform group-hover:scale-105 duration-300">
                    <i data-lucide="book-open" class="w-5 h-5 text-orange-500"></i>
                </div>
                <span class="bg-emerald-50 text-emerald-600 text-xs font-semibold px-2 py-1 rounded-lg border border-emerald-100">+8</span>
            </div>
            <p class="text-sm text-slate-500 mt-4 mb-1 font-medium">Active Courses</p>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight">248</h1>
        </div>
        <div class="flex justify-between items-end mt-2 z-10">
            <span class="text-xs text-slate-400 font-medium">+3 new this week</span>
            <!-- Sparkline SVG -->
            <svg class="w-24 h-8 text-orange-500 opacity-80" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 28 L15 25 L30 18 L45 20 L60 12 L75 15 L90 8 L100 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-400 to-amber-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden flex flex-col justify-between h-[180px] group">
        <div>
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center transition-transform group-hover:scale-105 duration-300">
                    <i data-lucide="credit-card" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="bg-emerald-50 text-emerald-600 text-xs font-semibold px-2 py-1 rounded-lg border border-emerald-100">+18%</span>
            </div>
            <p class="text-sm text-slate-500 mt-4 mb-1 font-medium">Total Revenue</p>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight">Rp 84.2M</h1>
        </div>
        <div class="flex justify-between items-end mt-2 z-10">
            <span class="text-xs text-slate-400 font-medium">+8% vs last month</span>
            <!-- Sparkline SVG -->
            <svg class="w-24 h-8 text-emerald-600 opacity-80" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 25 L15 22 L30 18 L45 12 L60 14 L75 8 L90 5 L100 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-green-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden flex flex-col justify-between h-[180px] group">
        <div>
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center transition-transform group-hover:scale-105 duration-300">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-rose-500"></i>
                </div>
                <span class="bg-brand-bg-soft text-brand-text-dark text-xs font-semibold px-2 py-1 rounded-lg border border-slate-200">Review</span>
            </div>
            <p class="text-sm text-slate-500 mt-4 mb-1 font-medium">Pending Orders</p>
            <h1 class="text-3xl font-extrabold text-brand-text-dark tracking-tight">36</h1>
        </div>
        <div class="flex justify-between items-end mt-2 z-10">
            <span class="text-xs text-slate-400 font-medium">-2% vs yesterday</span>
            <!-- Sparkline SVG -->
            <svg class="w-24 h-8 text-rose-500 opacity-80" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 10 L15 15 L30 8 L45 18 L60 12 L75 22 L90 15 L100 25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-400 to-red-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
    </div>
</div>

<!-- CONTENT -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <!-- TABLE -->
    <div class="xl:col-span-2 flex flex-col gap-6">
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden flex-1">
            <!-- HEADER -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-brand-text-dark">Recent Transactions</h1>
                    <p class="text-sm text-slate-400 mt-1">Latest course purchases.</p>
                </div>
                <button class="text-sm text-brand-text-dark hover:underline font-semibold flex items-center gap-1 transition cursor-pointer">
                    View All
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-brand-cream-card text-brand-text-dark font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left">Student</th>
                            <th class="px-6 py-4 text-left">Course</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-brand-bg-soft/20 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-brand-bg-soft text-brand-text-dark flex items-center justify-center font-bold shadow-sm">A</div>
                                    <div>
                                        <h1 class="font-medium text-slate-800">Andi Rahmat</h1>
                                        <p class="text-xs text-slate-400">andi@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">UI/UX Design</td>
                            <td class="px-6 py-4 text-right font-semibold text-brand-text-dark">Rp 350K</td>
                            <td class="px-6 py-4">
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-full text-xs font-semibold">Paid</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-brand-bg-soft/20 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold shadow-sm">S</div>
                                    <div>
                                        <h1 class="font-medium text-slate-800">Siti Permata</h1>
                                        <p class="text-xs text-slate-400">siti@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">Laravel Advanced</td>
                            <td class="px-6 py-4 text-right font-semibold text-brand-text-dark">Rp 499K</td>
                            <td class="px-6 py-4">
                                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- WALKTHROUGH GUIDE & PLATFORM LOGS -->
        <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-brand-text-dark flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-5 h-5 text-brand-text-dark"></i>
                        Admin Walkthrough & Action Logs
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">Quick platform reference manual and latest system events.</p>
                </div>
                <span class="bg-brand-bg-soft text-brand-text-dark text-xs font-semibold px-2.5 py-1 rounded-full border border-slate-200">Guide active</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- Guide Checklist -->
                <div>
                    <h3 class="text-sm font-semibold text-brand-text-dark mb-3 uppercase tracking-wider text-[11px]">Quick Setup Checklist</h3>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </span>
                            <span>Verify and publish new courses.</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </span>
                            <span>Approve pending instructor profiles.</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-brand-bg-soft border border-slate-300 flex items-center justify-center text-brand-text-dark flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-text-dark"></span>
                            </span>
                            <span>Review pending transactions (<span class="font-semibold text-brand-text-dark">36 total</span>).</span>
                        </li>
                    </ul>
                </div>
                
                <!-- System Logs -->
                <div>
                    <h3 class="text-sm font-semibold text-brand-text-dark mb-3 uppercase tracking-wider text-[11px]">Recent Actions</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 text-xs">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5"></div>
                            <div>
                                <p class="text-slate-800 font-medium">Laravel Advanced published successfully</p>
                                <p class="text-slate-400 mt-0.5">By Instructor: John Doe • 10 mins ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 text-xs">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5"></div>
                            <div>
                                <p class="text-slate-800 font-medium">System Settings updated</p>
                                <p class="text-slate-400 mt-0.5">Logo updated via Cloudinary • 1 hour ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POPULAR COURSES -->
    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100">
            <h1 class="text-lg font-semibold text-brand-text-dark">Popular Courses</h1>
            <p class="text-sm text-slate-400 mt-1">Best selling courses.</p>
        </div>
        <div class="p-4 space-y-4 flex-1">
            <!-- ITEM 1 -->
            <div class="flex flex-col p-4 rounded-2xl hover:bg-brand-bg-soft/40 border border-transparent hover:border-slate-200/60 transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-brand-text-dark flex flex-col items-center justify-center text-white relative overflow-hidden shadow-inner font-bold uppercase tracking-wider text-[9px] flex-shrink-0">
                            <i data-lucide="pen-tool" class="w-5 h-5 text-amber-300 mb-0.5"></i>
                            <span class="text-[8px] text-amber-100 font-medium">Design</span>
                        </div>
                        <div>
                            <h1 class="font-semibold text-slate-800 text-sm">UI/UX Design</h1>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-slate-400 font-medium">1,245 students</p>
                                <span class="text-amber-500 flex items-center gap-0.5 text-xs font-bold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">
                                    4.8
                                    <i data-lucide="star" class="w-3 h-3 fill-amber-500 text-amber-500"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <span class="font-bold text-brand-text-dark text-sm">Rp 350K</span>
                </div>
                <div class="flex items-center justify-end border-t border-dashed border-slate-200/60 pt-2.5">
                    <a href="#" class="text-xs font-semibold text-brand-text-dark hover:underline flex items-center gap-1 group-hover:translate-x-0.5 transition-transform duration-200">
                        View Course
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- ITEM 2 -->
            <div class="flex flex-col p-4 rounded-2xl hover:bg-brand-bg-soft/40 border border-transparent hover:border-slate-200/60 transition group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-brand-text-dark flex flex-col items-center justify-center text-white relative overflow-hidden shadow-inner font-bold uppercase tracking-wider text-[9px] flex-shrink-0">
                            <i data-lucide="code" class="w-5 h-5 text-orange-400 mb-0.5"></i>
                            <span class="text-[8px] text-orange-200 font-medium">Laravel</span>
                        </div>
                        <div>
                            <h1 class="font-semibold text-slate-800 text-sm">Laravel Advanced</h1>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-slate-400 font-medium">982 students</p>
                                <span class="text-amber-500 flex items-center gap-0.5 text-xs font-bold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">
                                    4.6
                                    <i data-lucide="star" class="w-3 h-3 fill-amber-500 text-amber-500"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <span class="font-bold text-brand-text-dark text-sm">Rp 499K</span>
                </div>
                <div class="flex items-center justify-end border-t border-dashed border-slate-200/60 pt-2.5">
                    <a href="#" class="text-xs font-semibold text-brand-text-dark hover:underline flex items-center gap-1 group-hover:translate-x-0.5 transition-transform duration-200">
                        View Course
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection