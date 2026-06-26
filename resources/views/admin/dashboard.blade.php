@extends('layouts.app')

@section('title', 'Admin Panel - SmartPocket')

@section('content')
<div style="max-width: 1000px; margin: 40px auto; padding: 20px; font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 20px; margin-bottom: 30px;">
        <h1 style="margin: 0; font-weight: 800; color: #1E293B;">Panou Control Sistem</h1>
        <div style="display: flex; gap: 20px; align-items: center;">
            <span style="background: #1E293B; color: white; padding: 8px 16px; border-radius: 8px; font-weight: bold;">System Admin</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
        <div style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #E2E8F0;">
            <div style="color: #64748B; font-weight: 700; text-transform: uppercase; font-size: 0.8rem;">Total Utilizatori</div>
            <div style="font-size: 2.5rem; font-weight: 800; color: #1E293B;">{{ $stats['total_users'] }}</div>
        </div>
        <div style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #E2E8F0;">
            <div style="color: #64748B; font-weight: 700; text-transform: uppercase; font-size: 0.8rem;">Familii Înregistrate</div>
            <div style="font-size: 2.5rem; font-weight: 800; color: #1E293B;">{{ $stats['total_families'] }}</div>
        </div>
        <div style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #E2E8F0;">
            <div style="color: #64748B; font-weight: 700; text-transform: uppercase; font-size: 0.8rem;">Tranzacții Totale</div>
            <div style="font-size: 2.5rem; font-weight: 800; color: #1E293B;">{{ $stats['total_transactions'] }}</div>
        </div>
    </div>

</div>
@endsection