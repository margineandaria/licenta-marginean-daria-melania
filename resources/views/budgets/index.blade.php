@extends('layouts.app')
@section('title', 'Bugetele Familiei - SmartPocket')
@section('content')

@php
    \Carbon\Carbon::setLocale('ro');
@endphp

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

    .section-band {
        padding: 60px 20px;
    }

    .section-band.white { background-color: var(--bg-band-white); }
    .section-band.cream { background-color: var(--bg-band-cream); }
    .section-band.gray { background-color: var(--bg-band-gray); }

    .content-wrapper {
        max-width: 1200px;
        margin: 0 auto;
    }

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

    .btn-primary:hover {
        opacity: 0.9;
    }

    .alert-success {
        background-color: var(--pastel-green-bg);
        color: var(--pastel-green-text);
        padding: 16px 24px;
        border-radius: 16px;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .section-title {
        font-weight: 800; 
        font-size: 1.5rem;
        margin-bottom: 30px;
        color: var(--text-dark);
        letter-spacing: -0.5px;
        display: inline-block;
    }

    .clean-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .clean-table th {
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        padding: 0 16px 20px 16px;
        border-bottom: 2px solid #E2E8F0;
        letter-spacing: 0.5px;
    }

    .clean-table td {
        padding: 24px 16px;
        border-bottom: 1px solid #E2E8F0;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text-dark);
        vertical-align: top;
    }

    .clean-table tr:hover td { 
        background-color: #F1F5F9; 
        border-radius: 12px;
    }

    .clean-table tr:last-child td { border-bottom: none; }

    .progress-bar-bg {
        background-color: #E2E8F0;
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
        margin: 8px 0;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 5px;
        transition: width 0.4s ease;
    }

    .action-link {
        color: var(--brand-purple);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        margin-right: 15px;
    }

    .action-delete {
        color: var(--pastel-red-text);
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 0;
        font-family: inherit;
    }

    details {
        background-color: #FFFFFF;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #E2E8F0;
    }

    summary {
        cursor: pointer;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.1rem;
        outline: none;
    }
    
    summary::marker {
        color: var(--brand-purple);
    }
</style>

<div class="finance-dashboard">

    @php
        $currentMonthStart = \Carbon\Carbon::now()->startOfMonth();
        
        $activeBudgets = $budgets->filter(function($b) use ($currentMonthStart) {
            return \Carbon\Carbon::parse($b->month_year)->startOfMonth()->gte($currentMonthStart);
        });
        
        $pastBudgets = $budgets->filter(function($b) use ($currentMonthStart) {
            return \Carbon\Carbon::parse($b->month_year)->startOfMonth()->lt($currentMonthStart);
        });
    @endphp

    <div class="section-band white">
        <div class="content-wrapper">
            <div class="header-bar">
                <h3>Bugetele Familiei</h3>
                <a href="{{ route('budgets.create') }}" class="btn-primary">+ Setează Buget Nou</a>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    ✔ {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <div class="section-band cream">
        <div class="content-wrapper">
            <div class="section-title">Bugete Active ({{ now()->translatedFormat('F Y') }})</div>
            
            <div style="overflow-x: auto;">
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Lună / An</th>
                            <th>Categorie</th>
                            <th>Responsabil</th>
                            <th>Consum și Analiză</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeBudgets as $budget)
                            <tr>
                                <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($budget->month_year)->format('m / Y') }}</td>
                                <td>{{ $budget->category_name }}</td>
                                <td>{{ $budget->user_name }}</td>
                                <td style="min-width: 300px;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                                        <span style="color: var(--text-muted);">Cheltuit: <strong style="color: var(--text-dark);">{{ number_format($budget->spent_amount, 2) }}</strong> RON</span>
                                        <span style="color: var(--text-muted);">Plafon: {{ number_format($budget->allocated_amount, 2) }} RON</span>
                                    </div>
                                    
                                    <div class="progress-bar-bg">
                                        @php
                                            $color = $budget->consumption_percentage >= 90 ? 'var(--pastel-red-text)' : ($budget->consumption_percentage >= 75 ? 'var(--pastel-orange-text)' : 'var(--pastel-green-text)');
                                            $width = $budget->consumption_percentage > 100 ? 100 : $budget->consumption_percentage;
                                        @endphp
                                        <div class="progress-bar-fill" style="background-color: {{ $color }}; width: {{ $width }}%;"></div>
                                    </div>
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 8px;">
                                        <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500; max-width: 80%;">
                                            {{ $budget->smart_tip }}
                                        </div>
                                        <div style="font-size: 0.95rem; color: {{ $color }}; font-weight: 800;">
                                            {{ $budget->consumption_percentage }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('budgets.edit', $budget->id) }}" class="action-link">Editează</a>
                                    <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Sigur ștergi acest buget?')" class="action-delete">Șterge</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">Nu aveți bugete active pentru această lună.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($pastBudgets->isNotEmpty())
    <div class="section-band gray">
        <div class="content-wrapper">
            <details>
                <summary>Vezi Istoricul Bugetelor (Lunile trecute)</summary>
                <div style="margin-top: 20px; overflow-x: auto;">
                    <table class="clean-table" style="background: none; box-shadow: none;">
                        <thead>
                            <tr>
                                <th>Lună / An</th>
                                <th>Categorie</th>
                                <th>Responsabil</th>
                                <th>Consum Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastBudgets as $budget)
                                <tr>
                                    <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($budget->month_year)->format('m / Y') }}</td>
                                    <td>{{ $budget->category_name }}</td>
                                    <td>{{ $budget->user_name }}</td>
                                    <td>
                                        @php
                                            $color = $budget->consumption_percentage > 100 ? 'var(--pastel-red-text)' : 'var(--pastel-green-text)';
                                        @endphp
                                        <span style="color: var(--text-muted);">Cheltuit:</span> <strong style="color: {{ $color }}">{{ number_format($budget->spent_amount, 2) }} RON</strong> 
                                        <span style="color: var(--text-muted); font-size: 0.95rem;">(din {{ number_format($budget->allocated_amount, 2) }} RON)</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        </div>
    </div>
    @endif

</div>
@endsection