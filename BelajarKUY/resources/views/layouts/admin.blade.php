<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BelajarKUY Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>

        body{
            font-family: 'Inter', sans-serif;
        }

    </style>

</head>

<body class="bg-brand-bg-soft text-slate-800 antialiased">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-[270px] bg-brand-sidebar-light text-brand-text-dark border-r border-slate-200/70 flex flex-col">

        <!-- LOGO -->
        <div class="h-20 flex items-center px-6 border-b border-slate-200/70">

            <div class="flex items-center gap-3">

                <!-- ICON -->
                <div class="w-11 h-11 rounded-2xl bg-brand-text-dark flex items-center justify-center shadow-sm mb-3">
                    <i data-lucide="book-open" class="w-5 h-5 text-white"></i>

                </div>

                <!-- TEXT -->
                <div>

                    <h1 class="text-[22px] leading-none font-bold tracking-tight">

                        <span class="text-brand-text-dark mb-3">
                            Belajar
                        </span>

                        <span class="text-orange-600">
                            KUY
                        </span>

                    </h1>

                    <p class="text-[12px] text-slate-400 font-medium mt-1">
                        Admin Panel
                    </p>

                </div>

            </div>

        </div>

        <!-- NAVIGATION -->
        <nav class="flex-1 overflow-y-auto px-4 py-6">

            <div class="mb-8">

                <p class="text-[11px] uppercase tracking-widest text-slate-400 font-semibold mb-3 px-3">
                    Overview
                </p>

                <div class="space-y-1">

                    <!-- DASHBOARD -->
                    <a href="/admin/dashboard"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/dashboard') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="layout-dashboard"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Dashboard
                        </span>

                    </a>

                    <!-- CATEGORIES -->
                    <a href="/admin/categories"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/categories*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="folders"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Categories
                        </span>

                    </a>

                    <!-- SUB CATEGORIES -->
                    <a href="/admin/sub-categories"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/sub-categories*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="layout-grid"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Sub Categories
                        </span>

                    </a>

                    <!-- SLIDERS -->
                    <a href="/admin/sliders"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/sliders*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="sliders"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Sliders
                        </span>

                    </a>

                    <!-- INFO BOXES -->
                    <a href="/admin/info-boxes"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/info-boxes*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="box"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Info Boxes
                        </span>

                    </a>

                    <!-- PARTNERS -->
                    <a href="/admin/partners"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/partners*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="briefcase"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Partners
                        </span>

                    </a>

                    <!-- COURSES -->
                    <a href="/admin/courses"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/courses*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="book-open"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Course Management
                        </span>

                    </a>

                    <!-- INSTRUCTORS -->
                    <a href="/admin/instructors"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/instructors*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="users"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Instructors
                        </span>

                    </a>

                    <!-- ORDERS -->
                    <a href="/admin/orders"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/orders*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="shopping-cart"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Order Management
                        </span>

                    </a>

                    <!-- USERS -->
                    <a href="/admin/users"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/users*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="user-check"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            User Management
                        </span>

                    </a>

                    <!-- REVIEWS -->
                    <a href="/admin/reviews"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/reviews*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="message-square"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Ulasan
                        </span>

                    </a>

                    <!-- SETTINGS -->
                    <a href="/admin/settings"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
                       {{ request()->is('admin/settings*') ? 'bg-brand-accent-blue text-white shadow-lg shadow-blue-100' : 'text-brand-text-dark hover:bg-slate-100' }}">

                        <i data-lucide="settings"
                           class="w-5 h-5"></i>

                        <span class="font-medium">
                            Site Settings
                        </span>

                    </a>

                </div>

            </div>

            <!-- PLATFORM STATUS -->
            <div class="mt-auto">

                <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 rounded-3xl p-5">

                    <div class="flex items-center gap-2 mb-2">

                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>

                        <span class="text-sm font-semibold text-slate-800">
                            Platform Status
                        </span>

                    </div>

                    <p class="text-xs text-slate-500 leading-relaxed">
                        All systems running smoothly.
                    </p>

                </div>

            </div>

        </nav>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <header class="h-20 bg-brand-cream-card border-b border-slate-200/70 px-8 flex items-center justify-between">

            <!-- SEARCH -->
            <div class="relative w-[360px]">

                <i data-lucide="search"
                   class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>

                <input type="text"
                       placeholder="Search courses, users, orders..."
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/50 transition-all">

            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-5">

                <!-- NOTIFICATION -->
                <button class="relative text-slate-500 hover:text-slate-800 transition">

                    <i data-lucide="bell"
                       class="w-5 h-5"></i>

                    <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-rose-500"></span>

                </button>

                <!-- MAIL -->
                <button class="text-slate-500 hover:text-slate-800 transition">

                    <i data-lucide="mail"
                       class="w-5 h-5"></i>

                </button>

                <!-- USER -->
                <div class="flex items-center gap-3 pl-4 border-l border-slate-200">

                    <div class="w-11 h-11 rounded-2xl bg-brand-text-dark text-white flex items-center justify-center font-semibold shadow-sm mb-3">

                        Q

                    </div>

                    <div>

                        <h1 class="text-sm font-semibold text-slate-800">
                            Quinsha
                        </h1>

                        <p class="text-xs text-slate-400">
                            Administrator
                        </p>

                    </div>

                </div>

            </div>

        </header>

        <!-- CONTENT -->
        <div class="flex-1 p-8 overflow-y-auto">

            @yield('content')

        </div>

    </main>

</div>

<script>

    lucide.createIcons();

</script>

</body>
</html>