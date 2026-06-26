<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class MultiMonthTransactionsSeeder extends Seeder
{
    public function run(): void
    {
        $allUsers = User::orderBy('id')->get();
        if ($allUsers->isEmpty()) {
            $this->command->error('Nu există useri în baza de date!');
            return;
        }

        $tata = $allUsers[0];
        $mama = $allUsers[1] ?? $tata;
        $copil1 = $allUsers[2] ?? $mama;
        $copil2 = $allUsers[3] ?? $mama;
        $familyId = $tata->family_id;

        $categories = Category::all()->keyBy('name');
        $getCatId = function($name) use ($categories) {
            return $categories[$name]->id ?? null;
        };

        Transaction::where('family_id', $familyId)
            ->whereYear('transaction_date', 2026)
            ->whereIn(\DB::raw('MONTH(transaction_date)'), [2, 3, 4, 5])
            ->delete();

        $months = [
            2 => ['sal_tata' => 7000, 'sal_mama' => 6000, 'aloc' => 512, 'rata' => 3200, 'apa' => 400, 'intretinere' => 850, 'gaz' => 580, 'curent' => 280, 'digi' => 165, 'alimente' => 1500, 'benzina' => 1100, 'educatie' => 450, 'sanatate1' => 180, 'sanatate2' => 125, 'haine1' => 350, 'haine2' => 420, 'buzunar' => 300, 'divertisment' => 400, 'economii' => 1000],
            3 => ['sal_tata' => 7000, 'sal_mama' => 6000, 'aloc' => 512, 'rata' => 3200, 'apa' => 380, 'intretinere' => 820, 'gaz' => 450, 'curent' => 310, 'digi' => 165, 'alimente' => 1650, 'benzina' => 1200, 'educatie' => 500, 'sanatate1' => 95, 'sanatate2' => 0, 'haine1' => 280, 'haine2' => 0, 'buzunar' => 300, 'divertisment' => 550, 'economii' => 1200],
            4 => ['sal_tata' => 7000, 'sal_mama' => 6000, 'aloc' => 512, 'rata' => 3200, 'apa' => 350, 'intretinere' => 780, 'gaz' => 320, 'curent' => 290, 'digi' => 165, 'alimente' => 1700, 'benzina' => 1050, 'educatie' => 400, 'sanatate1' => 150, 'sanatate2' => 90, 'haine1' => 0, 'haine2' => 500, 'buzunar' => 300, 'divertisment' => 600, 'economii' => 1500],
            5 => ['sal_tata' => 7000, 'sal_mama' => 6000, 'aloc' => 512, 'rata' => 3200, 'apa' => 320, 'intretinere' => 750, 'gaz' => 250, 'curent' => 270, 'digi' => 165, 'alimente' => 1800, 'benzina' => 1150, 'educatie' => 350, 'sanatate1' => 200, 'sanatate2' => 0, 'haine1' => 450, 'haine2' => 300, 'buzunar' => 350, 'divertisment' => 700, 'economii' => 1000],
        ];

        foreach ($months as $month => $v) {
            $transactions = [
                ['desc' => 'Salariu Tata',              'amount' => $v['sal_tata'],   'type' => 'income',  'cat' => 'Salariu',                  'day' => 4,  'u' => $tata->id],
                ['desc' => 'Salariu Mama',              'amount' => $v['sal_mama'],   'type' => 'income',  'cat' => 'Salariu',                  'day' => 5,  'u' => $mama->id],
                ['desc' => 'Alocație copii',            'amount' => $v['aloc'],       'type' => 'income',  'cat' => 'Alocație Stat',            'day' => 10, 'u' => $mama->id],

                ['desc' => 'Rată Bancă',                'amount' => $v['rata'],       'type' => 'expense', 'cat' => 'Rate Bancare',             'day' => 1,  'u' => $tata->id],

                ['desc' => 'Factură Apă Nova',          'amount' => $v['apa'],        'type' => 'expense', 'cat' => 'Utilități',                'day' => 8,  'u' => $tata->id],
                ['desc' => 'Întreținere Bloc',          'amount' => $v['intretinere'],'type' => 'expense', 'cat' => 'Utilități',                'day' => 15, 'u' => $tata->id],
                ['desc' => 'Factură Gaz',               'amount' => $v['gaz'],        'type' => 'expense', 'cat' => 'Utilități',                'day' => 10, 'u' => $tata->id],
                ['desc' => 'Factură Curent',            'amount' => $v['curent'],     'type' => 'expense', 'cat' => 'Utilități',                'day' => 12, 'u' => $tata->id],
                ['desc' => 'Abonament Digi',            'amount' => $v['digi'],       'type' => 'expense', 'cat' => 'Utilități',                'day' => 14, 'u' => $mama->id],

                ['desc' => 'Cumpărături Săptămânale',   'amount' => $v['alimente'],   'type' => 'expense', 'cat' => 'Alimente',                 'day' => 15, 'u' => $mama->id],

                ['desc' => 'Plinuri Benzină',           'amount' => $v['benzina'],    'type' => 'expense', 'cat' => 'Transport',                'day' => 20, 'u' => $tata->id],

                ['desc' => 'Activități Școală',         'amount' => $v['educatie'],   'type' => 'expense', 'cat' => 'Educație',                 'day' => 5,  'u' => $mama->id],

                ['desc' => 'Ieșire weekend familie',    'amount' => $v['divertisment'],'type' => 'expense','cat' => 'Divertisment',             'day' => 22, 'u' => $tata->id],

                ['desc' => 'Bani buzunar Copil 1',      'amount' => $v['buzunar'],    'type' => 'expense', 'cat' => 'Bani de Buzunar',          'day' => 1,  'u' => $copil1->id],
                ['desc' => 'Bani buzunar Copil 2',      'amount' => $v['buzunar'],    'type' => 'expense', 'cat' => 'Bani de Buzunar',          'day' => 1,  'u' => $copil2->id],

                ['desc' => 'Transfer către Economii',   'amount' => $v['economii'],   'type' => 'expense', 'cat' => 'Transfer către Economii',  'day' => 28, 'u' => $tata->id],
            ];

            if ($v['sanatate1'] > 0) {
                $transactions[] = ['desc' => 'Medicamente (Farmacia Tei)', 'amount' => $v['sanatate1'], 'type' => 'expense', 'cat' => 'Sănătate', 'day' => 7, 'u' => $mama->id];
            }
            if ($v['sanatate2'] > 0) {
                $transactions[] = ['desc' => 'Vitamine & Suplimente', 'amount' => $v['sanatate2'], 'type' => 'expense', 'cat' => 'Sănătate', 'day' => 18, 'u' => $tata->id];
            }

            if ($v['haine1'] > 0) {
                $descriptions1 = [2 => 'Adidași Adidas (Copil 1)', 3 => 'Ghete primăvară copii', 4 => 'Tricouri sport', 5 => 'Sandale copii Nike'];
                $transactions[] = ['desc' => $descriptions1[$month], 'amount' => $v['haine1'], 'type' => 'expense', 'cat' => 'Îmbrăcăminte', 'day' => 21, 'u' => $mama->id];
            }
            if ($v['haine2'] > 0) {
                $descriptions2 = [2 => 'Haine de iarnă Zara', 3 => 'N/A', 4 => 'Haine primăvară H&M', 5 => 'Rochie + pantaloni Reserved'];
                $transactions[] = ['desc' => $descriptions2[$month], 'amount' => $v['haine2'], 'type' => 'expense', 'cat' => 'Îmbrăcăminte', 'day' => 11, 'u' => $mama->id];
            }

            foreach ($transactions as $t) {
                $maxDay = $month == 2 ? 28 : ($month == 4 ? 30 : 31);
                $day = min($t['day'], $maxDay);

                Transaction::create([
                    'family_id'         => $familyId,
                    'user_id_allocated' => $t['u'],
                    'description'       => $t['desc'],
                    'amount'            => $t['amount'],
                    'type'              => $t['type'],
                    'payment_method'    => 'Card',
                    'transaction_date'  => Carbon::create(2026, $month, $day, 12, 0, 0),
                    'category_id_final' => $getCatId($t['cat']),
                ]);
            }
        }

        $this->command->info('Tranzacții pentru Februarie-Mai 2026 create cu succes! ');
    }
}