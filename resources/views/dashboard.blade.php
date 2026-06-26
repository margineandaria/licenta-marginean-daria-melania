@extends('layouts.app')

@section('title', 'SmartPocket')

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

    .content-wrapper { max-width: 1200px; margin: 0 auto; }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-bar h3 {
        margin: 0;
        font-weight: 800;
        font-size: 1.5rem;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .user-badge {
        color: var(--brand-purple);
        background-color: var(--brand-purple-light);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .stat-item { display: flex; flex-direction: column; gap: 4px; }
    .stat-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); }
    .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; letter-spacing: -1px; }

    .ai-alert-banner {
        background-color: #FEFCE8;
        border-radius: 16px;
        padding: 16px 24px;
        margin-bottom: 20px;
    }

    .ai-alert-banner h4 {
        margin: 0 0 8px 0;
        color: #D97706;
        font-size: 1rem;
        font-weight: 800;
    }

    .ai-alert-banner ul { 
        margin: 0; 
        padding-left: 20px; 
        color: #92400E; 
        font-size: 0.95rem; 
        font-weight: 500;
        line-height: 1.4;
    }

    .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; }

    @media (max-width: 992px) { .content-grid { grid-template-columns: 1fr; } }

    .section-title {
        font-weight: 800; font-size: 1.5rem; margin-bottom: 30px; color: var(--text-dark);
        display: flex; justify-content: space-between; align-items: center;
    }

    .section-title a {
        font-size: 0.95rem; color: var(--brand-purple); background-color: var(--brand-purple-light);
        padding: 6px 16px; border-radius: 20px; text-decoration: none; font-weight: 700;
    }

    .goal-list { display: flex; flex-direction: column; gap: 30px; }
    .goal-item { display: flex; flex-direction: column; gap: 12px; }
    .goal-header { display: flex; justify-content: space-between; align-items: flex-end; }
    .goal-title strong { font-size: 1.15rem; font-weight: 700; }
    
    .progress-bar-bg { background-color: #E2E8F0; height: 12px; border-radius: 6px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 6px; background: linear-gradient(90deg, #34D399, #10B981); }

    .clean-table { width: 100%; border-collapse: collapse; text-align: left; }
    .clean-table th { color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; padding: 0 16px 20px 16px; border-bottom: 2px solid #E2E8F0; }
    .clean-table td { padding: 24px 16px; border-bottom: 1px solid #E2E8F0; font-size: 1.05rem; font-weight: 600; }
    .badge-auto { background-color: var(--brand-purple-light); color: var(--brand-purple); padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; }
    .badge-manual { background-color: #E2E8F0; color: var(--text-muted); padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; }
</style>

<div class="finance-dashboard">
    
    <div class="section-band white">
        <div class="content-wrapper">
            
            <div class="header-bar">
                <h3>Prezentare Generală</h3>
                <div class="user-badge">Familia {{ auth()->user()->family->name}}</div>
            </div>

            <div class="summary-grid">
                <div class="stat-item">
                    <div class="stat-title">Venituri (Luna asta)</div>
                    <div class="stat-value" style="color: var(--pastel-green-text);">+ {{ number_format($summary['income'], 2) }} RON</div>
                </div>
                <div class="stat-item">
                    <div class="stat-title">Cheltuieli (Luna asta)</div>
                    <div class="stat-value" style="color: var(--pastel-red-text);">- {{ number_format($summary['expense'], 2) }} RON</div>
                </div>
                <div class="stat-item">
                    <div class="stat-title">Sold Disponibil</div>
                    <div class="stat-value" style="color: var(--brand-purple);">{{ number_format($summary['balance'], 2) }} RON</div>
                </div>
            </div>
            <div class="ai-alert-banner">
                <h4>Analiză Financiară</h4>
                
                @php
                    $realAlerts = [];
                    $hasHistory = false;

                    foreach($comparison as $comp) {
                        if($comp['percentage_change'] != 100) {
                            $hasHistory = true;
                        }
                        
                        if($comp['trend'] == 'up' && $comp['percentage_change'] > 5 && $comp['percentage_change'] != 100) {
                            $realAlerts[] = $comp;
                        }
                    }
                @endphp

                @if(!$hasHistory)
                    <p style="color: #92400E; font-weight: 500;">Bun venit! Momentan nu avem date din lunile trecute pentru comparație. Continuă să adaugi tranzacții, iar de luna viitoare îți voi pregăti prima analiză detaliată.</p>
                @elseif(count($realAlerts) > 0)
                    <ul>
                        @foreach($realAlerts as $alert)
                            <li>Atenție! Cheltuielile pentru <strong>{{ $alert['category'] }}</strong> au crescut cu <strong>{{ $alert['percentage_change'] }}%</strong> față de luna trecută.</li>
                        @endforeach
                    </ul>
                @else
                    <p style="color: #065f46; font-weight: 600;"> Momentan, cheltuielile tale sunt în parametri normali față de luna trecută!</p>
                @endif
            </div>

        </div>
    </div>

    <div class="section-band cream">
        <div class="content-wrapper">
            <div class="content-grid">
                <div>
                    <div class="section-title">Repartizare Cheltuieli</div>
                    <div style="width: 100%; max-width: 550px; margin: 0 auto; height: 500px;" id="chartContainer">
                        <canvas id="expensesChart"></canvas>
                    </div>
                </div>

                <div>
                    <div class="section-title">
                        Obiective de Economisire
                        <a href="{{ route('saving-goals.index') }}">Gestionează &rarr;</a>
                    </div>
                    @if($obiective->isEmpty())
                        <p style="color: var(--text-muted);">Nu aveți obiective setate.</p>
                    @else
                        <div class="goal-list">
                            @foreach($obiective as $goal)
                            <div class="goal-item">
                                <div class="goal-header">
                                    <div class="goal-title"><strong>{{ $goal->goal_name }}</strong></div>
                                    <div style="font-weight: 800;">{{ number_format($goal->current_amount, 0) }} / {{ number_format($goal->target_amount, 0) }} RON</div>
                                </div>
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $goal->progress }}%;"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section-band gray">
        <div class="content-wrapper">
            <div class="section-title">Ultimele Tranzacții <a href="{{ route('transactions.index') }}">Vezi tot &rarr;</a></div>
            <table class="clean-table">
                <thead>
                    <tr><th>Data</th><th>Descriere</th><th>Tip</th><th>Adăugat de</th><th style="text-align: right;">Suma</th></tr>
                </thead>
                <tbody>
                    @foreach($tranzactii as $t)
                    <tr>
                        <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M, Y') }}</td>
                        <td>{{ $t->description }}</td>
                        <td><span style="color: {{ $t->type == 'expense' ? 'var(--pastel-red-text)' : 'var(--pastel-green-text)' }}">{{ $t->type == 'expense' ? 'Cheltuială' : 'Venit' }}</span></td>
                        <td>{{ $t->user->name ?? 'N/A' }}</td>
                        <td style="text-align: right; font-weight: 800;">{{ $t->type == 'expense' ? '-' : '+' }}{{ number_format($t->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateGrafic = @json($chartData);
        if(dateGrafic && dateGrafic.length > 0) {
            const etichete = dateGrafic.map(item => item.category_name);
            const sume = dateGrafic.map(item => parseFloat(item.total));
            const totalSuma = sume.reduce((a, b) => a + b, 0);

            const ctx = document.getElementById('expensesChart').getContext('2d');
            Chart.register(ChartDataLabels);
            
            new Chart(ctx, {
                type: 'pie', 
                data: {
                    labels: etichete,
                    datasets: [{
                        data: sume,
                        backgroundColor: ['#8B5CF6', '#34D399', '#FBBF24', '#F472B6', '#60A5FA', '#94A3B8'],
                        borderColor: '#FFFBF0',
                        borderWidth: 4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'right', labels: { usePointStyle: true, font: { family: 'Plus Jakarta Sans', size: 14, weight: '600' } } },
                        datalabels: {
                            color: '#ffffff',
                            font: { weight: '800', size: 14 },
                            formatter: (value) => {
                                return (value * 100 / totalSuma).toFixed(1) + '%';
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection