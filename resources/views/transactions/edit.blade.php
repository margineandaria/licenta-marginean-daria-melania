@extends('layouts.app')
@section('title', 'Editează Tranzacție - SmartPocket')
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

    .form-note-ai {
        font-size: 0.85rem;
        color: var(--brand-purple);
        background-color: #EDE9FE;
        padding: 8px 12px;
        border-radius: 8px;
        margin-top: -4px;
        font-weight: 600;
        display: inline-block;
        width: fit-content;
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
                <div class="section-title">Modifică Tranzacția</div>
                <div class="section-subtitle">Ajustează detaliile sau corectează categoria stabilită automat.</div>

                @if($errors->any())
                    <div class="alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Descriere Tranzacție:</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description', $transaction->description) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Categorie Finală:</label>
                        <select name="category_id_final" class="form-control" required>
                            @foreach($categorii as $cat)
                                <option value="{{ $cat->id }}" {{ $transaction->category_id_final == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($transaction->category_id_ai)
                            <div class="form-note-ai">Clasificat automat inițial ca: {{ $transaction->categoryAi->name ?? 'Nespecificat' }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Suma (RON):</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $transaction->amount) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipul tranzacției:</label>
                        <select name="type" class="form-control" required>
                            <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Cheltuială (-)</option>
                            <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Venit (+)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Metoda de plată:</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="card" {{ $transaction->payment_method == 'card' ? 'selected' : '' }}>Card Bancar</option>
                            <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Numerar (Cash)</option>
                            <option value="transfer" {{ $transaction->payment_method == 'transfer' ? 'selected' : '' }}>Transfer / Revolut</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Data Tranzacției:</label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $transaction->transaction_date) }}" required>
                    </div>

                    <button type="submit" class="btn-submit">Salvează Modificările</button>
                    <a href="{{ route('transactions.index') }}" class="link-cancel">Anulează</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection