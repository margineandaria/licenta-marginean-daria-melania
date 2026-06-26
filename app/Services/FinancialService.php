<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialService
{
    public function getMonthlySummary($familyId)
    {
        $now = Carbon::now();
        $income = Transaction::where('family_id', $familyId)->where('type', 'income')->whereMonth('transaction_date', $now->month)
        ->whereYear('transaction_date', $now->year)->sum('amount');
        $expense = Transaction::where('family_id', $familyId)->where('type', 'expense')->whereMonth('transaction_date', $now->month)
        ->whereYear('transaction_date', $now->year)->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense, 
            'fond_economii' => $this->getFondEconomiiTotal($familyId)
        ];
    }

    public function getFondEconomiiTotal($familyId)
    {
        $startOfCurrentMonth = Carbon::now()->startOfMonth();
        $pastSurplus = Transaction::where('family_id', $familyId)->where('transaction_date', '<', $startOfCurrentMonth)->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as total")->value('total') ?? 0;
        
        $withdrawals = Transaction::where('family_id', $familyId)
            ->where('type', 'income')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereHas('categoryFinal', function($q) { $q->where('name', 'Bani din Economii'); })
            ->sum('amount');

        return max($pastSurplus - $withdrawals, 0);
    }
    public function getFondEconomiiCopil($userId)
    {
        $now = Carbon::now();
        $currentMonth = $now->format('Y-m');
        $startOfCurrentMonth = $now->startOfMonth();
        $totalAlocatiiTrecute = Budget::where('user_id_responsible', $userId)
            ->where('month_year', '<', $currentMonth)
            ->sum('budget_amount');
        $totalCheltuieliTrecute = Transaction::where('user_id_allocated', $userId)
            ->where('type', 'expense')
            ->where('transaction_date', '<', $startOfCurrentMonth)
            ->sum('amount');
        $baniSalvati = $totalAlocatiiTrecute - $totalCheltuieliTrecute;

        return max(0, $baniSalvati);
    }

    public function getBudgetsConsumption($familyId)
    {
        $allBudgets = \App\Models\Budget::where('family_id', $familyId)
                        ->orderBy('month_year', 'desc')
                        ->get();
        return $allBudgets->map(function ($budget) use ($familyId) {
            
            $dataBuget = \Carbon\Carbon::parse($budget->month_year);
            $lunaTrecuta = clone $dataBuget;
            $lunaTrecuta->subMonth();

            $responsabil = $budget->responsibleUser ?? $budget->user;
            
            $queryCurrent = \App\Models\Transaction::where('family_id', $familyId)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $dataBuget->month)
                ->whereYear('transaction_date', $dataBuget->year);

            $queryPast = \App\Models\Transaction::where('family_id', $familyId)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $lunaTrecuta->month)
                ->whereYear('transaction_date', $lunaTrecuta->year);

            if ($responsabil && $responsabil->role === 'child') {
                $spentCurrent = (clone $queryCurrent)->where('user_id_allocated', $responsabil->id)->sum('amount');
                $spentPast = (clone $queryPast)->where('user_id_allocated', $responsabil->id)->sum('amount');
            } else {
                $spentCurrent = (clone $queryCurrent)->where('category_id_final', $budget->category_id)->sum('amount');
                $spentPast = (clone $queryPast)->where('category_id_final', $budget->category_id)->sum('amount');
            }

            $allocated = (float) $budget->budget_amount;
            $percentage = $allocated > 0 ? round(($spentCurrent / $allocated) * 100, 1) : 0;
            
            $smartTip = "";
            if ($spentPast > $allocated) {
                $smartTip = "Luna precedentă ai cheltuit " . $spentPast . " RON (peste plafonul acesta). Atenție!";
            } elseif ($spentPast > 0 && $spentPast < ($allocated * 0.5)) {
                $smartTip = "Luna precedentă ai reușit să te încadrezi în buget (" . $spentPast . " RON).";
            } else {
                $smartTip = " Plafon setat realist comparativ cu luna trecută.";
            }
            return [
                'id' => $budget->id,
                'month_year' => $budget->month_year,
                'category_name' => $budget->category->name ?? 'Buget Copil',
                'user_name' => $responsabil->name ?? 'Membru',
                'allocated_amount' => $allocated,
                'spent_amount' => $spentCurrent,
                'consumption_percentage' => $percentage,
                'smart_tip' => $smartTip
            ];
        })->values(); 
    }


    public function getExpensesByCategoryForChart($familyId)
    {
        $now = Carbon::now();
        return DB::table('transactions')
            ->join('categories', 'transactions.category_id_final', '=', 'categories.id')
            ->where('transactions.family_id', $familyId)
            ->where('transactions.type', 'expense')
            ->whereMonth('transactions.transaction_date', $now->month)
            ->whereYear('transactions.transaction_date', $now->year)
            ->selectRaw('categories.name as category_name, sum(transactions.amount) as total')
            ->groupBy('categories.name')->get();
    }

    public function getCategoryComparison($familyId)
    {
        $now = Carbon::now();

        $prev = Carbon::now()->subMonthNoOverflow();

        $currentExpenses = $this->getExpensesByMonth($familyId, $now->month, $now->year);
        $previousExpenses = $this->getExpensesByMonth($familyId, $prev->month, $prev->year);

        $analysis = [];
        foreach ($currentExpenses as $category => $currentTotal) {
            $previousTotal = $previousExpenses[$category] ?? 0;
            $percent = ($previousTotal > 0) ? round((($currentTotal - $previousTotal) / $previousTotal) * 100, 1) : 100;

            $analysis[] = [
                'category' => $category,
                'current_spent' => $currentTotal,
                'previous_spent' => $previousTotal,
                'percentage_change' => abs($percent),
                'trend' => $currentTotal > $previousTotal ? 'up' : 'down'
            ];
        }
        return $analysis;
    }

    private function getExpensesByMonth($familyId, $month, $year)
    {
        return DB::table('transactions')
            ->join('categories', 'transactions.category_id_final', '=', 'categories.id')
            ->where('transactions.family_id', $familyId)
            ->where('transactions.type', 'expense')
            ->whereMonth('transactions.transaction_date', $month)
            ->whereYear('transactions.transaction_date', $year)
            ->groupBy('categories.name')
            ->selectRaw('categories.name as cat_name, SUM(transactions.amount) as total_val')
            ->pluck('total_val', 'cat_name');
    }
    public function getGoalInsight($user, $obiectiv)
    {
        if ($user->role === 'parent' && !$user->hasCompleteProfile()) {
            return [
                'status' => 'info',
                'mesaj' => 'Sfatul personalizat este momentan generat cu date implicite.',
                'recomandare' => 'Pentru o regresie ML precisă bazată pe profilul tău, completează datele demografice în secțiunea Profil.'
            ];
        }

        $familyId = $user->family_id;
        $sumaRamasa = max(0, $obiectiv->target_amount - $obiectiv->current_amount);
        
        if ($sumaRamasa <= 0) return ['status' => 'completed', 'mesaj' => 'Obiectiv atins! Felicitări!'];

        $dateTinta = Carbon::parse($obiectiv->target_date);
        $luniDorite = max(1, now()->diffInMonths($dateTinta));
        $necesarLunar = $sumaRamasa / $luniDorite;

        $parentIds = User::where('family_id', $familyId)->where('role', 'parent')->pluck('id');
        $startAcum2Luni = now()->subMonths(2)->startOfMonth();
        $sfarsitLunaTrecuta = now()->subMonth()->endOfMonth();
        $dataLunaTrecuta = now()->subMonth();

        $venitIstoric = Transaction::whereIn('user_id_allocated', $parentIds)
            ->whereHas('categoryFinal', fn($q) => $q->where('type', 'income'))
            ->whereBetween('transaction_date', [$startAcum2Luni, $sfarsitLunaTrecuta])
            ->sum('amount') / 2;

        $cheltuieliIstoric = Transaction::where('family_id', $familyId)
            ->whereHas('categoryFinal', fn($q) => $q->where('type', 'expense'))
            ->whereBetween('transaction_date', [$startAcum2Luni, $sfarsitLunaTrecuta])
            ->sum('amount') / 2;

        $economiiIstoriceMedii = max(0, $venitIstoric - $cheltuieliIstoric);

        $venitCurent = Transaction::whereIn('user_id_allocated', $parentIds)
            ->whereHas('categoryFinal', fn($q) => $q->where('type', 'income'))
            ->whereMonth('transaction_date', now()->month)->sum('amount');
            
        $rateCurent = $this->getFamilySumByCategory($familyId, 'Rate Bancare');
        $chirieCalculata = $this->getFamilySumByCategory($familyId, 'Chirie');
        $utilitatiCurent = $this->getFamilySumByCategory($familyId, 'Utilități', now());
        $alimenteCurent = $this->getFamilySumByCategory($familyId, 'Alimente', now());
        $divertismentCurent = $this->getFamilySumByCategory($familyId, 'Divertisment', now());
        
        $statutFinal = ($chirieCalculata > 0) ? 'Chirie' : ($user->housing_status ?: 'Proprietar');

        $numarMembri = max(1, $user->family->users()->count());

        $dateML = [
            "venit_lunar" => (float)($venitCurent > 0 ? $venitCurent : 1), 
            "cheltuieli_alimente" => (float)max($alimenteCurent, $numarMembri * 500),
            "cheltuieli_utilitati" => (float)max($utilitatiCurent, 400),
            "cheltuieli_divertisment" => (float)max($divertismentCurent, 200),
            "rate_bancare" => (float)$rateCurent,
            "numar_membri" => (int)$numarMembri,
            "categorie_varsta" => $user->age_category ?: '30-45',
            "nivel_educatie" => $user->education_level ?: 'Facultate',
            "statut_locuinta" => $statutFinal,
            "domeniu_activitate" => $user->work_domain ?: 'Servicii Diverse',
            "zona_geografica" => $user->geographic_zone ?: 'Urban',
            "luna_anului" => (int)now()->month
        ];

        $predictie = MLService::getInstance()->predictSavings($dateML);
        if (!$predictie) return ['status' => 'error', 'mesaj' => 'Consilierul este indisponibil.'];

        $capacitateAILunara = min(max(1, $predictie['economii_estimate']), $venitCurent * 0.4);
        
        $rataDeEconomisireFolosita = ($economiiIstoriceMedii > 0) ? $economiiIstoriceMedii : ($capacitateAILunara * 0.8);
        $rataDeEconomisireFolosita = max(1, $rataDeEconomisireFolosita); 

        $luniRealiste = ceil($sumaRamasa / $rataDeEconomisireFolosita);
        Carbon::setLocale('ro');
        $dataEstimataReala = now()->addMonths($luniRealiste)->translatedFormat('F Y');

        $sfatPersonalizat = "Ai nevoie de " . round($necesarLunar) . " RON/lună. O optimizare a cheltuielilor variabile ar ajuta.";

        $cheltuieliMediiVariabile = Transaction::with('categoryFinal')
            ->where('family_id', $familyId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startAcum2Luni, $sfarsitLunaTrecuta])
            ->get()
            ->filter(fn($t) => $t->categoryFinal && !in_array($t->categoryFinal->name, ['Rate Bancare', 'Chirie', 'Utilități', 'Transfer către Economii']))
            ->groupBy(fn($t) => $t->categoryFinal->name)
            ->map(fn($group) => $group->sum('amount') / 2)
            ->sortDesc();

        if ($cheltuieliMediiVariabile->isNotEmpty()) {
            $topCategoria = $cheltuieliMediiVariabile->keys()->first();
            $topSuma = $cheltuieliMediiVariabile->first();
            $sfatPersonalizat = "În ultima perioadă, media pentru '" . $topCategoria . "' a fost de " . round($topSuma) . " RON/lună. De aici poți tăia cel mai ușor pentru a atinge obiectivul.";
        }

        if ($luniRealiste <= $luniDorite) {
            return [
                'status' => 'success',
                'mesaj' => "Ești în grafic! Cu media reală de " . round($rataDeEconomisireFolosita) . " RON/lună, vei strânge suma necesară până în " . $dataEstimataReala . ".",
                'recomandare' => "Sfat : Poți atinge chiar un maxim de " . round($capacitateAILunara) . " RON/lună. " . $sfatPersonalizat
            ];
        } else {
            $luniIntarziere = ceil(max(1, $luniRealiste - $luniDorite));
            return [
                'status' => 'warning',
                'mesaj' => "Termen un pic prea optimist. Vei atinge obiectivul abia în " . $dataEstimataReala . ".",
                'recomandare' => "Sfat : Amână termenul cu " . $luniIntarziere . " luni sau redu cheltuielile de tip " . ($cheltuieliMediiVariabile->keys()->first() ?? 'Divertisment') . "."
            ];
        }
    }
    private function getFamilySumByCategory($familyId, $name, $date = null)
    {
        $date = $date ?: now();
        return Transaction::where('family_id', $familyId)
            ->whereHas('categoryFinal', fn($q) => $q->where('name', $name))
            ->whereMonth('transaction_date', $date->month)
            ->whereYear('transaction_date', $date->year)
            ->sum('amount');
    }
}