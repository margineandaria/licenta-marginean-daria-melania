@extends('layouts.app')

@section('title', 'Adaugă Membru - SmartPocket')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --text-dark: #1E293B;
        --text-muted: #64748B;
        --brand-purple: #8B5CF6;
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

    .alert-danger {
        background-color: var(--pastel-red-bg);
        color: var(--pastel-red-text);
        padding: 16px 24px;
        border-radius: 16px;
        margin-bottom: 30px;
        font-weight: 600;
    }

    .alert-danger ul { margin: 0; padding-left: 20px; }

    .form-group {
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-dark);
    }

    .form-control {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-dark);
        padding: 14px 16px;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        background-color: #F8FAFC;
        transition: all 0.2s;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--brand-purple);
        background-color: #FFFFFF;
    }

    .form-note {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: -4px;
        font-weight: 500;
    }

    .btn-submit {
        background-color: var(--brand-purple);
        color: #FFFFFF;
        padding: 16px 24px;
        border-radius: 12px;
        font-size: 1.05rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s;
        width: 100%;
        margin-top: 10px;
    }

    .btn-submit:hover { opacity: 0.9; }

    .link-cancel {
        display: block;
        text-align: center;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        margin-top: 20px;
    }

    .link-cancel:hover { color: var(--text-dark); }
</style>

<div class="finance-dashboard">
    <div class="section-band">
        <div class="content-wrapper">
            <div class="form-card">
                <div class="section-title">Adaugă un Membru Nou</div>
                <div class="section-subtitle">Completează detaliile pentru a-i crea un cont în familia ta.</div>

                @if($errors->any())
                    <div class="alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('family.store') }}" novalidate>
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Nume complet:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex: Maria Popescu" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Adresă de Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="maria@exemplu.ro" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rol în familie:</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Alege un rol --</option>
                            <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Părinte (Drepturi depline)</option>
                            <option value="child" {{ old('role') == 'child' ? 'selected' : '' }}>Copil (Acces limitat la propriile cheltuieli)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Parolă:</label>
                        <input type="password" name="password" class="form-control" placeholder="Minim 8 caractere" required>
                        <div class="form-note">Noul membru va putea folosi această parolă pentru a se loga.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmă Parola:</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Scrie parola din nou" required>
                    </div>

                    <button type="submit" class="btn-submit">Salvează Membrul</button>
                    <a href="{{ route('family.index') }}" class="link-cancel">Anulează și întoarce-te la listă</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection