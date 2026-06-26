@extends('layouts.app')
@section('title', 'Editează Profil - SmartPocket')
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
        --brand-purple-hover: #7C3AED;
        --pastel-red-bg: #FCE7F3;
        --pastel-red-text: #DB2777;
        --bg-band-cream: #FFFBF0;
    }

    .finance-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
        margin: 0; padding: 0; min-height: 100vh;
        background-color: var(--bg-band-cream);
    }

    .section-band { padding: 60px 20px; }
    .content-wrapper { max-width: 1100px; margin: 0 auto; }

    .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .header-bar h3 { margin: 0; font-weight: 800; font-size: 2rem; color: var(--text-dark); letter-spacing: -0.5px; }

    .link-cancel { color: var(--text-muted); text-decoration: none; font-weight: 700; font-size: 1rem; transition: color 0.2s;}
    .link-cancel:hover { color: var(--text-dark); }

    .alert-danger { background-color: var(--pastel-red-bg); color: var(--pastel-red-text); padding: 16px 24px; border-radius: 16px; margin-bottom: 30px; font-weight: 600; }
    .alert-danger ul { margin: 0; padding-left: 20px; }

    .profile-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
    @media (max-width: 992px) { .profile-layout { grid-template-columns: 1fr; } }

    .settings-stack { display: flex; flex-direction: column; gap: 20px; }

    .form-card { background-color: #FFFFFF; border-radius: 24px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #F8FAFC; }
    .section-title { font-weight: 800; font-size: 1.35rem; margin-bottom: 8px; color: var(--text-dark); letter-spacing: -0.5px; }
    .section-subtitle { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 24px; font-weight: 500; padding-bottom: 16px; border-bottom: 2px solid #F1F5F9;}

    .form-row { display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;}
    .form-row .form-group { flex: 1; min-width: 200px; margin-bottom: 0;}

    .form-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
    .form-label { font-weight: 700; font-size: 0.9rem; color: var(--text-dark); }

    .form-control {
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.95rem; font-weight: 500;
        color: var(--text-dark); padding: 12px 16px; border: 2px solid #E2E8F0; border-radius: 12px;
        background-color: #F8FAFC; transition: all 0.2s; outline: none; width: 100%; box-sizing: border-box;
    }
    .form-control:focus { border-color: var(--brand-purple); background-color: #FFFFFF; }
    .form-control[readonly] { background-color: #E2E8F0; color: var(--text-muted); cursor: not-allowed; border-color: #E2E8F0; }

    .btn-submit {
        background-color: var(--brand-purple); color: #FFFFFF; padding: 14px 24px;
        border-radius: 12px; font-size: 1rem; font-weight: 700; border: none; cursor: pointer; transition: 0.2s; width: 100%; margin-top: 10px;
    }
    .btn-submit:hover { background-color: var(--brand-purple-hover); }

    .btn-secondary {
        background-color: #F1F5F9; color: var(--text-dark); padding: 14px 24px; border-radius: 12px;
        font-size: 1rem; font-weight: 700; border: 1px solid #E2E8F0; cursor: pointer; transition: 0.2s; width: 100%; display: block;
    }
    .btn-secondary:hover { background-color: #E2E8F0; }

    .info-card { background-color: #F8FAFC; border-radius: 24px; padding: 32px; border: 1px solid #E2E8F0; height: fit-content; }
    .info-card h4 { margin: 0 0 20px 0; font-weight: 800; font-size: 1.25rem; color: var(--text-dark); }
    
    .ai-info-box { background-color: #FFFFFF; padding: 16px; border-radius: 16px; border-left: 4px solid var(--brand-purple); }
    .ai-info-box h5 { margin: 0 0 8px 0; color: var(--brand-purple); font-weight: 800; font-size: 0.95rem;}
    .ai-info-box p { margin: 0; font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; font-weight: 500;}

    #passwordFormContainer { display: none; animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="finance-dashboard">
    <div class="section-band">
        <div class="content-wrapper">
            
            <div class="header-bar">
                <h3>Editează Profilul</h3>
                <a href="{{ route('profile.index') }}" class="link-cancel">Anulează</a>
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

            <div class="profile-layout">
                
                <div class="settings-stack">
                    <form method="POST" action="{{ route('profile.update') }}" class="form-card" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="section-title">Identitate</div>
                        <div class="section-subtitle">Datele tale de bază pentru accesul în platformă.</div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nume Complet:</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Adresă de Email:</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" 
                                       {{ $user->role !== 'parent' ? 'readonly' : 'required' }}>
                                @if($user->role !== 'parent')
                                    <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 4px; font-weight: 600;">
                                        Doar administratorul familiei poate modifica email-ul.
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="section-title" style="margin-top: 20px;">Detalii Financiare & Demografice</div>
                        <div class="section-subtitle">Aceste date ne ajută să îți oferim sfaturi financiare personalizate.</div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nivel Educație:</label>
                                <select name="education_level" class="form-control">
                                    <option value="Liceu" {{ old('education_level', $user->education_level) == 'Liceu' ? 'selected' : '' }}>Liceu</option>
                                    <option value="Facultate" {{ old('education_level', $user->education_level) == 'Facultate' ? 'selected' : '' }}>Facultate</option>
                                    <option value="Master" {{ old('education_level', $user->education_level) == 'Master' ? 'selected' : '' }}>Master</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Categorie Vârstă:</label>
                                <select name="age_category" class="form-control">
                                    <option value="20-30" {{ old('age_category', $user->age_category) == '20-30' ? 'selected' : '' }}>20-30 ani</option>
                                    <option value="30-45" {{ old('age_category', $user->age_category) == '30-45' ? 'selected' : '' }}>30-45 ani</option>
                                    <option value="45+" {{ old('age_category', $user->age_category) == '45+' ? 'selected' : '' }}>Peste 45 ani</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Domeniu de Activitate:</label>
                            <select name="work_domain" class="form-control">
                                @foreach(['IT & Software', 'Sanatate / Medical', 'Educatie', 'Horeca / Turism', 'Vanzari / Retail', 'Administratie Publica', 'Finante / Banci', 'Constructii / Imobiliare', 'Transport / Logistica', 'Productie / Inginerie', 'Marketing / Media / PR', 'Agricultura', 'Servicii Diverse'] as $domeniu)
                                    <option value="{{ $domeniu }}" {{ old('work_domain', $user->work_domain) == $domeniu ? 'selected' : '' }}>{{ $domeniu }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Statut Locuință:</label>
                                <select name="housing_status" class="form-control">
                                    <option value="Proprietar" {{ old('housing_status', $user->housing_status) == 'Proprietar' ? 'selected' : '' }}>Proprietar</option>
                                    <option value="Chirie" {{ old('housing_status', $user->housing_status) == 'Chirie' ? 'selected' : '' }}>Chirie</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Zonă Geografică:</label>
                                <select name="geographic_zone" class="form-control">
                                    <option value="Urban" {{ old('geographic_zone', $user->geographic_zone) == 'Urban' ? 'selected' : '' }}>Urban</option>
                                    <option value="Rural" {{ old('geographic_zone', $user->geographic_zone) == 'Rural' ? 'selected' : '' }}>Rural</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">Salvează Modificările</button>
                    </form>

                    @if($user->role === 'parent')
                        <button type="button" id="btnTogglePassword" class="btn-secondary">Modifică Parola</button>

                        <div id="passwordFormContainer">
                            <form method="POST" action="{{ route('profile.password.update') }}" class="form-card" novalidate>
                                @csrf
                                @method('PUT')
                                
                                <div class="section-title">Securitate & Parolă</div>
                                <div class="section-subtitle">Alege o parolă puternică pentru a-ți proteja datele financiare.</div>

                                <div class="form-group">
                                    <label class="form-label">Parola Curentă:</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Noua Parolă:</label>
                                        <input type="password" name="password" class="form-control" placeholder="Minim 8 caractere" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Confirmă Noua Parolă:</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-submit" style="background-color: var(--text-dark);">Actualizează Parola</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="info-card">
                        <h4>De ce ne trebuie?</h4>
                        <div class="ai-info-box" style="margin-top:0;">
                            <h5>Sfaturi Inteligente</h5>
                            <p>Combinăm aceste detalii cu istoricul tău de tranzacții pentru a genera predicții financiare și sfaturi de economisire perfect adaptate situației tale.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('btnTogglePassword');
        const passwordForm = document.getElementById('passwordFormContainer');

        if (toggleBtn && passwordForm) {
            toggleBtn.addEventListener('click', function() {
                if (passwordForm.style.display === 'block') {
                    passwordForm.style.display = 'none';
                    toggleBtn.innerText = 'Modifică Parola';
                } else {
                    passwordForm.style.display = 'block';
                    toggleBtn.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection