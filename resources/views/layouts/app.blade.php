<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AAPP - Academic Portal')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Theme Variables CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme-variables.css') }}">
    
    <!-- KaTeX for LaTeX Math Rendering -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    
    <style>
        :root {
            /* Main Background Colors */
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --bg-hover: #2d3748;
            
            /* Border Colors */
            --border-primary: #475569;
            --border-secondary: #64748b;
            
            /* Text Colors */
            --text-primary: #e2e8f0;
            --text-secondary: #f1f5f9;
            --text-muted: #94a3b8;
            
            /* Accent Colors */
            --accent-green: #15803d;
            --accent-green-hover: #166534;
            --accent-green-light: #22c55e;
            
            --accent-red: #dc2626;
            --accent-red-hover: #b91c1c;
            
            --accent-blue: #3b82f6;
            --accent-yellow: #fbbf24;
            --accent-orange: #ca8a04;
            
            /* Shadow */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 15px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.3);
            
            /* Transitions */
            --transition-default: all 0.3s ease;
        }
        
        body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
        }
        
        /* Login/Register pages */
        .auth-page body {
            display: flex;
            align-items: center;
            padding-top: 0;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .auth-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .auth-header h2 {
            margin: 0;
            font-weight: 600;
        }
        .auth-body {
            padding: 40px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.4);
        }
        .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }
        .form-select:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }
        .alert {
            border-radius: 10px;
        }
    </style>
    
    @yield('styles')
</head>
<body class="{{ !auth()->check() ? 'auth-page' : '' }}">
    @include('layouts.nav')
    
    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Initialize KaTeX Auto-Render -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Wait for KaTeX to load
            const renderMath = () => {
                if (typeof renderMathInElement !== 'undefined') {
                    renderMathInElement(document.body, {
                        delimiters: [
                            {left: '$$', right: '$$', display: true},
                            {left: '$', right: '$', display: false},
                            {left: '\\(', right: '\\)', display: false},
                            {left: '\\[', right: '\\]', display: true}
                        ],
                        throwOnError: false
                    });
                }
            };
            
            // Try to render immediately, or wait a bit
            setTimeout(renderMath, 100);
        });
    </script>
    
    @yield('scripts')
</body>
</html>