<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


use App\Models\User;
use App\Models\Family;
use App\Models\SavingGoal;
use App\Models\Transaction; 
use App\Models\Budget;

class FamilySeeder extends Seeder
{
    
    public function run(): void
    {
        $family = Family::create([
            'name' => 'Familia Popescu'
        ]);

        $tata = User::create([
            'family_id' => $family->id,
            'name' => 'Tata',
            'email' => 'tata@test.com',
            'password' => Hash::make('parola123'),
            'role' => 'parent',
        ]);

        $mama = User::create([
            'family_id' => $family->id,
            'name' => 'Mama',
            'email' => 'mama@test.com',
            'password' => Hash::make('parola123'),
            'role' => 'parent',
        ]);

        $copil1 = User::create([
            'family_id' => $family->id,
            'name' => 'Băiatul',
            'email' => 'baiat@test.com',
            'password' => Hash::make('parola123'),
            'role' => 'child',
        ]);

        $copil2 = User::create([
            'family_id' => $family->id,
            'name' => 'Fata',
            'email' => 'fata@test.com',
            'password' => Hash::make('parola123'),
            'role' => 'child',
        ]);

        SavingGoal::create([
            'family_id' => $family->id,
            'goal_name' => 'Vacanță Grecia 2026',
            'target_amount' => 10000.00,
            'current_amount' => 2500.00,
            'target_date' => Carbon::now()->addMonths(6),
        ]);

        Transaction::create([
            'family_id' => $family->id,
            'user_id_allocated' => $mama->id,
            'category_id_final' => 2, 
            'description' => 'Cumpărături Kaufland',
            'amount' => 450.00,
            'transaction_date' => Carbon::now()->subDays(2),
            'payment_method' => 'Card',
        ]);

        Transaction::create([
            'family_id' => $family->id,
            'user_id_allocated' => $copil1->id,
            'category_id_final' => 3, 
            'description' => 'Bilete Cinema',
            'amount' => 70.00,
            'transaction_date' => Carbon::now()->subDays(1),
            'payment_method' => 'Cash',
        ]);
    }
}
