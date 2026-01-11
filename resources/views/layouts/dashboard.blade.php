<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Integrated Documents Employee Dashboard - SITE')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #028a0f;
            --primary-dark: #026a0c;
            --primary-light: rgba(2, 138, 15, 0.65);
            --white: #ffffff;
            --bg-light: #f8f9fa;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --border-color: #e0e0e0;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Dark Mode */
        [data-theme="dark"] {
            --primary-color: #02b815;
            --primary-dark: #028a0f;
            --primary-light: rgba(2, 184, 21, 0.65);
            --white: #1e1e1e;
            --bg-light: #121212;
            --text-dark: #e0e0e0;
            --text-light: #b0b0b0;
            --border-color: #333333;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        /* Font Size Options */
        [data-font-size="small"] {
            font-size: 13px;
        }
        [data-font-size="medium"] {
            font-size: 15px;
        }
        [data-font-size="large"] {
            font-size: 17px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--white);
            box-shadow: var(--shadow);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 13px;
            opacity: 0.9;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 14px 25px;
            display: flex;
            align-items: center;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .menu-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(2, 138, 15, 0.08);
            padding-left: 30px;
        }

        .menu-item:hover:before {
            transform: translateX(0);
        }

        .menu-item.active {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
        }

        .menu-item.active:before {
            transform: translateX(0);
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            width: calc(100% - 260px);
        }

        /* Top Bar */
        .top-bar {
            background: var(--white);
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-text h1 {
            font-size: 28px;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .welcome-text p {
            color: var(--text-light);
            font-size: 14px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-icon {
            position: relative;
            font-size: 22px;
            color: var(--text-light);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .notification-icon:hover {
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
        }

        .logout-btn {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 26px;
            color: var(--primary-dark);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 14px;
        }

        /* Content Card */
        .content-card {
            background: var(--white);
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            animation: fadeInUp 0.8s ease;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: var(--text-dark);
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: var(--primary-light);
        }

        .data-table th {
            padding: 15px;
            text-align: left;
            font-weight: 700;
            color: #000000;
            font-size: 14px;
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .data-table tbody tr {
            transition: background 0.3s ease;
        }

        .data-table tbody tr:hover {
            background: rgba(2, 138, 15, 0.05);
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(2, 138, 15, 0.1);
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Icon Button Styles */
        .icon-btn {
            background: transparent;
            border: none;
            color: var(--text-light);
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .icon-btn:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            padding: 10px;
            min-width: 120px;
            z-index: 1000;
            margin-top: 5px;
        }

        .dropdown-menu button {
            display: block;
            width: 100%;
            padding: 8px 12px;
            background: transparent;
            border: none;
            text-align: left;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.2s ease;
            color: var(--text-dark);
        }

        .dropdown-menu button:hover {
            background: var(--primary-light);
        }

        /* Global Search Modal */
        .search-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: flex-start;
            justify-content: center;
            padding-top: 100px;
        }

        .search-modal.active {
            display: flex;
        }

        .search-modal-content {
            background: var(--white);
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: var(--shadow-lg);
            animation: slideDown 0.3s ease;
        }

        .search-input-wrapper {
            padding: 20px;
            border-bottom: 2px solid var(--border-color);
        }

        .search-input-wrapper input {
            width: 100%;
            padding: 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .search-input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .search-results {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
        }

        .search-result-item {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .search-result-item:hover {
            background: var(--primary-light);
        }

        .search-result-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .search-result-type {
            font-size: 12px;
            color: var(--text-light);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('uploads/documents/site_logo-removebg-preview.png') }}" alt="SITE Logo" style="width: 60px; height: 60px; margin-bottom: 10px; object-fit: contain; background: white; padding: 3px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.2); border: 2px solid rgba(255,255,255,0.8);">
                <h2 style="font-size: 16px; line-height: 1.3; margin-bottom: 8px;">Integrated Documents Employee Dashboard</h2>
                <p style="font-size: 11px; opacity: 0.95; margin-bottom: 5px;">School of Information Technology and Engineering</p>
                <p style="font-size: 12px; font-weight: 600;">{{ auth()->user()->role->role_name }}</p>
            </div>
            <nav class="sidebar-menu">
                @yield('sidebar')
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="welcome-text">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-subtitle', 'Welcome back!')</p>
                </div>
                <div class="user-info">
                    @if(auth()->user()->isFaculty())
                    <a href="{{ route('faculty.notifications') }}" class="notification-icon">
                        <i class="fas fa-bell"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                        <span class="notification-badge">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                    @endif
                    
                    <!-- Theme & Settings Controls -->
                    <div class="settings-controls" style="display: flex; gap: 10px; align-items: center;">
                        <!-- Font Size -->
                        <div class="dropdown" style="position: relative;">
                            <button class="icon-btn" id="fontSizeBtn" title="Font Size">
                                <i class="fas fa-text-height"></i>
                            </button>
                            <div class="dropdown-menu" id="fontSizeMenu" style="display: none;">
                                <button onclick="changeFontSize('small')">Small</button>
                                <button onclick="changeFontSize('medium')">Medium</button>
                                <button onclick="changeFontSize('large')">Large</button>
                            </div>
                        </div>
                        
                        <!-- Dark Mode Toggle -->
                        <button class="icon-btn" id="darkModeToggle" title="Toggle Dark Mode">
                            <i class="fas fa-moon"></i>
                        </button>
                        
                        <!-- Global Search -->
                        <button class="icon-btn" id="globalSearchBtn" title="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                    </div>
                    
                    <!-- User Dropdown Menu -->
                    <div class="dropdown" style="position: relative;">
                        <button class="icon-btn" id="userMenuBtn" style="font-size: 14px; padding: 8px 12px; border-radius: 8px;">
                            {{ auth()->user()->username }} <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i>
                        </button>
                        <div class="dropdown-menu" id="userMenu" style="display: none; min-width: 180px;">
                            <a href="{{ route('profile.edit') }}" style="display: block; padding: 10px 15px; color: var(--text-dark); text-decoration: none; border-radius: 4px;">
                                <i class="fas fa-user-edit"></i> Edit Profile
                            </a>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; padding: 10px 15px; background: transparent; border: none; color: var(--text-dark); cursor: pointer; border-radius: 4px;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Global Search Modal -->
    <div class="search-modal" id="searchModal">
        <div class="search-modal-content">
            <div class="search-input-wrapper">
                <input type="text" id="globalSearchInput" placeholder="Search employees, tasks, documents..." autocomplete="off">
            </div>
            <div class="search-results" id="searchResults">
                <p style="text-align: center; color: var(--text-light); padding: 20px;">
                    Type to search...
                </p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        updateDarkModeIcon(savedTheme);
        
        darkModeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateDarkModeIcon(newTheme);
        });
        
        function updateDarkModeIcon(theme) {
            const icon = darkModeToggle.querySelector('i');
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        
        // Font Size Toggle
        const fontSizeBtn = document.getElementById('fontSizeBtn');
        const fontSizeMenu = document.getElementById('fontSizeMenu');
        
        fontSizeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fontSizeMenu.style.display = fontSizeMenu.style.display === 'none' ? 'block' : 'none';
        });
        
        document.addEventListener('click', () => {
            fontSizeMenu.style.display = 'none';
            document.getElementById('userMenu').style.display = 'none';
        });
        
        // User Menu Toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');
        
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.style.display = userMenu.style.display === 'none' ? 'block' : 'none';
            fontSizeMenu.style.display = 'none';
        });
        
        // Load saved font size
        const savedFontSize = localStorage.getItem('fontSize') || 'medium';
        html.setAttribute('data-font-size', savedFontSize);
        
        function changeFontSize(size) {
            html.setAttribute('data-font-size', size);
            localStorage.setItem('fontSize', size);
            fontSizeMenu.style.display = 'none';
        }
        
        // Global Search
        const searchModal = document.getElementById('searchModal');
        const globalSearchBtn = document.getElementById('globalSearchBtn');
        const searchInput = document.getElementById('globalSearchInput');
        const searchResults = document.getElementById('searchResults');
        
        globalSearchBtn.addEventListener('click', () => {
            searchModal.classList.add('active');
            searchInput.focus();
        });
        
        searchModal.addEventListener('click', (e) => {
            if (e.target === searchModal) {
                searchModal.classList.remove('active');
                searchInput.value = '';
                searchResults.innerHTML = '<p style="text-align: center; color: var(--text-light); padding: 20px;">Type to search...</p>';
            }
        });
        
        // ESC key to close search
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchModal.classList.contains('active')) {
                searchModal.classList.remove('active');
                searchInput.value = '';
            }
        });
        
        // Search functionality
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                searchResults.innerHTML = '<p style="text-align: center; color: var(--text-light); padding: 20px;">Type at least 2 characters...</p>';
                return;
            }
            
            searchResults.innerHTML = '<p style="text-align: center; color: var(--text-light); padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Searching...</p>';
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 500);
        });
        
        function performSearch(query) {
            // Simulate search (replace with actual API call)
            fetch(`/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                searchResults.innerHTML = '<p style="text-align: center; color: var(--text-light); padding: 20px;">No results found</p>';
            });
        }
        
        function displaySearchResults(results) {
            if (results.length === 0) {
                searchResults.innerHTML = '<p style="text-align: center; color: var(--text-light); padding: 20px;">No results found</p>';
                return;
            }
            
            let html = '';
            results.forEach(result => {
                html += `
                    <div class="search-result-item" onclick="window.location.href='${result.url}'">
                        <div class="search-result-title">${result.title}</div>
                        <div class="search-result-type">${result.type}</div>
                    </div>
                `;
            });
            searchResults.innerHTML = html;
        }
        
        // Keyboard shortcut: Ctrl+K for search
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchModal.classList.add('active');
                searchInput.focus();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
