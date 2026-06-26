@extends('layouts.app')
@section('title', 'Economii & Obiective - SmartPocket')
@section('content')

@php 
    $isParent = auth()->user()->role === 'parent'; 
    \Carbon\Carbon::setLocale('ro');
@endphp

<style>
    .page-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; font-family: 'Plus Jakarta Sans', sans-serif; }

    .global-savings-card {
        background: linear-gradient(135deg, var(--brand-purple) 0%, #A78BFA 100%);
        border-radius: 24px; padding: 40px; color: white; margin-bottom: 40px;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;
        box-shadow: 0 10px 30px -10px rgba(139, 92, 246, 0.4);
    }
    .global-balance { font-size: 3.5rem; font-weight: 800; line-height: 1; }
    .global-balance span { font-size: 1.5rem; opacity: 0.8; }

    .info-helper {
        position: relative;
        display: inline-block;
        margin-left: 10px;
        cursor: help;
        vertical-align: middle;
    }
    .info-helper i { font-size: 0.9rem; opacity: 0.8; }
    
    .tooltip-box {
        visibility: hidden;
        width: 260px;
        background-color: #1E293B;
        color: #fff;
        text-align: center;
        border-radius: 12px;
        padding: 12px;
        position: absolute;
        z-index: 10;
        bottom: 130%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1.4;
        box-shadow: 0 10px 15px rgba(0,0,0,0.2);
    }
    .info-helper:hover .tooltip-box { visibility: visible; opacity: 1; }
    .tooltip-box::after {
        content: ""; position: absolute; top: 100%; left: 50%; margin-left: -5px;
        border-width: 5px; border-style: solid; border-color: #1E293B transparent transparent transparent;
    }

    .withdraw-global-form {
        background: rgba(255, 255, 255, 0.1); padding: 15px; border-radius: 16px;
        display: flex; gap: 10px; align-items: center; border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .withdraw-global-form input {
        padding: 10px; border-radius: 10px; border: none; width: 120px; font-weight: 700; outline: none;
    }
    .btn-withdraw-global {
        background: white; color: var(--brand-purple); border: none; padding: 10px 20px;
        border-radius: 10px; font-weight: 800; cursor: pointer; transition: 0.2s;
    }

    .index-profile-alert {
        background-color: #EDE9FE; border: 2px dashed #8B5CF6; border-radius: 16px;
        padding: 20px 25px; margin-bottom: 35px; display: flex; align-items: center; gap: 20px;
    }

    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .section-header h3 { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin: 0; }

    .goals-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; }
    .goal-card { 
        background: white; border-radius: 20px; padding: 24px; border: 1px solid #E2E8F0; 
        display: flex; flex-direction: column; min-height: 520px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }

    .smart-tip-box {
        background-color: #F8FAFC; padding: 15px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;
        color: var(--text-dark); border-left: 4px solid var(--brand-purple); margin-bottom: 20px; flex-grow: 1;
    }
    .smart-tip-box.success { border-left-color: #10B981; background-color: #F0FDF4; }
    .smart-tip-box.warning { border-left-color: #F59E0B; background-color: #FFFBEB; }

    .progress-bar-bg { background-color: #F1F5F9; height: 12px; border-radius: 10px; overflow: hidden; margin-bottom: 20px; }
    .progress-bar-fill { height: 100%; background-color: var(--brand-purple); transition: width 0.6s ease; }

    .goal-actions { display: flex; flex-direction: column; gap: 10px; margin-top: auto; border-top: 1px solid #F1F5F9; padding-top: 20px; }
    .action-row { display: flex; gap: 8px; }
    .action-input { flex: 1; padding: 10px; border: 2px solid #E2E8F0; border-radius: 10px; font-weight: 600; outline: none; font-size: 0.85rem; }
    
    .btn-icon { border: none; border-radius: 10px; padding: 10px 15px; cursor: pointer; color: white; font-weight: 800; transition: 0.2s; }
    .btn-add { background-color: #10B981; }
    .btn-remove { background-color: #FCA5A5; color: #991B1B; }

    .admin-actions { display: flex; gap: 8px; margin-top: 5px; }
    .btn-edit { 
        flex: 1; text-align: center; background-color: #F1F5F9; color: var(--text-dark); 
        padding: 8px; border-radius: 8px; text-decoration: none; font-size: 0.8rem; font-weight: 700; 
    }
    .btn-delete { 
        flex: 1; background-color: #FEE2E2; color: #EF4444; border: none; 
        padding: 8px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; 
    }

    .btn-new-goal { background-color: var(--text-dark); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 700; }
</style>

<div class="page-container">

    @if($isParent && $isProfileIncomplete)
        <div class="index-profile-alert">
            <div style="font-size: 1.5rem;">💡</div>
            <div>
                <p style="margin: 0; font-weight: 700; color: var(--brand-purple);">Activează Smart Tips complete</p>
                <p style="margin: 3px 0 0 0; font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">
                    Completează datele despre profilul tău în <a href="{{ route('profile.index') }}" style="color: var(--brand-purple); text-decoration: underline;">Profilul Meu</a> pentru sfaturi financiare personalizate.
                </p>
            </div>
        </div>
    @endif

    <div class="global-savings-card">
        <div>
            <h2 style="font-weight: 800; margin-bottom: 5px; display: flex; align-items: center;">
                <i class="fas {{ $isParent ? 'fa-university' : 'fa-piggy-bank' }}" style="margin-right: 12px;"></i>
                {{ $isParent ? 'Fondul de Economii' : 'Pușculița Mea' }}
                
                <div class="info-helper">
                    <i class="fas fa-info-circle"></i>
                    <div class="tooltip-box">
                        {{ $isParent 
                            ? 'Aici se strâng automat banii rămași necheltuiți la finalul lunii din bugetele familiei.' 
                            : 'Aici se adună restul de bani din alocațiile tale pe care nu i-ai cheltuit lunile trecute.' }}
                    </div>
                </div>
            </h2>
            <p style="font-weight: 500; opacity: 0.9;">
                {{ $isParent ? 'Bani salvați prin gestionarea atentă a bugetului' : 'Banii tăi rămași din alocațiile trecute.' }}
            </p>
        </div>

        <div style="text-align: right;">
            <div class="global-balance">{{ number_format($fondEconomii, 2) }} <span>RON</span></div>
            @if($fondEconomii > 0)
                <form action="{{ route('saving-goals.withdraw-global') }}" method="POST" class="withdraw-global-form">
                    @csrf
                    <input type="number" name="amount_to_withdraw" step="0.01" max="{{ $fondEconomii }}" placeholder="Suma..." required min="0.01">
                    <button type="submit" class="btn-withdraw-global">Retrage</button>
                </form>
            @endif
        </div>
    </div>

    <div class="section-header">
        <h3>Obiective active</h3>
        @if($isParent) <a href="{{ route('saving-goals.create') }}" class="btn-new-goal">+ Obiectiv Nou</a> @endif
    </div>

    <div class="goals-grid">
        @forelse($obiective as $goal)
            @php
                $progres = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0;
                $aiClass = $goal->insight['status'] ?? 'info';
            @endphp
            
            <div class="goal-card">
                <div style="display:flex; justify-content:space-between; margin-bottom:15px; align-items: center;">
                    <h4 style="font-weight: 800; color: var(--text-dark); margin:0;">{{ $goal->goal_name }}</h4>
                    <span style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); background: #F1F5F9; padding: 5px 12px; border-radius: 20px;">
                        {{ \Carbon\Carbon::parse($goal->target_date)->translatedFormat('M Y') }}
                    </span>
                </div>

                <div style="display:flex; justify-content:space-between; font-size: 0.9rem; font-weight: 700; margin-bottom: 8px;">
                    Strâns: <span>{{ number_format($goal->current_amount, 0) }} / {{ number_format($goal->target_amount, 0) }} RON</span>
                </div>
                
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" style="width: {{ $progres }}%;"></div>
                </div>

                @if(isset($goal->insight['mesaj']))
                    <div class="smart-tip-box {{ $aiClass }}">
                        <strong>Smart Tip:</strong> {{ $goal->insight['mesaj'] }}
                        @if(isset($goal->insight['recomandare']))
                            <div style="margin-top: 8px; opacity: 0.8; font-size: 0.8rem; font-weight: 500;">
                                {{ $goal->insight['recomandare'] }}
                            </div>
                        @endif
                    </div>
                @endif

                <div class="goal-actions">
                    @if($goal->current_amount < $goal->target_amount)
                    <form action="{{ route('saving-goals.add-funds', $goal->id) }}" method="POST" class="action-row">
                        @csrf
                        <input type="number" name="amount_to_add" class="action-input" placeholder="Adaugă RON" required min="1">
                        <button type="submit" class="btn-icon btn-add" title="Depune"><i class="fas fa-plus"></i></button>
                    </form>
                    @endif

                    @if($isParent)
                        @if($goal->current_amount > 0)
                        <form action="{{ route('saving-goals.withdraw-funds', $goal->id) }}" method="POST" class="action-row">
                            @csrf
                            <input type="number" name="amount_to_withdraw" class="action-input" placeholder="Retrage RON" required min="1" max="{{ $goal->current_amount }}">
                            <button type="submit" class="btn-icon btn-remove" title="Retrage"><i class="fas fa-minus"></i></button>
                        </form>
                        @endif

                        <div class="admin-actions">
                            <a href="{{ route('saving-goals.edit', $goal->id) }}" class="btn-edit">Editează</a>
                            <form action="{{ route('saving-goals.destroy', $goal->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Ștergi obiectivul?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">Șterge</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px; font-weight: 600;">Nu există obiective momentan.</p>
        @endforelse
    </div>
</div>
@endsection