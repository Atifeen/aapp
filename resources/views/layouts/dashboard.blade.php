<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'AAPP - Academic Portal')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-bg: #0f172a;
            --secondary-bg: #1e293b;
            --card-bg: rgba(30, 41, 59, 0.7);
            --accent-color: #3b82f6;
            --success-color: #22c55e;
            --warning-color: #eab308;
            --info-color: #0ea5e9;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
        }

        body {
            background: linear-gradient(135deg, var(--primary-bg) 0%, var(--secondary-bg) 100%);
            min-height: 100vh;
            color: var(--text-primary);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Navbar Styles */
        .navbar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: var(--accent-color) !important;
        }

        /* Card Styles */
        .welcome-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: var(--text-primary);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            color: var(--text-secondary);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        /* Stats and Icons */
        .stat-icon {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            font-size: 2.5rem;
            opacity: 0.15;
            transition: opacity 0.2s;
        }

        .dashboard-card:hover .stat-icon {
            opacity: 0.3;
        }

        .display-6 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Action Buttons */
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--accent-color);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .btn-success {
            background: var(--success-color);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3);
        }

        .btn-info {
            background: var(--info-color);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.3);
            color: white;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            padding: 0.5rem;
        }

        .dropdown-item {
            color: var(--text-primary);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin: 0.25rem 0;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }

        .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 0.5rem 0;
        }

        /* Section Headers */
        h4 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        /* Quick Action Cards */
        .quick-action-card {
            text-align: center;
            padding: 2rem;
        }

        .quick-action-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .quick-action-card h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .quick-action-card p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    @yield('content')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>