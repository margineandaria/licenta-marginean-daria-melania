<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creează Cont - SmartPocket</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-purple: #8B5CF6;
            --brand-purple-hover: #7C3AED;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --bg-body: #FFFBF0; 
            --bg-white: #FFFFFF;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-card {
            background-color: var(--bg-white);
            width: 100%;
            max-width: 500px; 
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            border: 1px solid #E2E8F0;
        }

        .auth-header { text-align: center; margin-bottom: 30px; }
        .logo-icon { color: var(--brand-purple); font-size: 2rem; margin-bottom: 10px; }
        .auth-header h2 { font-weight: 800; font-size: 1.5rem; color: var(--text-dark); }
        .auth-header p { color: var(--text-muted); font-size: 0.95rem; font-weight: 500; margin-top: 5px; }

        .form-divider {
            border: none; border-top: 2px dashed #E2E8F0; margin: 25px 0;
        }
        
        .section-title { font-size: 0.85rem; font-weight: 800; color: var(--brand-purple); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px; }

        .form-group { margin-bottom: 16px; display: flex; flex-direction: column; gap: 8px; }
        .form-label { font-weight: 700; font-size: 0.9rem; color: var(--text-dark); }
        
        .form-control {
            font-family: inherit; font-size: 0.95rem; font-weight: 500;
            color: var(--text-dark); padding: 14px 16px; border: 2px solid #E2E8F0;
            border-radius: 12px; background-color: #F8FAFC; outline: none; transition: 0.2s; width: 100%;
        }
        .form-control:focus { border-color: var(--brand-purple); background-color: var(--bg-white); }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }

        .btn-submit {
            background-color: var(--brand-purple); color: white; padding: 14px;
            border-radius: 12px; font-size: 1rem; font-weight: 700; border: none;
            width: 100%; cursor: pointer; transition: 0.2s; margin-top: 10px;
        }
        .btn-submit:hover { background-color: var(--brand-purple-hover); }

        .alert-danger {
            background-color: #FEF2F2; color: #DC2626; padding: 15px;
            border-radius: 10px; font-size: 0.85rem; font-weight: 600; margin-bottom: 20px;
            border: 1px solid #FECACA;
        }
        .alert-danger ul { margin: 0; padding-left: 20px; }

        .auth-footer { text-align: center; margin-top: 25px; font-size: 0.9rem; font-weight: 600; color: var(--text-muted); }
        .auth-footer a { color: var(--brand-purple); text-decoration: none; transition: 0.2s; }
        .auth-footer a:hover { color: var(--brand-purple-hover); text-decoration: underline; }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-header">
            <i class="fas fa-users logo-icon"></i>
            <h2>Începe cu SmartPocket</h2>
            <p>Creează mediul financiar al familiei tale</p>
        </div>

        @if($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="family_name" class="form-label">Numele Familiei</label>
                <input type="text" class="form-control" id="family_name" name="family_name" value="{{ old('family_name') }}" placeholder="ex: Familia Popescu"  autofocus>
            </div>

            <hr class="form-divider">
            <div class="section-title">Datele Părintelui (Administrator)</div>

            <div class="form-group">
                <label for="name" class="form-label">Numele Tău</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Ion Popescu">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresă de Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="ion@email.com" >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Parolă</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Minim 8 caractere" >
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmă Parola</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repetă parola" >
                </div>
            </div>

            <button type="submit" class="btn-submit">Creează Contul</button>
        </form>

        <div class="auth-footer">
            Ai deja cont? <a href="{{ route('login') }}">Loghează-te aici</a>
        </div>

        <div style="text-align: center; margin-top: 15px;">
            <a href="/" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none; font-weight: 500;">&larr; Înapoi la pagina principală</a>
        </div>
    </div>

</body>
</html>