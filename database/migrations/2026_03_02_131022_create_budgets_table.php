<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id(); // BudgetID
            $table->foreignId('family_id')->constrained('families')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('user_id_responsible')->constrained('users');
            $table->decimal('budget_amount', 12, 2);
            $table->string('month_year'); // ex: "2024-06"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
