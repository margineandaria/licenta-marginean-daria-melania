<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(['name' => 'Salariu', 'type' => 'income']);
        Category::firstOrCreate(['name' => 'Alocație Stat', 'type' => 'income']); 
        Category::firstOrCreate(['name' => 'Alte Venituri', 'type' => 'income']);
        Category::firstOrCreate(['name' => 'Bani din Economii', 'type' => 'income']);

        Category::firstOrCreate(['name' => 'Alimente', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Utilități', 'type' => 'expense']); 
        Category::firstOrCreate(['name' => 'Transport', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Sănătate', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Educație', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Divertisment', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Îmbrăcăminte', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Casă și Întreținere', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Bani de Buzunar', 'type' => 'expense']); 
        Category::firstOrCreate(['name' => 'Transfer către Economii', 'type' => 'expense']); 
                Category::firstOrCreate(['name' => 'Chirie', 'type' => 'expense']);
        Category::firstOrCreate(['name' => 'Rate Bancare', 'type' => 'expense']);

        Category::firstOrCreate(['name' => 'Diverse', 'type' => 'expense']);
    }
}
