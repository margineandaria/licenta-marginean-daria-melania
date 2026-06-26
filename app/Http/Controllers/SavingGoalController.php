<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingGoal;
use App\Models\Transaction; 
use App\Models\Category;    
use App\Models\User;
use App\Services\FinancialService; 
use App\Services\MLService; 
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;          

class SavingGoalController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    private function isProfileIncomplete($user)
    {
        if ($user->role !== 'parent') return false;

        return empty($user->age_category) || 
               empty($user->work_domain) || 
               empty($user->education_level) || 
               empty($user->geographic_zone);
    }

    public function index()
    {
        $user = auth()->user();
        $familyId = $user->family_id;
        
        $obiective = SavingGoal::where('family_id', $familyId)->get();
        
        if ($user->role === 'parent') {
            $fondEconomii = $this->financialService->getFondEconomiiTotal($familyId);
        } else {
            $fondEconomii = $this->financialService->getFondEconomiiCopil($user->id);
        }

        foreach ($obiective as $obiectiv) {
            $obiectiv->insight = $this->financialService->getGoalInsight($user, $obiectiv);
        }

        return view('saving_goals.index', [
            'obiective' => $obiective,
            'fondEconomii' => $fondEconomii,
            'isProfileIncomplete' => $this->isProfileIncomplete($user) 
        ]);
    }


    public function create() 
    { 
        if (auth()->user()->role !== 'parent') {
            return redirect()->back()->with('error', 'Doar părinții pot crea obiective.');
        }

        return view('saving_goals.create', [
            'isProfileIncomplete' => $this->isProfileIncomplete(auth()->user())
        ]); 
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'parent') abort(403);

        $request->validate([
            'goal_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'current_amount' => 'required|numeric|min:0|lte:target_amount',
            'target_date' => 'required|date|after:today',
        ], [
            'goal_name.required' => 'Te rog să dai un nume obiectivului.',
            'target_amount.required' => 'Suma țintă este obligatorie.',
            'target_amount.numeric' => 'Suma țintă trebuie să fie un număr valid.',
            'target_amount.min' => 'Suma țintă trebuie să fie de minimum 1 RON.',
            'current_amount.required' => 'Specifică suma curentă (chiar dacă e 0).',
            'current_amount.min' => 'Suma curentă nu poate fi negativă.',
            'target_date.required' => 'Data limită este obligatorie.',
            'target_date.date' => 'Introdu o dată calendaristică validă.',
            'target_date.after' => 'Data limită trebuie să fie în viitor.',
        ]);

        $transferCategory = Category::where('name', 'Transfer către Economii')->first();

        DB::transaction(function () use ($request, $transferCategory) {
            $goal = SavingGoal::create([
                'family_id' => auth()->user()->family_id,
                'goal_name' => $request->goal_name,
                'target_amount' => $request->target_amount,
                'current_amount' => $request->current_amount,
                'target_date' => $request->target_date,
            ]);

            if ($request->current_amount > 0) {
                Transaction::create([
                    'family_id' => auth()->user()->family_id,
                    'user_id_allocated' => auth()->user()->id,
                    'category_id_final' => $transferCategory->id,
                    'description' => 'Depunere inițială: ' . $goal->goal_name,
                    'amount' => $request->current_amount,
                    'type' => 'expense',
                    'transaction_date' => Carbon::now(),
                ]);
            }
        });

        return redirect()->route('saving-goals.index')->with('success', 'Obiectiv adăugat!');
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'parent') return redirect()->back();

        $goal = SavingGoal::where('id', $id)->where('family_id', auth()->user()->family_id)->firstOrFail();
        return view('saving_goals.edit', ['goal' => $goal]);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'parent') abort(403);

        $goal = SavingGoal::where('id', $id)->where('family_id', auth()->user()->family_id)->firstOrFail();
        $request->validate(['goal_name' => 'required', 'target_amount' => 'required', 'target_date' => 'required']);
        $goal->update($request->only(['goal_name', 'target_amount', 'target_date']));
        return redirect()->route('saving-goals.index')->with('success', 'Actualizat!');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'parent') abort(403);

        $goal = SavingGoal::where('id', $id)
                        ->where('family_id', auth()->user()->family_id)
                        ->firstOrFail();

        \App\Models\Transaction::where('family_id', auth()->user()->family_id)
                            ->where('category_id', $goal->category_id) 
                            ->delete();

        $goal->delete();

        return redirect()->route('saving-goals.index')->with('success', 'Obiectivul și tranzacțiile asociate au fost șterse!');
    }

    public function addFunds(Request $request, $id)
    {
        $request->validate(['amount_to_add' => 'required|numeric|min:1']);
        $goal = SavingGoal::where('id', $id)->where('family_id', auth()->user()->family_id)->firstOrFail();
        
        DB::transaction(function () use ($goal, $request) {
            $goal->increment('current_amount', $request->amount_to_add);
            Transaction::create([
                'family_id' => $goal->family_id,
                'user_id_allocated' => auth()->user()->id,
                'category_id_final' => Category::where('name', 'Transfer către Economii')->first()->id,
                'description' => 'Adăugare fonduri: ' . $goal->goal_name,
                'amount' => $request->amount_to_add,
                'type' => 'expense',
                'transaction_date' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Bani adăugați!');
    }

    public function withdrawFunds(Request $request, $id)
    {
        if (auth()->user()->role !== 'parent') return redirect()->back()->with('error', 'Acces refuzat.');

        $request->validate(['amount_to_withdraw' => 'required|numeric|min:1']);
        $goal = SavingGoal::where('id', $id)->where('family_id', auth()->user()->family_id)->firstOrFail();

        if ($request->amount_to_withdraw > $goal->current_amount) return redirect()->back()->withErrors(['msg' => 'Fonduri insuficiente!']);

        DB::transaction(function () use ($goal, $request) {
            $goal->decrement('current_amount', $request->amount_to_withdraw);
            Transaction::create([
                'family_id' => $goal->family_id,
                'user_id_allocated' => auth()->user()->id,
                'category_id_final' => Category::where('name', 'Bani din Economii')->first()->id,
                'description' => 'Retragere din: ' . $goal->goal_name,
                'amount' => $request->amount_to_withdraw,
                'type' => 'income',
                'transaction_date' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Bani retrași!');
    }

    public function withdrawGlobal(Request $request)
    {
        $request->validate(['amount_to_withdraw' => 'required|numeric|min:0.01']);
        $user = auth()->user();

        if ($user->role === 'parent') {
            $fondDisponibil = $this->financialService->getFondEconomiiTotal($user->family_id);
            $tipTranzactie = 'income';
            $categoryName = 'Bani din Economii';
        } else {
            $fondDisponibil = $this->financialService->getFondEconomiiCopil($user->id);
            $tipTranzactie = 'expense';
            $categoryName = 'Bani din Economii';
        }

        if ($request->amount_to_withdraw > $fondDisponibil) return redirect()->back()->with('error', 'Suma depășește soldul disponibil!');

        Transaction::create([
            'family_id' => $user->family_id,
            'user_id_allocated' => $user->id,
            'category_id_final' => Category::where('name', $categoryName)->first()->id,
            'description' => 'Retragere fond economii',
            'amount' => $request->amount_to_withdraw,
            'type' => $tipTranzactie, 
            'transaction_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Retragere efectuată!');
    }
}