<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category; 
use App\Models\User;
use App\Services\MLService; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $familyId = $user->family_id;
        Carbon::setLocale('ro');
        $availableMonths = [];
        $startOfThisMonth = Carbon::now()->startOfMonth(); 

        for ($i = 0; $i < 12; $i++) {
            $date = $startOfThisMonth->copy()->subMonths($i); 
            $availableMonths[$date->format('Y-m')] = ucfirst($date->translatedFormat('F Y'));
        }
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        $query = Transaction::with(['categoryFinal', 'categoryAi', 'user'])
                            ->where('family_id', $familyId);

        if ($user->role === 'child') {
            $query->where('user_id_allocated', $user->id);
        }

        if ($selectedMonth) {
            $anLuna = explode('-', $selectedMonth);
            $query->whereYear('transaction_date', $anLuna[0])
                  ->whereMonth('transaction_date', $anLuna[1]);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id_allocated', $request->user_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id_final', $request->category_id);
        }

        $calendarQuery = clone $query;
        $tranzactiiCalendar = $calendarQuery->where('type', 'expense')->get();
        $calendarData = $tranzactiiCalendar->groupBy(function($tranzactie) {
            return Carbon::parse($tranzactie->transaction_date)->format('Y-m-d');
        })->map(function ($tranzactiiPeZi) {
            $totalZi = $tranzactiiPeZi->sum('amount');
            $categoriiPeZi = $tranzactiiPeZi->groupBy(function($t) {
                return $t->categoryFinal->name ?? 'Diverse';
            })->map(function($tranzactiiCategorie) {
                return $tranzactiiCategorie->sum('amount');
            });

            return [
                'total' => $totalZi,
                'categories' => $categoriiPeZi->toArray() 
            ];
        })->toArray();
        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportCsvFiltered($query->orderBy('transaction_date', 'desc')->get(), $selectedMonth);
        }
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(10)->withQueryString();

        $familyMembers = User::where('family_id', $familyId)->get();
        $categories = Category::orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'familyMembers', 'categories', 'availableMonths', 'selectedMonth', 'calendarData'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
        'description' => 'required|string|max:255', 
        'amount' => 'required|numeric|min:0.01', 
        'type' => 'required|in:income,expense', 
        'payment_method' => 'required|string',
        'transaction_date' => 'required|date|before_or_equal:today',
        ], [
        'description.required' => 'Te rog să introduci o descriere pentru tranzacție.',
        'description.max' => 'Descrierea nu poate depăși 255 de caractere.',
        'amount.required' => 'Suma este obligatorie.',
        'amount.numeric' => 'Suma trebuie să fie un număr valid.',
        'amount.min' => 'Suma trebuie să fie de cel puțin 0.01 RON.',
        'type.required' => 'Te rog să alegi tipul tranzacției (Venit/Cheltuială).',
        'payment_method.required' => 'Alege o metodă de plată.',
        'transaction_date.required' => 'Data tranzacției este obligatorie.',
        'transaction_date.date' => 'Data introdusă nu este validă.',
        'transaction_date.before_or_equal' => 'Data tranzacției nu poate fi în viitor.'
        ]);

        $aiResult = MLService::getInstance()->classifyTransaction($request->description);
        $categoryNameFromAI = $aiResult['category']; 
        
        $category = $this->findCategoryByName($categoryNameFromAI);

        Transaction::create([
            'family_id' => Auth::user()->family_id, 
            'user_id_allocated' => Auth::id(), 
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'payment_method' => $request->payment_method, 
            'transaction_date' => $request->transaction_date,
            'category_id_ai' => $category ? $category->id : null,
            'category_id_final' => $category ? $category->id : null,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Tranzacție salvată și categorisită automat!');
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->family_id !== Auth::user()->family_id) {
            abort(403, 'Acces interzis.');
        }

        $categorii = Category::all();
        return view('transactions.edit', compact('transaction', 'categorii'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->family_id !== Auth::user()->family_id) {
            abort(403, 'Acces interzis.');
        }

        $request->validate([
            'description' => 'required|string|max:255', 
            'amount' => 'required|numeric|min:0.01', 
            'type' => 'required|in:income,expense', 
            'payment_method' => 'required|string',
            'transaction_date' => 'required|date',
            'category_id_final' => 'required|exists:categories,id',
        ]);

        $data = $request->only(['description', 'amount', 'type', 'payment_method', 'transaction_date', 'category_id_final']);

        if ($transaction->description !== $request->description) {
            $aiResult = MLService::getInstance()->classifyTransaction($request->description);
            $category = $this->findCategoryByName($aiResult['category']);

            if ($category) {
                $data['category_id_ai'] = $category->id;
                $data['category_id_final'] = $category->id; 
            }
        }

        $transaction->update($data);

        return redirect()->route('transactions.index')->with('success', 'Tranzacția a fost actualizată!');
    }

    private function findCategoryByName($name)
    {
        $nameClean = strtolower(Str::ascii($name));
        $allCategories = Category::all();
        
        return $allCategories->first(function ($cat) use ($nameClean) {
            return strtolower(Str::ascii($cat->name)) === $nameClean;
        }) ?? Category::where('name', 'like', '%Diverse%')->first();
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->family_id !== Auth::user()->family_id) {
            abort(403, 'Acces interzis.');
        }

        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Tranzacția a fost ștearsă!');
    }

    private function exportCsvFiltered($transactions, $selectedMonth)
    {
        $totalIncomes = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncomes - $totalExpenses;

        Carbon::setLocale('ro');
        $monthLabel = $selectedMonth 
            ? ucfirst(Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y'))
            : 'Toate perioadele';

        $safeMonthName = str_replace(' ', '_', $monthLabel);

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Extras_Cont_{$safeMonthName}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($transactions, $monthLabel, $totalIncomes, $totalExpenses, $balance) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); 
            
            fputcsv($file, ['RAPORT FINANCIAR - SmartPocket']);
            fputcsv($file, ['Perioada:', $monthLabel]);
            fputcsv($file, ['Generat la:', Carbon::now()->format('d.m.Y H:i')]);
            fputcsv($file, []); 
            
            fputcsv($file, ['Total Venituri:', number_format($totalIncomes, 2) . ' RON']);
            fputcsv($file, ['Total Cheltuieli:', number_format($totalExpenses, 2) . ' RON']);
            fputcsv($file, ['Balanta perioadei:', number_format($balance, 2) . ' RON']);
            fputcsv($file, []); 
            fputcsv($file, []); 

            fputcsv($file, ['Data', 'Descriere', 'Adaugat de', 'Categorie', 'Tip', 'Metoda Plata', 'Suma (RON)']);
            foreach ($transactions as $t) {
                fputcsv($file, [
                    Carbon::parse($t->transaction_date)->format('d.m.Y'),
                    $t->description,
                    $t->user ? $t->user->name : 'N/A', 
                    $t->categoryFinal ? $t->categoryFinal->name : 'N/A',
                    $t->type == 'income' ? 'Venit' : 'Cheltuiala',
                    ucfirst($t->payment_method),
                    $t->amount
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}