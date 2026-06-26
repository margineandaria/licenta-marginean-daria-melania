<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(FinancialService $financialService)
    {
        $user = Auth::user();
        
        $budgetsData = $financialService->getBudgetsConsumption($user->family_id);

        $budgets = collect($budgetsData)->map(function ($item) {
            return (object) $item;
        });

        if ($user->role === 'child') {
            
            $categoriiCopil = Budget::where('user_id_responsible', $user->id)
                                    ->pluck('category_id')
                                    ->toArray();
            $budgets = $budgets->filter(function ($budget) use ($user, $categoriiCopil) {
                if (isset($budget->user_id_responsible)) {
                    return $budget->user_id_responsible == $user->id;
                }
                if (isset($budget->category_id)) {
                    return in_array($budget->category_id, $categoriiCopil);
                }

                return false; 
            });
        }

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'parent') return redirect()->route('dashboard')->with('error', 'Nu ai permisiunea de a crea bugete.');

        $categorii = Category::where('type', 'expense')->get();
        $membri = User::where('family_id', Auth::user()->family_id)->get();
        return view('budgets.create', compact('categorii', 'membri'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'parent') abort(403);

        $request->validate([
        'category_id' => 'required|exists:categories,id',
        'user_id_responsible' => 'required|exists:users,id',
        'budget_amount' => 'required|numeric|min:1',
        'month_year' => 'required', 
        ], [
        'category_id.required' => 'Te rog să alegi categoria bugetului.',
        'category_id.exists' => 'Categoria selectată nu este validă.',
        'user_id_responsible.required' => 'Te rog să asignezi un membru responsabil.',
        'user_id_responsible.exists' => 'Membrul selectat nu este găsit.',
        'budget_amount.required' => 'Trebuie să introduci o sumă pentru buget.',
        'budget_amount.numeric' => 'Suma trebuie să fie un număr.',
        'budget_amount.min' => 'Bugetul minim este de 1 RON.',
        'month_year.required' => 'Te rog să alegi luna pentru care setezi bugetul.',
        ]);

        $formattedDate = \Carbon\Carbon::parse($request->month_year)->format('Y-m');

        $exists = Budget::where('family_id', auth()->user()->family_id)
            ->where('category_id', $request->category_id)
            ->where('user_id_responsible', $request->user_id_responsible)
            ->where('month_year', $formattedDate)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'category_id' => 'Există deja un buget pe această categorie pentru luna selectată.'
            ])->withInput();
        }
        Budget::create([
            'family_id' => Auth::user()->family_id,
            'category_id' => $request->category_id,
            'user_id_responsible' => $request->user_id_responsible,
            'budget_amount' => $request->budget_amount,
            'month_year' => $formattedDate, 
        ]);

        return redirect()->route('budgets.index')->with('success', 'Bugetul lunar a fost setat cu succes!');
    }

    public function edit(Budget $budget)
    {
        if (Auth::user()->role !== 'parent' || $budget->family_id !== Auth::user()->family_id) {
            abort(403, 'Acces interzis.');
        }

        $categorii = Category::where('type', 'expense')->get();
        $membri = User::where('family_id', Auth::user()->family_id)->get();
        return view('budgets.edit', compact('budget', 'categorii', 'membri'));
    }

    public function update(Request $request, Budget $budget)
    {
        if (Auth::user()->role !== 'parent' || $budget->family_id !== Auth::user()->family_id) abort(403);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'user_id_responsible' => 'required|exists:users,id',
            'budget_amount' => 'required|numeric|min:1',
            'month_year' => 'required',
        ]);

        $budget->update($request->only(['category_id', 'user_id_responsible', 'budget_amount', 'month_year']));
        return redirect()->route('budgets.index')->with('success', 'Bugetul a fost actualizat!');
    }

    public function destroy(Budget $budget)
    {
        if (Auth::user()->role !== 'parent' || $budget->family_id !== Auth::user()->family_id) abort(403);

        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Bugetul a fost șters!');
    }
}