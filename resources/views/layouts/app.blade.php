<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartPocket - Consultantul financiar al familiei tale')</title>

  
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-purple: #8B5CF6;
            --brand-purple-light: #EDE9FE;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --bg-page: #FFFBF0; 
            --white: #FFFFFF;
            --border: #E2E8F0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-main {
            background-color: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container-nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 800;
            font-size: 1.2rem;
        }

        .logo-box {
            background-color: var(--brand-purple);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-links-desktop {
            display: flex;
            gap: 5px;
            height: 100%;
        }

        .nav-link-item {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            padding: 0 15px;
            border-bottom: 3px solid transparent;
            transition: 0.2s;
        }

        .nav-link-item:hover, .nav-link-item.active {
            color: var(--brand-purple);
            background-color: #F8FAFC;
        }

        .nav-link-item.active {
            border-bottom-color: var(--brand-purple);
        }

        .btn-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--brand-purple);
            font-size: 1.4rem;
            cursor: pointer;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--brand-purple-light);
            color: var(--brand-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .btn-logout {
            background: none;
            border: 1px solid var(--border);
            padding: 7px 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-logout:hover {
            color: #DC2626;
            background-color: #FEF2F2;
        }

        .mobile-dropdown {
            display: none;
            background-color: var(--white);
            border-bottom: 1px solid var(--border);
            flex-direction: column;
            padding: 10px 20px 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .mobile-dropdown.is-open {
            display: flex;
        }

        .mobile-link {
            padding: 12px 15px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 10px;
            margin-bottom: 4px;
        }

        .mobile-link:hover {
            background-color: var(--brand-purple-light);
            color: var(--brand-purple);
        }

        @media (max-width: 992px) {
            .nav-links-desktop { display: none; }
            .btn-hamburger { display: block; }
            .user-name-text { display: none; } 
        }

        main { flex: 1; width: 100%; }

        .footer-main {
            padding: 25px;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    @auth
    <header class="navbar-main">
        <div class="container-nav">
            
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="btn-hamburger" id="hamburgerBtn">
                    <i class="fas fa-bars"></i>
                </button>

                <a href="{{ auth()->user()->role === 'admin' ? route('admin.index') : route('dashboard') }}" class="logo-area">
                    <div class="logo-box"><i class="fas fa-wallet"></i></div>
                    <span>SmartPocket</span>
                </a>
            </div>

            <nav class="nav-links-desktop">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="nav-link-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">Panou Control</a>
                @else
                    <a href="{{ route('dashboard') }}" class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('transactions.index') }}" class="nav-link-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">Tranzacții</a>
                    <a href="{{ route('budgets.index') }}" class="nav-link-item {{ request()->routeIs('budgets.*') ? 'active' : '' }}">Bugete</a>
                    <a href="{{ route('saving-goals.index') }}" class="nav-item nav-link-item {{ request()->routeIs('saving-goals.*') ? 'active' : '' }}">Obiective/Economii</a>
                    @if(auth()->user()->role === 'parent')
                        <a href="{{ route('family.index') }}" class="nav-link-item {{ request()->routeIs('family.*') ? 'active' : '' }}">Familie</a>
                    @endif
                @endif
            </nav>

            <div class="user-section">
                <a href="{{ route('profile.index') }}" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <div class="avatar-circle">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="user-name-text" style="font-weight: 700; font-size: 0.85rem; color: var(--text-dark);">{{ auth()->user()->name }}</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout" title="Ieșire">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>

        </div>

        <div class="mobile-dropdown" id="mobileMenu">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.index') }}" class="mobile-link">Panou Control</a>
            @else
                <a href="{{ route('dashboard') }}" class="mobile-link">Dashboard</a>
                <a href="{{ route('transactions.index') }}" class="mobile-link">Tranzacții</a>
                <a href="{{ route('budgets.index') }}" class="mobile-link">Bugete</a>
                <a href="{{ route('saving-goals.index') }}" class="mobile-link">Obiective/Economii</a>
                @if(auth()->user()->role === 'parent')
                    <a href="{{ route('family.index') }}" class="mobile-link">Familia Mea</a>
                @endif
            @endif
            <hr style="border: 0; border-top: 1px solid var(--border); margin: 10px 0;">
            <a href="{{ route('profile.index') }}" class="mobile-link">Profilul Meu</a>
        </div>
    </header>
    @endauth

    <main>
        @yield('content')
    </main>

    <footer class="footer-main">
        <p>&copy; {{ date('Y') }} SmartPocket. Proiect de Licență.</p>
    </footer>

    <script>
        const btn = document.getElementById('hamburgerBtn');
        const menu = document.getElementById('mobileMenu');

        if(btn) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('is-open');
                
                const icon = btn.querySelector('i');
                if(menu.classList.contains('is-open')) {
                    icon.classList.replace('fa-bars', 'fa-times');
                } else {
                    icon.classList.replace('fa-times', 'fa-bars');
                }
            });
        }
    </script>

</body>
</html>