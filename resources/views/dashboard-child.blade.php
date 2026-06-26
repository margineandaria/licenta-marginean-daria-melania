@extends('layouts.app')

@section('title', 'Dashboard - SmartPocket')

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
        --pastel-green-text: #059669;
        --pastel-red-text: #DB2777;
        --pastel-orange-text: #D97706;
        --bg-band-white: #FFFFFF;
        --bg-band-cream: #FFFBF0;
        --bg-band-gray: #F8FAFC;
    }

    .finance-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
        margin: 0;
        padding: 0;
    }

    .section-band { padding: 40px 20px; }
    .section-band.white { background-color: var(--bg-band-white); }
    .section-band.cream { background-color: var(--bg-band-cream); }
    .section-band.gray { background-color: var(--bg-band-gray); }

    .content-wrapper { max-width: 1100px; margin: 0 auto; }

  
    .welcome-card {
        background-color: var(--brand-purple-light);
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 20px;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .welcome-card h2 { margin: 0 0 8px 0; font-weight: 800; color: var(--brand-purple); font-size: 1.8rem; }
    .welcome-card p { margin: 0; color: var(--text-dark); font-weight: 500; opacity: 0.8; }

    .alert-success { background-color: #D1FAE5; color: #065F46; padding: 16px 24px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; }

    .child-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 30px; }
    @media (max-width: 992px) { .child-grid { grid-template-columns: 1fr; } }

    .soft-card {
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        height: fit-content;
    }

    .card-title { font-weight: 800; font-size: 1.25rem; margin-bottom: 20px; color: var(--text-dark); }

    .pocket-money-info { margin-bottom: 24px; }
    .money-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 1rem; font-weight: 600; color: var(--text-muted); }
    .money-row strong { color: var(--text-dark); font-weight: 800; }
    .money-main { font-size: 2.2rem; font-weight: 800; color: var(--pastel-green-text); letter-spacing: -1px; margin: 10px 0; }

    .progress-container { margin-top: 20px; }
    .progress-label { font-weight: 700; font-size: 0.9rem; margin-bottom: 10px; display: block; }
    .progress-bar-bg { background-color: #F1F5F9; height: 16px; border-radius: 10px; overflow: hidden; position: relative; }
    .progress-bar-fill { height: 100%; border-radius: 10px; transition: width 0.6s ease; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; color: white; }

    .btn-action {
        display: block;
        background-color: var(--brand-purple);
        color: white;
        text-align: center;
        padding: 18px;
        text-decoration: none;
        font-weight: 800;
        border-radius: 16px;
        margin: 24px 0;
        transition: transform 0.2s, background-color 0.2s;
        box-shadow: 0 10px 20px -5px rgba(139, 92, 246, 0.3);
    }
    .btn-action:hover { transform: translateY(-3px); background-color: var(--brand-purple-hover); }

    .mini-goal { padding: 15px 0; border-bottom: 1px solid #F1F5F9; }
    .mini-goal:last-child { border-bottom: none; }
    .goal-name { font-weight: 700; display: block; margin-bottom: 4px; }
    .goal-progress-text { font-size: 0.85rem; color: var(--text-muted); font-weight: 600; }

    .clean-table { width: 100%; border-collapse: collapse; }
    .clean-table th { text-align: left; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); padding-bottom: 12px; border-bottom: 2px solid #F1F5F9; letter-spacing: 0.5px; }
    .clean-table td { padding: 16px 0; border-bottom: 1px solid #F8FAFC; font-weight: 600; font-size: 0.95rem; }
    .clean-table tr:last-child td { border-bottom: none; }
</style>

<div class="finance-dashboard">
    <div class="section-band white">
        <div class="content-wrapper">
            
            <div class="welcome-card">
                <h2>Salutare, {{ auth()->user()->name }}! </h2>
                <p>Aici este spațiul tău personal. Vezi câți bani de buzunar mai ai și cum îți gestionezi alocația.</p>
            </div>

            @if(session('success'))
                <div class="alert-success">✔ {{ session('success') }}</div>
            @endif

            <div class="child-grid">
                
                {{-- COLOANA 1: SITUATIA BANILOR --}}
                <div>
                    <div class="soft-card">
                        <div class="card-title">Banii tăi de buzunar</div>
                        
                        @if($buget)
                            @php 
                                $ramas = $buget->budget_amount - $totalCheltuit;
                                $procent = $buget->budget_amount > 0 ? ($totalCheltuit / $buget->budget_amount) * 100 : 0;
                                $procent = min(round($procent, 0), 100);
                                
                                $culoareBara = $procent < 75 ? 'var(--pastel-green-text)' : ($procent < 90 ? 'var(--pastel-orange-text)' : 'var(--pastel-red-text)');
                            @endphp

                            <div class="pocket-money-info">
                                <div class="money-row">Primiți luna aceasta: <span>{{ number_format($buget->budget_amount, 2) }} RON</span></div>
                                <div class="money-row">Ai cheltuit: <span style="color: var(--pastel-red-text);">{{ number_format($totalCheltuit, 2) }} RON</span></div>
                                <div style="margin-top: 20px; border-top: 2px dashed #F1F5F9; padding-top: 15px;">
                                    <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">Ți-au mai rămas:</span>
                                    <div class="money-main" style="color: {{ $ramas > 0 ? 'var(--pastel-green-text)' : 'var(--pastel-red-text)' }}">
                                        {{ number_format(max($ramas, 0), 2) }} <span style="font-size: 1rem;">RON</span>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-container">
                                <span class="progress-label">Cât ai consumat din buget:</span>
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="background-color: {{ $culoareBara }}; width: {{ $procent }}%;">
                                        @if($procent > 15) {{ $procent }}% @endif
                                    </div>
                                </div>
                            </div>

                            @if($ramas <= 0)
                                <div style="margin-top: 20px; padding: 12px; background-color: #FEF2F2; color: #991B1B; border-radius: 12px; font-weight: 700; text-align: center; font-size: 0.9rem;">
                                     Ai epuizat bugetul pe luna aceasta!
                                </div>
                            @endif
                        @else
                            <p style="color: var(--text-muted); font-weight: 500;">Părinții tăi nu au setat încă o alocație pentru această lună. Vorbește cu ei! 😊</p>
                        @endif
                    </div>

                    <a href="{{ route('transactions.create') }}" class="btn-action">
                        + Am cumpărat ceva nou
                    </a>

                    <div class="soft-card" style="margin-top: 20px;">
                        <div class="card-title">Pentru ce strângem bani?</div>
                        @forelse($obiective as $goal)
                            <div class="mini-goal">
                                <span class="goal-name">{{ $goal->goal_name }}</span>
                                <span class="goal-progress-text">
                                    <span style="color: var(--pastel-green-text)">{{ number_format($goal->current_amount, 0) }} RON</span> 
                                    din {{ number_format($goal->target_amount, 0) }} RON
                                </span>
                            </div>
                        @empty
                            <p style="color: var(--text-muted); font-size: 0.9rem;">Nu sunt obiective setate momentan.</p>
                        @endforelse
                    </div>
                </div>

                <div class="soft-card">
                    <div class="card-title">Ultimele tale cumpărături</div>
                    
                    @if($tranzactii->isEmpty())
                        <p style="color: var(--text-muted); font-weight: 500; text-align: center; padding: 40px 0;">Nu ai adăugat nicio cheltuială încă.</p>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="clean-table">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Ce ai luat?</th>
                                        <th style="text-align: right;">Suma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tranzactii as $t)
                                        <tr>
                                            <td style="color: var(--text-muted); font-size: 0.85rem;">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d.m.Y') }}</td>
                                            <td>{{ $t->description }}</td>
                                            <td style="text-align: right; color: {{ $t->type == 'expense' ? 'var(--text-dark)' : 'var(--pastel-green-text)' }}; font-weight: 800;">
                                                {{ $t->type == 'expense' ? '-' : '+' }}{{ number_format($t->amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>

@endsection