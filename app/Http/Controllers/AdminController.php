<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Family;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Acces interzis!');
        }

        $stats = [
            'total_users' => User::count(),
            'total_families' => Family::count(),
            'total_transactions' => Transaction::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}