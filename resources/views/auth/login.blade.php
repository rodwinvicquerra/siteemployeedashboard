<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Integrated Documents Employee Dashboard - SITE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #028a0f;
            --primary-dark: #026a0c;
            --primary-light: rgba(2, 138, 15, 0.65);
            --white: #ffffff;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --bg-gradient-start: #028a0f;
            --bg-gradient-end: #026a0c;
            --card-bg: #ffffff;
            --input-bg: #ffffff;
            --input-border: #e0e0e0;
        }

        /* Dark Mode */
        [data-theme="dark"] {
            --primary-color: #02b815;
            --primary-dark: #028a0f;
            --primary-light: rgba(2, 184, 21, 0.65);
            --white: #1e1e1e;
            --text-dark: #e0e0e0;
            --text-light: #b0b0b0;
            --bg-gradient-start: #0a1f0c;
            --bg-gradient-end: #051108;
            --card-bg: #2a2a2a;
            --input-bg: #1e1e1e;
            --input-border: #3a3a3a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start), var(--bg-gradient-end));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            transition: background 0.3s ease;
        }

        .login-container {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            animation: slideUp 0.6s ease;
            position: relative;
            transition: background 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 40px 30px;
            text-align: center;
            color: var(--white);
            position: relative;
        }

        .theme-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            font-size: 18px;
        }

        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
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
            padding: 14px 18px;
            border: 2px solid var(--input-border);
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
            background: var(--input-bg);
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(2, 138, 15, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(2, 138, 15, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <button class="theme-toggle" id="themeToggle" type="button">
                <i class="fas fa-moon"></i>
            </button>
            <img src="{{ asset('uploads/documents/site_logo-removebg-preview.png') }}" alt="SITE Logo" style="width: 80px; height: 80px; margin-bottom: 15px; object-fit: contain; background: white; padding: 4px; border-radius: 50%; box-shadow: 0 6px 15px rgba(0,0,0,0.3); border: 2px solid rgba(255,255,255,0.9);">
            <h1 style="font-size: 22px; line-height: 1.3; margin-bottom: 8px;">Integrated Documents Employee Dashboard</h1>
            <p style="font-size: 13px; margin-bottom: 5px;">School of Information Technology and Engineering (SITE)</p>
            <p style="font-size: 13px; opacity: 0.9;">Sign in to continue</p>
        </div>
        <div class="login-body">
            @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-icon">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Sign In
                </button>
            </form>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
        
        function updateThemeIcon(theme) {
            const icon = themeToggle.querySelector('i');
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    </script>
</body>
</html>
