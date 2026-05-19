<x-admin-layout>

    <x-slot name="header">
        <h1 class="admin-page-title">Dashboard</h1>
        <p class="admin-page-subtitle">Welcome back! Here's what's happening with your platform today.</p>
    </x-slot>

    <style>
        /* ═══ STAT CARDS ═══ */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 22px; margin-bottom: 30px; }
        .stat-card { display: flex; align-items: center; gap: 18px; }
        .stat-icon {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .stat-icon.blue { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
        .stat-icon.orange { background: linear-gradient(135deg, #fff7ed, #fed7aa); }
        .stat-icon.green { background: linear-gradient(135deg, #f0fdf4, #bbf7d0); }
        .stat-icon.red { background: linear-gradient(135deg, #fef2f2, #fecaca); }
        .stat-label { font-size: 0.72rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-value { font-size: 1.65rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; line-height: 1.2; margin-top: 2px; }
        .stat-change { font-size: 0.68rem; font-weight: 600; margin-top: 2px; display: inline-flex; align-items: center; gap: 3px; }
        .stat-change.up { color: #22c55e; }
        .stat-change.warn { color: #f97316; }

        /* ═══ GRID LAYOUTS ═══ */
        .two-col { display: grid; grid-template-columns: 5fr 3fr; gap: 24px; margin-bottom: 28px; }
        .bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 1024px) { .two-col, .bottom-row { grid-template-columns: 1fr; } }

        /* ═══ SECTION HEADERS ═══ */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-title { font-size: 1rem; font-weight: 700; color: #0f172a; }
        .section-link {
            font-size: 0.78rem; color: #2563eb; text-decoration: none; font-weight: 600;
            transition: color 0.2s; display: inline-flex; align-items: center; gap: 4px;
        }
        .section-link:hover { color: #1d4ed8; }

        /* ═══ TABLE ═══ */
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
        .data-table thead th {
            text-align: left; padding: 12px 14px; color: #94a3b8; font-weight: 600;
            font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em;
            border-bottom: 1.5px solid #f1f5f9;
        }
        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:hover { background: #fafbfc; }
        .data-table tbody td { padding: 14px; border-bottom: 1px solid #f8fafc; }
        .user-cell { display: flex; align-items: center; gap: 12px; }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.72rem; flex-shrink: 0;
        }
        .user-avatar.blue { background: #eff6ff; color: #2563eb; }
        .user-avatar.orange { background: #fff7ed; color: #f97316; }
        .user-avatar.red { background: #fef2f2; color: #ef4444; }
        .user-avatar.purple { background: #f5f3ff; color: #8b5cf6; }
        .user-name { font-weight: 600; color: #1e293b; }
        .cell-muted { color: #64748b; }
        .cell-bold { font-weight: 700; color: #0f172a; }
        .status-pill {
            display: inline-block; padding: 4px 14px; border-radius: 999px;
            font-size: 0.68rem; font-weight: 700; letter-spacing: 0.02em;
        }
        .status-pill.success { background: #f0fdf4; color: #16a34a; }
        .status-pill.warning { background: #fffbeb; color: #d97706; }
        .status-pill.danger { background: #fef2f2; color: #dc2626; }

        /* ═══ COURSE LIST ═══ */
        .course-item {
            display: flex; align-items: center; gap: 14px; padding: 13px 14px;
            background: #f8fafc; border-radius: 14px; transition: all 0.2s;
            border: 1px solid transparent;
        }
        .course-item:hover { background: #f1f5f9; border-color: rgba(37,99,235,0.08); transform: translateX(2px); }
        .course-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .course-info { flex: 1; min-width: 0; }
        .course-name { font-size: 0.84rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .course-meta { font-size: 0.68rem; color: #94a3b8; font-weight: 500; margin-top: 2px; }
        .course-rating { font-size: 0.75rem; font-weight: 700; color: #f59e0b; white-space: nowrap; }

        /* ═══ REVIEW CARDS ═══ */
        .review-card {
            padding: 16px; background: #f8fafc; border-radius: 14px;
            border-left: 3px solid #2563eb; transition: all 0.2s;
        }
        .review-card:hover { background: #f1f5f9; }
        .review-card.accent-orange { border-left-color: #f97316; }
        .review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .review-author { font-size: 0.8rem; font-weight: 700; color: #0f172a; }
        .review-stars { font-size: 0.72rem; color: #f59e0b; letter-spacing: 1px; }
        .review-text { font-size: 0.78rem; color: #64748b; line-height: 1.6; font-style: italic; }

        /* ═══ QUICK ACTIONS ═══ */
        .actions-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .action-btn {
            display: flex; flex-direction: column; align-items: center; gap: 10px;
            padding: 22px 14px; background: #f8fafc; border-radius: 16px;
            text-decoration: none; transition: all 0.25s; border: 1.5px solid transparent;
        }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
        .action-btn.hover-blue:hover { border-color: #2563eb; background: #eff6ff; }
        .action-btn.hover-orange:hover { border-color: #f97316; background: #fff7ed; }
        .action-btn.hover-green:hover { border-color: #22c55e; background: #f0fdf4; }
        .action-btn.hover-purple:hover { border-color: #8b5cf6; background: #f5f3ff; }
        .action-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .action-label { font-size: 0.78rem; font-weight: 600; color: #334155; }
    </style>

    <!-- ═══ STATS ═══ -->
    <div class="stats-grid">
        <div class="admin-card stat-card">
            <div class="stat-icon blue"><i data-lucide="users" style="width:24px;height:24px;color:#2563eb;"></i></div>
            <div>
                <p class="stat-label">Total Students</p>
                <h3 class="stat-value">12,845</h3>
                <span class="stat-change up">↑ 12.5% from last month</span>
            </div>
        </div>
        <div class="admin-card stat-card">
            <div class="stat-icon orange"><i data-lucide="book-open" style="width:24px;height:24px;color:#f97316;"></i></div>
            <div>
                <p class="stat-label">Active Courses</p>
                <h3 class="stat-value">248</h3>
                <span class="stat-change up">↑ 8 new this week</span>
            </div>
        </div>
        <div class="admin-card stat-card">
            <div class="stat-icon green"><i data-lucide="trending-up" style="width:24px;height:24px;color:#22c55e;"></i></div>
            <div>
                <p class="stat-label">Total Revenue</p>
                <h3 class="stat-value">Rp 84.2M</h3>
                <span class="stat-change up">↑ 18.2% growth</span>
            </div>
        </div>
        <div class="admin-card stat-card">
            <div class="stat-icon red"><i data-lucide="shopping-bag" style="width:24px;height:24px;color:#ef4444;"></i></div>
            <div>
                <p class="stat-label">Pending Orders</p>
                <h3 class="stat-value">36</h3>
                <span class="stat-change warn">⚠ Requires attention</span>
            </div>
        </div>
    </div>

    <!-- ═══ ORDERS + COURSES ═══ -->
    <div class="two-col">
        <div class="admin-card">
            <div class="section-header">
                <h2 class="section-title">Recent Orders</h2>
                <a href="#" class="section-link">View All →</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr><th>Student</th><th>Course</th><th>Amount</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class="user-cell"><div class="user-avatar blue">AR</div><span class="user-name">Andi Rahmat</span></div></td>
                        <td class="cell-muted">UI/UX Design Mastery</td>
                        <td class="cell-bold">Rp 350K</td>
                        <td><span class="status-pill success">Completed</span></td>
                    </tr>
                    <tr>
                        <td><div class="user-cell"><div class="user-avatar orange">SP</div><span class="user-name">Siti Permata</span></div></td>
                        <td class="cell-muted">Laravel Advanced</td>
                        <td class="cell-bold">Rp 499K</td>
                        <td><span class="status-pill warning">Pending</span></td>
                    </tr>
                    <tr>
                        <td><div class="user-cell"><div class="user-avatar red">BW</div><span class="user-name">Budi Wijaya</span></div></td>
                        <td class="cell-muted">Data Science Python</td>
                        <td class="cell-bold">Rp 599K</td>
                        <td><span class="status-pill success">Completed</span></td>
                    </tr>
                    <tr>
                        <td><div class="user-cell"><div class="user-avatar purple">DN</div><span class="user-name">Dewi Nurhayati</span></div></td>
                        <td class="cell-muted">React Fullstack</td>
                        <td class="cell-bold">Rp 450K</td>
                        <td><span class="status-pill danger">Cancelled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="admin-card">
            <div class="section-header">
                <h2 class="section-title">Popular Courses</h2>
                <a href="#" class="section-link">See All →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div class="course-item">
                    <div class="course-icon" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                        <i data-lucide="palette" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                    <div class="course-info">
                        <p class="course-name">UI/UX Design Mastery</p>
                        <p class="course-meta">1,245 students enrolled</p>
                    </div>
                    <span class="course-rating">⭐ 4.9</span>
                </div>
                <div class="course-item">
                    <div class="course-icon" style="background:linear-gradient(135deg,#f97316,#ea580c);">
                        <i data-lucide="code" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                    <div class="course-info">
                        <p class="course-name">Laravel Advanced</p>
                        <p class="course-meta">982 students enrolled</p>
                    </div>
                    <span class="course-rating">⭐ 4.8</span>
                </div>
                <div class="course-item">
                    <div class="course-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                        <i data-lucide="database" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                    <div class="course-info">
                        <p class="course-name">Data Science Python</p>
                        <p class="course-meta">876 students enrolled</p>
                    </div>
                    <span class="course-rating">⭐ 4.7</span>
                </div>
                <div class="course-item">
                    <div class="course-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">
                        <i data-lucide="smartphone" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                    <div class="course-info">
                        <p class="course-name">Mobile App Flutter</p>
                        <p class="course-meta">654 students enrolled</p>
                    </div>
                    <span class="course-rating">⭐ 4.6</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ REVIEWS + ACTIONS ═══ -->
    <div class="bottom-row">
        <div class="admin-card">
            <div class="section-header">
                <h2 class="section-title">Latest Reviews</h2>
                <a href="#" class="section-link">View All →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div class="review-card">
                    <div class="review-header">
                        <span class="review-author">Maria Susanti</span>
                        <span class="review-stars">★★★★★</span>
                    </div>
                    <p class="review-text">"Materi sangat lengkap dan mudah dipahami. Mentor sangat responsif!"</p>
                </div>
                <div class="review-card accent-orange">
                    <div class="review-header">
                        <span class="review-author">Rizky Pratama</span>
                        <span class="review-stars">★★★★☆</span>
                    </div>
                    <p class="review-text">"Course Laravel-nya bagus, tapi butuh lebih banyak project-based learning."</p>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h2 class="section-title" style="margin-bottom:18px;">Quick Actions</h2>
            <div class="actions-grid">
                <a href="#" class="action-btn hover-blue">
                    <div class="action-icon" style="background:linear-gradient(135deg,#1e3a5f,#2563eb);"><i data-lucide="plus" style="width:18px;height:18px;color:#fff;"></i></div>
                    <span class="action-label">Add Course</span>
                </a>
                <a href="#" class="action-btn hover-orange">
                    <div class="action-icon" style="background:linear-gradient(135deg,#f97316,#ea580c);"><i data-lucide="user-plus" style="width:18px;height:18px;color:#fff;"></i></div>
                    <span class="action-label">Add Instructor</span>
                </a>
                <a href="#" class="action-btn hover-green">
                    <div class="action-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a);"><i data-lucide="image-plus" style="width:18px;height:18px;color:#fff;"></i></div>
                    <span class="action-label">Add Slider</span>
                </a>
                <a href="#" class="action-btn hover-purple">
                    <div class="action-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);"><i data-lucide="file-text" style="width:18px;height:18px;color:#fff;"></i></div>
                    <span class="action-label">View Reports</span>
                </a>
            </div>
        </div>
    </div>

</x-admin-layout>
