@extends('layouts.app')
@section('title', 'Istoric Tranzacții - SmartPocket')
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
        min-height: 100vh;
        background-color: var(--bg-band-gray);
    }

    .section-band { padding: 40px 20px; }
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
    }

    .alert-success { background-color: var(--pastel-green-bg); color: var(--pastel-green-text); padding: 16px 24px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; }

    .calendar-container {
        background-color: #FFFFFF;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        margin: 0 auto 40px auto;
        max-width: 700px;
    }

    .calendar-header {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .calendar-month-name {
        color: var(--brand-purple);
        background-color: var(--brand-purple-light);
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }

    .calendar-day-header {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        padding-bottom: 6px;
    }

    .calendar-day {
        min-height: 50px;
        border-radius: 8px;
        background-color: #F8FAFC;
        border: 1px solid #F1F5F9;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 6px;
        position: relative;
    }

    .day-number {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    .day-total {
        font-size: 0.75rem;
        font-weight: 800;
        text-align: right;
        margin-top: auto;
    }

    .calendar-day.has-data {
        cursor: pointer;
        color: #FFFFFF;
        transition: transform 0.2s ease;
    }

    .calendar-day.has-data .day-number { color: #FFFFFF; opacity: 0.9; }
    .calendar-day.has-data .day-total { color: #FFFFFF; }

    .calendar-day.has-data:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3);
        z-index: 10;
    }

    .heat-1 { background-color: #DDD6FE; border-color: #C4B5FD;}
    .heat-1 .day-number, .heat-1 .day-total { color: var(--brand-purple); }
    .heat-2 { background-color: #A78BFA; border-color: #8B5CF6;}
    .heat-3 { background-color: #8B5CF6; border-color: #7C3AED;}
    .heat-4 { background-color: #6D28D9; border-color: #5B21B6;}

    .day-tooltip {
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%) translateY(5px);
        background-color: #1E293B;
        color: #FFFFFF;
        padding: 10px 14px;
        border-radius: 8px;
        width: max-content;
        min-width: 140px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, transform 0.2s ease;
        pointer-events: none;
        z-index: 100;
    }

    .calendar-day.has-data:hover .day-tooltip {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .day-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #1E293B transparent transparent transparent;
    }

    .tooltip-total {
        font-size: 0.9rem;
        font-weight: 800;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 4px;
        margin-bottom: 4px;
        color: var(--brand-purple-light);
    }

    .tooltip-cat {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 2px;
        gap: 12px;
    }

    .empty-day { background-color: transparent; border: none; }

    .filters-bar {
        display: flex;
        gap: 16px;
        align-items: flex-end;
        background-color: #FFFFFF;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
    }

    .filter-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        background-color: #F8FAFC;
        color: var(--text-dark);
        font-weight: 600;
        font-size: 0.9rem;
        outline: none;
        min-width: 150px;
    }

    .btn-filter { background-color: var(--text-dark); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; height: 38px;}
    .btn-export { background-color: var(--pastel-green-text); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; height: 38px; margin-left: auto;}
    .link-reset { color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-decoration: none; padding: 8px; }

    .clean-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        background-color: #FFFFFF;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .clean-table th {
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        padding: 16px;
        border-bottom: 2px solid #F1F5F9;
    }

    .clean-table td {
        padding: 16px;
        border-bottom: 1px solid #F8FAFC;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .clean-table tr:hover td { background-color: #FAFAFA; }
    .clean-table tr:last-child td { border-bottom: none; }

    .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .badge-auto { background-color: var(--brand-purple-light); color: var(--brand-purple); }
    .badge-manual { background-color: #F1F5F9; color: var(--text-muted); }

    .action-link { color: var(--brand-purple); text-decoration: none; font-weight: 700; font-size: 0.85rem; margin-right: 10px;}
    .action-delete { color: var(--pastel-red-text); background: none; border: none; cursor: pointer; font-weight: 700; font-size: 0.85rem; padding: 0;}

    nav[role="navigation"] {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex !important;
        list-style: none !important;
        padding: 0 !important;
        gap: 12px !important;
    }

    .page-link {
        border: 1px solid #E2E8F0 !important;
        background-color: #FFFFFF !important;
        color: var(--text-dark) !important;
        padding: 10px 24px !important;
        border-radius: 12px !important;
        font-weight: 800 !important;
        text-decoration: none !important;
        font-size: 0.9rem !important;
        transition: 0.2s all ease !important;
        display: flex !important;
        align-items: center !important;
    }

    .page-link:hover {
        background-color: var(--brand-purple-light) !important;
        color: var(--brand-purple) !important;
        border-color: var(--brand-purple) !important;
        transform: translateY(-2px);
    }

    .page-item.disabled .page-link {
        background-color: #F1F5F9 !important;
        color: #CBD5E1 !important;
        border-color: #E2E8F0 !important;
        cursor: not-allowed !important;
    }
</style>

<div class="finance-dashboard">
    
    <div class="section-band cream">
        <div class="content-wrapper">
            <div class="header-bar">
                <h3>Istoric Tranzacții</h3>
                <a href="{{ route('transactions.create') }}" class="btn-primary">+ Adaugă Tranzacție</a>
            </div>

            @if(session('success'))
                <div class="alert-success">✔ {{ session('success') }}</div>
            @endif

            <div class="calendar-container">
                <div class="calendar-header">
                    Harta Cheltuielilor
                    @php
                        $displayMonth = request('month') ?: \Carbon\Carbon::now()->format('Y-m');
                        $parsedMonth = \Carbon\Carbon::parse($displayMonth.'-01');
                    @endphp
                    <span class="calendar-month-name">{{ $parsedMonth->translatedFormat('F Y') }}</span>
                </div>

                <div class="calendar-grid">
                    <div class="calendar-day-header">Lun</div>
                    <div class="calendar-day-header">Mar</div>
                    <div class="calendar-day-header">Mie</div>
                    <div class="calendar-day-header">Joi</div>
                    <div class="calendar-day-header">Vin</div>
                    <div class="calendar-day-header">Sâm</div>
                    <div class="calendar-day-header">Dum</div>

                    @php
                        $daysInMonth = $parsedMonth->daysInMonth;
                        $firstDayOfWeek = $parsedMonth->dayOfWeekIso;

                        $maxDailyExpense = 0;
                        if(isset($calendarData) && count($calendarData) > 0) {
                            $maxDailyExpense = max(array_column($calendarData, 'total'));
                        }
                    @endphp

                    @for($i = 1; $i < $firstDayOfWeek; $i++)
                        <div class="calendar-day empty-day"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dateString = $parsedMonth->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $dayData = isset($calendarData[$dateString]) ? $calendarData[$dateString] : null;
                            
                            $heatClass = '';
                            if($dayData && $dayData['total'] > 0 && $maxDailyExpense > 0) {
                                $ratio = $dayData['total'] / $maxDailyExpense;
                                if($ratio <= 0.25) $heatClass = 'heat-1';
                                elseif($ratio <= 0.5) $heatClass = 'heat-2';
                                elseif($ratio <= 0.75) $heatClass = 'heat-3';
                                else $heatClass = 'heat-4';
                            }
                        @endphp

                        <div class="calendar-day {{ $dayData ? 'has-data ' . $heatClass : '' }}">
                            <div class="day-number">{{ $day }}</div>
                            
                            @if($dayData)
                                <div class="day-total">{{ number_format($dayData['total'], 0) }}</div>
                                <div class="day-tooltip">
                                    <div class="tooltip-total">{{ number_format($dayData['total'], 2) }} RON</div>
                                    @foreach($dayData['categories'] as $catName => $amount)
                                        <div class="tooltip-cat">
                                            <span>{{ $catName }}</span>
                                            <span>{{ number_format($amount, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <div class="section-band gray">
        <div class="content-wrapper">
            
            <form method="GET" action="{{ route('transactions.index') }}" class="filters-bar">
                
                <div class="filter-group">
                    <label for="month" class="filter-label">Lună:</label>
                    <select name="month" id="month" class="filter-select">
                        <option value="">Toate lunile</option>
                        @foreach($availableMonths as $value => $label)
                            <option value="{{ $value }}" {{ request('month') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(auth()->user()->role !== 'child')
                <div class="filter-group">
                    <label class="filter-label">Membru:</label>
                    <select name="user_id" class="filter-select">
                        <option value="">Toți membrii</option>
                        @foreach($familyMembers as $member)
                            <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="filter-group">
                    <label class="filter-label">Categorie:</label>
                    <select name="category_id" class="filter-select">
                        <option value="">Toate categoriile</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-filter">Filtrează</button>
                <a href="{{ route('transactions.index') }}" class="link-reset">Resetează</a>

                <button type="submit" name="export" value="csv" class="btn-export">Export CSV</button>
            </form>

            <div style="overflow-x: auto;">
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Dată</th>
                            <th>Descriere</th>
                            <th>Adăugat de</th> 
                            <th>Categorie</th> 
                            <th>Tip</th>
                            <th>Plată</th>
                            <th style="text-align: right;">Sumă</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                            <tr>
                                <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M, Y') }}</td>
                                <td>{{ $t->description }}</td>
                                <td style="color: var(--text-muted);">{{ $t->user->name ?? 'N/A' }}</td>

                                <td>
                                    {{ $t->categoryFinal->name ?? 'Nespecificat' }}
                                    @if($t->category_id_ai)
                                        <br><span class="badge badge-auto">Clasificat automat</span>
                                    @endif
                                </td>

                                <td>
                                    @if($t->type == 'expense')
                                        <span style="color: var(--pastel-red-text);">Cheltuială</span>
                                    @else
                                        <span style="color: var(--pastel-green-text);">Venit</span>
                                    @endif
                                </td>
                                <td style="color: var(--text-muted); text-transform: capitalize;">{{ $t->payment_method }}</td>
                                <td style="text-align: right; font-weight: 800; font-size: 1rem; color: {{ $t->type == 'income' ? 'var(--pastel-green-text)' : 'var(--text-dark)' }}">
                                    {{ $t->type == 'income' ? '+' : '-' }}{{ number_format($t->amount, 2) }}
                                </td>
                                <td>
                                    <a href="{{ route('transactions.edit', $t->id) }}" class="action-link">Editează</a>
                                    <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-delete" onclick="return confirm('Sigur vrei să ștergi această tranzacție?')">Șterge</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted); font-weight: 500;">
                                    Nu există tranzacții pentru filtrele selectate.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 30px;">
                {{ $transactions->withQueryString()->links('pagination::simple-bootstrap-4') }}
            </div>

        </div>
    </div>

</div>
@endsection