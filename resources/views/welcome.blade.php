<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPocket - Consultantul financiar al familiei tale</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-purple: #8B5CF6;
            --brand-purple-hover: #7C3AED;
            --brand-purple-light: #EDE9FE;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --bg-body: #FFFBF0; 
            --bg-white: #FFFFFF;
            --border-color: #E2E8F0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 90px;
        }

        .logo {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.5px;
        }

        .logo-icon {
            background: var(--brand-purple);
            color: white;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.1rem;
        }

        .nav-actions { display: flex; gap: 15px; align-items: center; }

        .btn {
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .btn-ghost { color: var(--text-dark); }
        .btn-ghost:hover { color: var(--brand-purple); background-color: rgba(139, 92, 246, 0.05); }

        .btn-primary {
            background-color: var(--brand-purple);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(139, 92, 246, 0.3);
        }
        .btn-primary:hover {
            background-color: var(--brand-purple-hover);
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(139, 92, 246, 0.4);
        }

        .hero {
            padding: 80px 0 100px 0;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 24px;
            letter-spacing: -1.5px;
            color: var(--text-dark);
        }

        .hero h1 span { color: var(--brand-purple); }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 40px;
            font-weight: 500;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .features-section { padding: 0 0 100px 0; }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--bg-white);
            padding: 40px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.08);
            border-color: var(--brand-purple-light);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: var(--brand-purple-light);
            color: var(--brand-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 1.5rem;
            margin-bottom: 24px;
        }

        .feature-card h3 {
            font-weight: 800;
            font-size: 1.25rem;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        .feature-card p {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .bottom-cta {
            background-color: var(--text-dark);
            border-radius: 40px;
            padding: 70px 40px;
            text-align: center;
            color: white;
            margin-bottom: 80px;
        }

        .bottom-cta h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 16px; letter-spacing: -0.5px;}
        .bottom-cta p { font-size: 1.1rem; color: #94A3B8; margin-bottom: 32px; font-weight: 500;}

        footer {
            padding: 40px 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            border-top: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero-buttons { flex-direction: column; }
            .btn { text-align: center; width: 100%; }
            .bottom-cta { padding: 50px 20px; }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <header class="header">
            <a href="/" class="logo">
                <div class="logo-icon"><i class="fas fa-wallet"></i></div>
                SmartPocket
            </a>
            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Mergi la Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Intră în cont</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Creează cont</a>
                @endauth
            </div>
        </header>

        <section class="hero">
            <h1>O aplicație pentru <br><span>bugetul familiei tale.</span></h1>
            <p>Ține evidența cheltuielilor, gestionează banii de buzunar ai copiilor și primește sfaturi simple pentru a atinge obiectivele de economisire.</p>
            
            <div class="hero-buttons">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 16px 36px; font-size: 1.05rem;">Creează cont</a>
                @endguest
            </div>
        </section>

        <section id="functionalitati" class="features-section">
            <div class="features-grid">
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-list-ul"></i></div>
                    <h3>Evidența cheltuielilor</h3>
                    <p>Adaugi cheltuielile zilnice în aplicație și poți vedea exact pe ce s-au dus banii la finalul fiecărei luni.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-child"></i></div>
                    <h3>Conturi pentru copii</h3>
                    <p>Copiii au propriul ecran unde pot vedea câți bani de buzunar mai au disponibili și își pot nota ce și-au cumpărat.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3>Obiective și sfaturi</h3>
                    <p>Setați obiective pentru lucrurile pe care vi le doriți, iar aplicația vă va oferi sfaturi pentru a ajunge la ele.</p>
                </div>

            </div>
        </section>

        <section class="bottom-cta">
            <h2>Începe să folosești SmartPocket</h2>
            <p>Adaugă membrii familiei și notează primele cheltuieli.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary" style="background-color: var(--brand-purple); padding: 16px 40px; font-size: 1.1rem; border: 2px solid var(--brand-purple);">Înregistrează-te</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary" style="background-color: var(--brand-purple); padding: 16px 40px; font-size: 1.1rem; border: 2px solid var(--brand-purple);">Înapoi la panoul tău</a>
            @endguest
        </section>

        <footer>
            <p>&copy; {{ date('Y') }} SmartPocket. Proiect de Licență.</p>
        </footer>

    </div>

</body>
</html>