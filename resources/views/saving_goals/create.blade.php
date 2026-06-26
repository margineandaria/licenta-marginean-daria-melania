@extends('layouts.app')
@section('title', 'Setează Obiectiv - SmartPocket')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --text-dark: #1E293B;
        --text-muted: #64748B;
        --brand-purple: #8B5CF6;
        --brand-purple-light: #EDE9FE;
        --pastel-red-bg: #FCE7F3;
        --pastel-red-text: #DB2777;
        --bg-band-cream: #FFFBF0;
    }

    .finance-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background-color: var(--bg-band-cream);
    }

    .section-band { padding: 60px 20px; }
    .content-wrapper { max-width: 600px; margin: 0 auto; }

    .form-card {
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
    }

    .section-title {
        font-weight: 800; 
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .section-subtitle {
        color: var(--text-muted);
        font-size: 1.05rem;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .profile-alert {
        background-color: var(--brand-purple-light);
        border-left: 5px solid var(--brand-purple);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    .profile-alert h4 { color: var(--brand-purple); margin: 0 0 5px 0; font-weight: 800; font-size: 1rem; }
    .profile-alert p { margin: 0; font-size: 0.9rem; font-weight: 600; line-height: 1.4; color: var(--text-dark); }
    .profile-alert a { color: var(--brand-purple); font-weight: 800; text-decoration: underline; }

    .alert-danger {
        background-color: var(--pastel-red-bg);
        color: var(--pastel-red-text);
        padding: 16px 24px;
        border-radius: 16px;
        margin-bottom: 30px;
        font-weight: 600;
    }

    .form-group { margin-bottom: 24px; display: flex; flex-direction: column; gap: 8px; }
    .form-label { font-weight: 700; font-size: 0.95rem; color: var(--text-dark); }
    .form-control {
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 500;
        padding: 14px 16px; border: 2px solid #E2E8F0; border-radius: 12px;
        background-color: #F8FAFC; outline: none;
    }
    .form-control:focus { border-color: var(--brand-purple); background-color: #FFFFFF; }

    .btn-submit {
        background-color: var(--brand-purple); color: #FFFFFF; padding: 16px 24px;
        border-radius: 12px; font-size: 1.05rem; font-weight: 700; border: none; cursor: pointer;
        width: 100%; margin-top: 10px; transition: 0.2s;
    }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
</style>

<div class="finance-dashboard">
    <div class="section-band">
        <div class="content-wrapper">
            <div class="form-card">
                <div class="section-title">Adaugă Obiectiv</div>
                @if($errors->any())
                    <div class="alert-danger">
                        <ul style="margin:0; padding-left:20px;">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                @if($isProfileIncomplete)
                    <div class="profile-alert">
                        <h4>!!Activează funcțiile Smart!</h4>
                        <p>
                            Pentru a primi Smart Tips bazate pe profilul tău, 
                            completează datele în <a href="{{ route('profile.index') }}">Profilul Meu</a>.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('saving-goals.store') }}" novalidate>
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nume Obiectiv (ex: Vacanță):</label>
                        <input type="text" name="goal_name" class="form-control" placeholder="Ce vrei să realizezi?" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Suma Țintă (RON):</label>
                        <input type="number" step="0.01" name="target_amount" class="form-control" placeholder="Suma totală" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Suma deja strânsă (RON):</label>
                        <input type="number" step="0.01" name="current_amount" class="form-control" value="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Data Limită:</label>
                        <input type="date" name="target_date" class="form-control" required>
                    </div>

                    <button type="submit" class="btn-submit">Salvează Obiectivul</button>
                    <a href="{{ route('saving-goals.index') }}" style="display:block; text-align:center; margin-top:20px; color:var(--text-muted); text-decoration:none; font-weight:700;">Renunță</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection