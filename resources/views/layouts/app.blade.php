<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Monexa') - Quản lý chi tiêu</title>
    <link rel="icon" type="images/png" href="{{ asset('favicon.png') }}">
    <style>
        /* Giá trị biến toàn cục */
        :root {
            --primary: #4a90e2;
            --primary-dark: #2a5298;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark-bg: #0f1217;
            --dark-card: #191d27;
            --dark-border: rgba(255, 255, 255, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf4 100%);
            overflow-x: hidden;
            transition: var(--transition);
        }

        /* Dark Mode */
        body.dark {
            background: var(--dark-bg);
        }

        body.dark .topbar,
        body.dark .sidebar {
            background: rgba(22, 25, 32, 0.95);
            border-color: var(--dark-border);
        }

        body.dark .brand-name {
            -webkit-text-fill-color: #fff;
        }

        body.dark .search-bar,
        body.dark .icon-btn,
        body.dark .user-profile {
            background: #1c212a;
        }

        body.dark .search-bar input {
            color: #e5e7eb;
        }

        body.dark .search-bar input::placeholder {
            color: #868686;
        }

        body.dark .user-profile:hover {
            background: #2d3748;
        }

        body.dark .user-name {
            color: #e5e7eb;
        }

        body.dark .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        body.dark .nav-text {
            background: #1a1f29;
            color: #e5e7eb;
        }

        body.dark .nav-text::before {
            border-color: transparent #1a1f29 transparent transparent;
        }

        body.dark .profile-dropdown {
            background: #1a1f29;
            border-color: rgba(255, 255, 255, 0.1);
        }

        body.dark .dropdown-item {
            color: #e5e7eb;
        }

        body.dark .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        body.dark .card,
        body.dark .stat-card {
            background: var(--dark-card);
            border-color: var(--dark-border);
        }

        body.dark .card-title,
        body.dark .stat-value {
            color: #e5e7eb;
        }

        body.dark .stat-label {
            color: #9ca3af;
        }

        body.dark .chart-placeholder {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px 0 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 1000;
            transition: var(--transition);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            padding: 9px;
            box-shadow: 0 4px 16px rgba(74, 144, 226, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #f8f9fd;
            padding: 12px 20px;
            border-radius: 12px;
            flex: 1;
            max-width: 500px;
            margin: 0 auto;
            gap: 10px;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .search-bar:focus-within {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.2);
        }

        .search-bar img {
            width: 18px;
            height: 18px;
            opacity: 0.5;
            transition: opacity 0.3s;
        }

        .search-bar:focus-within img {
            opacity: 0.8;
        }

        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .search-bar input::placeholder {
            color: #9ca3af;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f8f9fd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .icon-btn:hover {
            background: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(74, 144, 226, 0.25);
        }

        .icon-btn img {
            width: 20px;
            height: 20px;
            opacity: 0.6;
            transition: opacity 0.3s;
        }

        .icon-btn:hover img {
            opacity: 1;
        }

        /* User Profile */
        .user-profile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
            background: #f8f9fd;
        }

        .user-profile:hover {
            background: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(74, 144, 226, 0.2);
        }

        .user-profile::after {
            content: "▼";
            font-size: 10px;
            margin-left: 4px;
            transition: transform 0.3s ease;
            color: #9ca3af;
        }

        .user-profile:hover::after {
            color: var(--primary);
        }

        .user-profile.active::after {
            transform: rotate(180deg);
            color: var(--primary);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .user-avatar-img {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.3;
        }

        /* Dropdown Profile */
        .profile-dropdown {
            position: absolute;
            top: 65px;
            right: 0;
            width: 280px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 24px 20px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            text-align: center;
        }

        .dropdown-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin: 0 auto 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 800;
            border: 3px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }

        .dropdown-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropdown-name {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .dropdown-email {
            font-size: 13px;
            opacity: 0.9;
        }

        .dropdown-menu {
            padding: 8px 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, rgba(74, 144, 226, 0.08) 0%, transparent 100%);
            padding-left: 26px;
            color: var(--primary);
        }

        .dropdown-item::before {
            content: '';
            width: 8px;
            height: 8px;
            background: currentColor;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .dropdown-item:hover::before {
            opacity: 1;
        }

        .dropdown-item.logout {
            color: var(--danger);
            font-weight: 600;
            border-top: 1px solid #f3f4f6;
            margin-top: 4px;
        }

        .dropdown-item.logout:hover {
            background: linear-gradient(90deg, rgba(239, 68, 68, 0.08) 0%, transparent 100%);
            color: #dc2626;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            height: calc(100vh - 70px);
            width: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            z-index: 100;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.08);
            border-right: 1px solid rgba(226, 232, 240, 0.8);
            transition: var(--transition);
        }

        .nav-menu {
            list-style: none;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .nav-item {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            text-decoration: none;
            transition: var(--transition);
            background: transparent;
        }

        .nav-link:hover {
            background: rgba(74, 144, 226, 0.1);
            transform: scale(1.1);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 8px 20px rgba(74, 144, 226, 0.35);
        }

        .nav-icon {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: opacity(0.6);
            transition: var(--transition);
        }

        .nav-link:hover .nav-icon img,
        .nav-link.active .nav-icon img {
            filter: opacity(1);
        }

        .nav-text {
            position: absolute;
            left: 70px;
            background: white;
            color: #1f2937;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-10px);
            transition: var(--transition);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            pointer-events: none;
            z-index: 1000;
        }

        .nav-text::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 6px 6px 6px 0;
            border-color: transparent white transparent transparent;
        }

        .nav-link:hover .nav-text {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        .nav-link.active .nav-text {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .nav-link.active .nav-text::before {
            border-color: transparent var(--primary) transparent transparent;
        }

        /* Main Content */
        .main-content {
            margin-left: 80px;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
        }

        .content {
            padding: 35px 40px 40px;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 26px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 800;
            color: #1f2937;
            letter-spacing: -0.3px;
        }

        .card-menu {
            width: 36px;
            height: 36px;
            cursor: pointer;
            transition: var(--transition);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fd;
        }

        .card-menu:hover {
            background: rgba(74, 144, 226, 0.1);
            transform: rotate(90deg);
        }

        .card-menu img {
            width: 20px;
            height: 20px;
            opacity: 0.6;
        }

        .card-menu:hover img {
            opacity: 1;
        }

        .chart-placeholder {
            height: 280px;
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.05) 0%, rgba(42, 82, 152, 0.05) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 15px;
            font-weight: 600;
            border: 2px dashed rgba(74, 144, 226, 0.2);
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 28px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.12);
            transform: translateY(-6px);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.08);
        }

        .stat-icon img {
            width: 28px;
            height: 28px;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.15) 0%, rgba(74, 144, 226, 0.05) 100%);
        }

        .stat-icon.green {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 100%);
        }

        .stat-icon.red {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%);
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.15) 0%, rgba(251, 146, 60, 0.05) 100%);
        }

        .stat-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 900;
            color: #1f2937;
            letter-spacing: -1px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -80px;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 0 20px;
            }

            .brand-name,
            .user-info {
                display: none;
            }

            .search-bar {
                max-width: 100%;
            }

            .content {
                padding: 25px 20px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="{{ cookie('theme', 'light') === 'dark' ? 'dark' : '' }}">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="brand-info">
                <div class="brand-logo">
                    <img src="/images/logo.png" alt="Logo">
                </div>
                <span class="brand-name">Monexa</span>
            </div>
            
            <div class="search-bar">
                <img src="/images/search.png" alt="Search">
                <input type="text" placeholder="Tìm kiếm giao dịch, danh mục...">
            </div>
        </div>

        <div class="topbar-right">
            <div class="icon-btn">
                <img src="/images/bell.png" alt="Notifications">
            </div>
            <div id="themeToggle" class="icon-btn">
                <img src="/images/dark-mode.png" alt="Theme">
            </div>
            <div class="user-profile" id="userProfile">
                @if(Auth::user()->avatar)
                    @if(str_starts_with(Auth::user()->avatar, 'http'))
                        <img src="{{ Auth::user()->avatar }}" class="user-avatar-img" alt="Avatar">
                    @else
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="user-avatar-img" alt="Avatar">
                    @endif
                @else
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif

                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">
                        @if(Auth::user()->avatar)
                            @if(str_starts_with(Auth::user()->avatar, 'http'))
                                <img src="{{ Auth::user()->avatar }}" alt="Avatar">
                            @else
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
                            @endif
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>  
                    <div class="dropdown-name">{{ Auth::user()->name }}</div>
                    <div class="dropdown-email">{{ Auth::user()->email }}</div>
                </div>
                <div class="dropdown-menu">
                    <a href="/profile" class="dropdown-item">Hồ sơ cá nhân</a>
                    @if(!Auth::user()->google_id)
                        <a href="/change-password" class="dropdown-item">Đổi mật khẩu</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" class="dropdown-item logout">Đăng xuất</button>
                    </form>
                </div>
            </div>         
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <img src="{{ asset('images/home.png') }}" alt="Home">
                    </span>
                    <span class="nav-text">Trang chủ</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <img src="{{ asset('images/transaction.png') }}" alt="Transaction">
                    </span>
                    <span class="nav-text">Giao dịch</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('wallets.index') }}" class="nav-link {{ request()->routeIs('wallets.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <img src="{{ asset('images/wallet.png') }}" alt="Budget">
                    </span>
                    <span class="nav-text">Ngân sách</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <img src="{{ asset('images/category.png') }}" alt="Category">
                    </span>
                    <span class="nav-text">Danh mục</span>
                </a>
            </li>     
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">
                        <img src="/images/settings.png" alt="Settings">
                    </span>
                    <span class="nav-text">Cài đặt</span>
                </a>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') document.body.classList.add('dark');

        document.getElementById('themeToggle')?.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
        });

        const userProfile = document.getElementById('userProfile');
        const dropdown = document.getElementById('profileDropdown');
        
        userProfile?.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
        
        document.addEventListener('click', () => dropdown.classList.remove('show'));
        dropdown?.addEventListener('click', e => e.stopPropagation());
    </script>
</body>
</html>