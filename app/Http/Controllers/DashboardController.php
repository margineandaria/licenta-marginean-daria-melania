<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FinancialService;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\SavingGoal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index()
    {
        $user = Auth::user();
        $familyId = $user->family_id;
        $now = Carbon::now();
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.index');
        }
        if ($user->role === 'child') {
            $currentPeriod = $now->format('Y-m'); 
    
            $bugetCopil = Budget::where('family_id', $familyId)
                    ->where('user_id_responsible', $user->id)
                    ->where('month_year', 'LIKE', $currentPeriod . '%') 
                    ->first();

            $totalCheltuitLunaAsta = Transaction::where('family_id', $familyId)
                            ->where('user_id_allocated', $user->id)
                            ->where('type', 'expense')
                            ->whereMonth('transaction_date', $now->month)
                            ->whereYear('transaction_date', $now->year)
                            ->sum('amount');

            return view('dashboard-child', [
                'buget' => $bugetCopil,
                'totalCheltuit' => $totalCheltuitLunaAsta, 
                'tranzactii' => Transaction::with(['user', 'categoryFinal'])
                                ->where('family_id', $familyId)
                                ->where('user_id_allocated', $user->id) 
                                ->orderBy('transaction_date', 'desc')
                                ->take(10)
                                ->get(),
                'obiective' => SavingGoal::where('family_id', $familyId)->get()
            ]);
        }

        $summary = $this->financialService->getMonthlySummary($familyId);
        $chartData = $this->financialService->getExpensesByCategoryForChart($familyId);
        $comparison = $this->financialService->getCategoryComparison($familyId);
        $bugetAnalysis = $this->financialService->getBudgetsConsumption($familyId);

        return view('dashboard', [
            'summary' => $summary,
            'chartData' => $chartData,
            'comparison' => $comparison,
            'bugete' => $bugetAnalysis, 
            'tranzactii' => Transaction::with(['user', 'categoryFinal'])
                            ->where('family_id', $familyId)
                            ->orderBy('transaction_date', 'desc')
                            ->take(10)
                            ->get(),
            'obiective' => SavingGoal::where('family_id', $familyId)->get(),
            'membri' => User::where('family_id', $familyId)->get(),
        ]);
    }
}