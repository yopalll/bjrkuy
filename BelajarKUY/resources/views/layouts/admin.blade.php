<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard' }} — BelajarKUY</title>
    <meta name="description" content="BelajarKUY Admin Dashboard — Manage courses, users, and platform content.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ═══ SIDEBAR ═══ */
        .admin-sidebar {
            width: 272px; min-height: 100vh; background: #ffffff;
            border-right: 1px solid rgba(226,232,240,0.6);
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 40;
            display: flex; flex-direction: column;
            transition: transform 0.35s cubic-bezier(.4,0,.2,1);
            box-shadow: 1px 0 12px rgba(0,0,0,0.02);
        }
        .sidebar-brand {
            padding: 26px 24px 22px; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(37,99,235,0.2);
        }
        .sidebar-brand-icon span { color: #fff; font-weight: 800; font-size: 1rem; }
        .sidebar-brand-text h1 {
            font-size: 1.25rem; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; line-height: 1.2;
        }
        .sidebar-brand-text h1 em { font-style: normal; color: #f97316; }
        .sidebar-brand-text p {
            font-size: 0.65rem; color: #94a3b8; text-transform: uppercase;
            letter-spacing: 0.12em; font-weight: 600; margin-top: 1px;
        }

        .sidebar-nav { flex: 1; padding: 20px 14px 12px; overflow-y: auto; }
        .sidebar-section-label {
            font-size: 0.6rem; font-weight: 700; color: #cbd5e1;
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 20px 14px 8px; margin-top: 4px;
        }
        .sidebar-section-label:first-child { padding-top: 0; margin-top: 0; }

        .sidebar-link {
            display: flex; align-items: center; gap: 14px;
            padding: 11px 14px; border-radius: 12px;
            font-size: 0.835rem; font-weight: 500; color: #64748b;
            text-decoration: none; transition: all 0.2s ease;
            margin-bottom: 3px; position: relative;
        }
        .sidebar-link:hover { background: #f8fafc; color: #1e3a5f; transform: translateX(2px); }
        .sidebar-link.active {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: #ffffff; box-shadow: 0 4px 16px rgba(37,99,235,0.28);
            transform: translateX(0);
        }
        .sidebar-link.active:hover { transform: translateX(0); }
        .sidebar-link i { width: 20px; height: 20px; flex-shrink: 0; opacity: 0.7; }
        .sidebar-link:hover i { opacity: 1; }
        .sidebar-link.active i { opacity: 1; color: #fff; }
        .sidebar-link .badge {
            margin-left: auto; background: #fef2f2; color: #ef4444;
            font-size: 0.6rem; font-weight: 700; padding: 3px 9px; border-radius: 999px;
        }
        .sidebar-link.active .badge { background: rgba(255,255,255,0.2); color: #fff; }

        .sidebar-footer { padding: 14px 18px; border-top: 1px solid #f1f5f9; }
        .sidebar-footer-card {
            background: linear-gradient(135deg, #eff6ff 0%, #fef3c7 100%);
            border-radius: 14px; padding: 18px; text-align: center;
            border: 1px solid rgba(37,99,235,0.06);
        }
        .sidebar-footer-card p { font-size: 0.72rem; color: #64748b; margin-bottom: 6px; }
        .sidebar-footer-card strong { display: block; font-size: 0.78rem; color: #1e3a5f; margin-bottom: 10px; font-weight: 700; }
        .sidebar-footer-card a {
            font-size: 0.72rem; font-weight: 700; color: #2563eb;
            text-decoration: none; transition: color 0.2s;
        }
        .sidebar-footer-card a:hover { color: #1d4ed8; }

        /* ═══ TOPBAR ═══ */
        .admin-topbar {
            position: fixed; top: 0; left: 272px; right: 0; height: 70px;
            background: rgba(255,255,255,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226,232,240,0.5);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; z-index: 30;
            transition: left 0.35s cubic-bezier(.4,0,.2,1);
        }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-toggle {
            display: none; background: none; border: none; cursor: pointer;
            padding: 8px; border-radius: 10px; color: #64748b; transition: all 0.2s;
        }
        .topbar-toggle:hover { background: #f1f5f9; color: #1e3a5f; }
        .topbar-search { position: relative; width: 340px; }
        .topbar-search input {
            width: 100%; padding: 10px 16px 10px 44px;
            background: #f1f5f9; border: 1.5px solid transparent;
            border-radius: 14px; font-size: 0.84rem; font-weight: 500;
            color: #334155; outline: none; transition: all 0.25s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .topbar-search input::placeholder { color: #94a3b8; font-weight: 400; }
        .topbar-search input:focus {
            border-color: #2563eb; background: #fff;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.06);
        }
        .topbar-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 17px; height: 17px; }

        .topbar-right { display: flex; align-items: center; gap: 6px; }
        .topbar-btn {
            position: relative; background: none; border: none; cursor: pointer;
            padding: 10px; border-radius: 12px; color: #64748b; transition: all 0.2s;
        }
        .topbar-btn:hover { background: #f1f5f9; color: #1e3a5f; }
        .topbar-btn i { width: 20px; height: 20px; }
        .topbar-btn .notif-dot {
            position: absolute; top: 7px; right: 7px;
            width: 9px; height: 9px; background: #f97316;
            border-radius: 999px; border: 2px solid #fff;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(249,115,22,0.4); }
            50% { box-shadow: 0 0 0 4px rgba(249,115,22,0); }
        }
        .topbar-divider { width: 1px; height: 28px; background: #e2e8f0; margin: 0 10px; }

        .topbar-profile {
            display: flex; align-items: center; gap: 12px;
            padding: 5px 14px 5px 5px; border-radius: 14px;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .topbar-profile:hover { background: #f8fafc; }
        .topbar-profile-avatar {
            width: 38px; height: 38px; border-radius: 12px;
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.85rem;
            box-shadow: 0 2px 8px rgba(37,99,235,0.2);
        }
        .topbar-profile-info { line-height: 1.25; }
        .topbar-profile-name { font-size: 0.8rem; font-weight: 700; color: #0f172a; }
        .topbar-profile-role { font-size: 0.62rem; color: #94a3b8; font-weight: 500; }

        /* ═══ CONTENT ═══ */
        .admin-content {
            margin-left: 272px; margin-top: 70px;
            min-height: calc(100vh - 70px); background: #f8fafc;
            padding: 30px 32px;
            transition: margin-left 0.35s cubic-bezier(.4,0,.2,1);
        }

        /* ═══ REUSABLE ═══ */
        .admin-card {
            background: #ffffff; border-radius: 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02);
            border: 1px solid rgba(241,245,249,0.8);
            padding: 24px; transition: all 0.25s ease;
        }
        .admin-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.05); transform: translateY(-1px); }
        .admin-page-header { margin-bottom: 28px; }
        .admin-page-title { font-size: 1.6rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
        .admin-page-subtitle { font-size: 0.88rem; color: #94a3b8; margin-top: 4px; font-weight: 400; }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 1024px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-topbar { left: 0; }
            .admin-content { margin-left: 0; }
            .topbar-toggle { display: flex; }
            .topbar-search { width: 220px; }
        }
        @media (max-width: 640px) {
            .topbar-search { display: none; }
            .topbar-profile-info { display: none; }
            .admin-content { padding: 20px 16px; }
        }
        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.25);
            z-index: 35; opacity: 0; pointer-events: none;
            transition: opacity 0.3s; backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { opacity: 1; pointer-events: auto; }

        /* Scrollbar */
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 999px; }
    </style>
</head>
<body class="bg-[#f8fafc] antialiased" style="font-family:'Plus Jakarta Sans',sans-serif;">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ═══ SIDEBAR ═══ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><span>B</span></div>
            <div class="sidebar-brand-text">
                <h1>Belajar<em>KUY</em></h1>
                <p>Admin Panel</p>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" id="nav-dashboard">
                <i data-lucide="layout-dashboard"></i><span>Dashboard</span>
            </a>

            <div class="sidebar-section-label">Content</div>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" id="nav-categories">
                <i data-lucide="folder-tree"></i><span>Categories</span>
            </a>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}" id="nav-subcategories">
                <i data-lucide="folders"></i><span>SubCategories</span>
            </a>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" id="nav-courses">
                <i data-lucide="book-open"></i><span>Courses</span>
            </a>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.instructors.*') ? 'active' : '' }}" id="nav-instructors">
                <i data-lucide="graduation-cap"></i><span>Instructors</span>
            </a>

            <div class="sidebar-section-label">Commerce</div>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" id="nav-orders">
                <i data-lucide="shopping-bag"></i><span>Orders</span>
                <span class="badge">12</span>
            </a>

            <div class="sidebar-section-label">Users & Feedback</div>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" id="nav-users">
                <i data-lucide="users"></i><span>Users</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" id="nav-reviews">
                <i data-lucide="star"></i><span>Reviews</span>
            </a>

            <div class="sidebar-section-label">Appearance</div>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" id="nav-sliders">
                <i data-lucide="image"></i><span>Sliders</span>
            </a>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}" id="nav-partners">
                <i data-lucide="handshake"></i><span>Partners</span>
            </a>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.infoboxes.*') ? 'active' : '' }}" id="nav-infoboxes">
                <i data-lucide="info"></i><span>Info Boxes</span>
            </a>

            <div class="sidebar-section-label">System</div>
            <a href="#" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" id="nav-settings">
                <i data-lucide="settings"></i><span>Settings</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-footer-card">
                <p>🎓 Platform Status</p>
                <strong>All systems running</strong>
                <a href="#">View Analytics →</a>
            </div>
        </div>
    </aside>

    <!-- ═══ TOPBAR ═══ -->
    <header class="admin-topbar" id="adminTopbar">
        <div class="topbar-left">
            <button class="topbar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar" id="sidebar-toggle-btn">
                <i data-lucide="menu" style="width:22px;height:22px;"></i>
            </button>
            <div class="topbar-search">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Search courses, users, orders..." id="admin-search-input">
            </div>
        </div>
        <div class="topbar-right">
            <button class="topbar-btn" aria-label="Notifications" id="notif-btn">
                <i data-lucide="bell"></i><span class="notif-dot"></span>
            </button>
            <button class="topbar-btn" aria-label="Messages" id="messages-btn">
                <i data-lucide="mail"></i>
            </button>
            <div class="topbar-divider"></div>
            <a href="{{ route('profile.edit') }}" class="topbar-profile" id="admin-profile-link">
                <div class="topbar-profile-avatar">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</div>
                <div class="topbar-profile-info">
                    <div class="topbar-profile-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="topbar-profile-role">Administrator</div>
                </div>
            </a>
        </div>
    </header>

    <!-- ═══ CONTENT ═══ -->
    <main class="admin-content" id="adminContent">
        @isset($header)
            <div class="admin-page-header">{{ $header }}</div>
        @endisset
        {{ $slot }}
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
    </script>
</body>
</html>
