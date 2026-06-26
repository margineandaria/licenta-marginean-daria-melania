@extends('layouts.app')
@section('title', 'Profilul Meu - SmartPocket')
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
        --pastel-green-bg: #D1FAE5;
        --pastel-green-text: #059669;
        --bg-band-cream: #FFFBF0;
    }

    .finance-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
        margin: 0; padding: 0; min-height: 100vh;
        background-color: var(--bg-band-cream);
    }

    .section-band { padding: 60px 20px; }
    .content-wrapper { max-width: 900px; margin: 0 auto; }

    .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .header-bar h3 { margin: 0; font-weight: 800; font-size: 2rem; color: var(--text-dark); letter-spacing: -0.5px; }
    
    .btn-primary {
        background-color: var(--brand-purple); color: #FFFFFF;
        padding: 12px 24px; border-radius: 12px; font-size: 1rem; font-weight: 700;
        text-decoration: none; transition: opacity 0.2s;
    }
    .btn-primary:hover { opacity: 0.9; }

    .alert-success { background-color: var(--pastel-green-bg); color: var(--pastel-green-text); padding: 16px 24px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; }

    .profile-card {
        background-color: #FFFFFF; border-radius: 24px; padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #F8FAFC;
    }

    .profile-header {
        display: flex; align-items: center; gap: 24px; margin-bottom: 40px;
        padding-bottom: 30px; border-bottom: 2px solid #F1F5F9;
    }

    .profile-avatar {
        width: 80px; height: 80px; border-radius: 50%;
        background-color: var(--brand-purple-light); color: var(--brand-purple);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800;
    }

    .profile-name { font-size: 1.5rem; font-weight: 800; margin: 0 0 8px 0; }
    .profile-email { color: var(--text-muted); font-size: 1rem; margin: 0 0 12px 0; font-weight: 500;}
    
    .role-badge {
        display: inline-block; background-color: #F1F5F9; color: var(--text-muted);
        padding: 6px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 700;
    }
    .role-badge.parent { background-color: var(--pastel-green-bg); color: var(--pastel-green-text); }

    .info-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;
    }

    .info-item { display: flex; flex-direction: column; gap: 6px; }
    .info-label { font-size: 0.85rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); }
</style>

<div class="finance-dashboard">
    <div class="section-band">
        <div class="content-wrapper">
            
            <div class="header-bar">
                <h3>Profilul Meu</h3>
                <a href="{{ route('profile.edit') }}" class="btn-primary">Editează Profilul</a>
            </div>

            @if(session('success'))
                <div class="alert-success">✔ {{ session('success') }}</div>
            @endif

            <div class="profile-card">
                
                <div class="profile-header">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="profile-name">{{ $user->name }}</h4>
                        <p class="profile-email">{{ $user->email }}</p>
                        <div class="role-badge {{ $user->role == 'parent' ? 'parent' : '' }}">
                            {{ $user->role == 'parent' ? 'Părinte (Admin)' : 'Copil (Membru)' }}
                        </div>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nivel Educație</span>
                        <span class="info-value">{{ $user->education_level ?? 'Nespecificat' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Domeniu de Activitate</span>
                        <span class="info-value">{{ $user->work_domain ?? 'Nespecificat' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Categorie Vârstă</span>
                        <span class="info-value">{{ $user->age_category ? $user->age_category . ' ani' : 'Nespecificat' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Statut Locuință</span>
                        <span class="info-value">{{ $user->housing_status ?? 'Nespecificat' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Zonă Geografică</span>
                        <span class="info-value">{{ $user->geographic_zone ?? 'Nespecificat' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Membru din</span>
                        <span class="info-value">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection