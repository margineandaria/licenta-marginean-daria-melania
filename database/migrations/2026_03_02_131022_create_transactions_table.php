<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void 
    {
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('family_id');
        $table->foreignId('user_id_allocated');
        $table->foreignId('category_id_ai')->nullable();
        $table->foreignId('category_id_final')->nullable();
        $table->string('description');
        $table->decimal('amount', 12, 2);
        
        $table->enum('type', ['income', 'expense'])->default('expense');
        
        $table->datetime('transaction_date');
        $table->string('payment_method')->nullable();
        $table->boolean('is_anomaly')->default(false);
        $table->timestamps();
    });


    }
   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
