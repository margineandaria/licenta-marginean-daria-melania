@extends('layouts.app')

@section('title', 'Familia mea - SmartPocket')

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
        --pastel-red-bg: #FCE7F3;
        --pastel-red-text: #DB2777;
        --bg-band-white: #FFFFFF;
        --bg-band-gray: #F8FAFC;
    }

    .finance-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background-color: var(--bg-band-gray);
    }

    .section-band { padding: 60px 20px; }
    .section-band.white { background-color: var(--bg-band-white); }
    .section-band.gray { background-color: var(--bg-band-gray); }

    .content-wrapper { max-width: 1200px; margin: 0 auto; }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header-bar h3 {
        margin: 0;
        font-weight: 800;
        font-size: 2rem;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .header-subtitle {
        color: var(--text-muted);
        font-size: 1.05rem;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .btn-primary {
        background-color: var(--brand-purple);
        color: #FFFFFF;
        padding: 12px 24px;
        border-radius: 30px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s;
        display: inline-block;
    }

    .btn-primary:hover { opacity: 0.9; }

    .alert-success { background-color: var(--pastel-green-bg); color: var(--pastel-green-text); padding: 16px 24px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; }
    .alert-danger { background-color: var(--pastel-red-bg); color: var(--pastel-red-text); padding: 16px 24px; border-radius: 16px; margin-bottom: 30px; font-weight: 600; }

    .members-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .member-card {
        background-color: #FFFFFF;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        border: 1px solid #F1F5F9;
        position: relative;
        transition: transform 0.2s ease;
    }

    .member-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
    }

    .member-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .avatar-parent { background-color: var(--brand-purple-light); color: var(--brand-purple); }
    .avatar-child { background-color: #E0F2FE; color: #0284C7; }

    .member-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0 0 4px 0;
    }

    .member-email {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin: 0 0 16px 0;
        font-weight: 500;
    }

    .role-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
    }

    .role-parent { background-color: var(--brand-purple); color: #FFFFFF; }
    .role-child { background-color: #F1F5F9; color: var(--text-muted); }

    .member-footer {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #F1F5F9;
    }

    .join-date {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .action-delete {
        color: var(--pastel-red-text);
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 6px 12px;
        border-radius: 8px;
        transition: background-color 0.2s;
    }

    .action-delete:hover {
        background-color: var(--pastel-red-bg);
    }
</style>

<div class="finance-dashboard">
    
    <div class="section-band gray">
        <div class="content-wrapper">
            <div class="header-bar">
                <h3>Membrii Familiei</h3>
                @if(auth()->user()->role === 'parent')
                    <a href="{{ route('family.create') }}" class="btn-primary">+ Adaugă Membru Nou</a>
                @endif
            </div>
            <p class="header-subtitle">Aici poți vedea și gestiona toți membrii care au acces la bugetul familiei.</p>

            @if(session('success'))
                <div class="alert-success">✔ {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert-danger">{{ session('error') }}</div>
            @endif

            <div class="members-grid">
                @foreach($members as $member)
                    <div class="member-card">
                        <div class="member-avatar {{ $member->role == 'parent' ? 'avatar-parent' : 'avatar-child' }}">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        
                        <h4 class="member-name">
                            {{ $member->name }} 
                            @if(auth()->user()->id === $member->id) <span style="color: var(--text-muted); font-weight: 500;">(Tu)</span> @endif
                        </h4>
                        
                        <p class="member-email">{{ $member->email }}</p>
                        
                        <div class="role-badge {{ $member->role == 'parent' ? 'role-parent' : 'role-child' }}">
                            {{ $member->role == 'parent' ? 'Părinte' : 'Copil' }}
                        </div>

                        <div class="member-footer">
                            <span class="join-date">Din {{ $member->created_at->format('d.m.Y') }}</span>
                            
                            @if(auth()->user()->role === 'parent')
                                @if(auth()->user()->id !== $member->id)
                                    <form action="{{ route('family.destroy', $member->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-delete" onclick="return confirm('Sigur vrei să elimini acest membru? Toate datele lui vor fi afectate.')">
                                            Elimină
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 0.85rem; color: #CBD5E1; font-weight: 600;">Admin</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

</div>
@endsection