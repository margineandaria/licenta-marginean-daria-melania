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
        Schema::table('transactions', function (Blueprint $table) {
            // Adăugăm DOAR cheile externe peste coloanele care deja există
            $table->foreign('family_id')->references('id')->on('families')->onDelete('cascade');
            $table->foreign('user_id_allocated')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id_ai')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('category_id_final')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // În caz de rollback, ștergem doar legăturile
            $table->dropForeign(['family_id']);
            $table->dropForeign(['user_id_allocated']);
            $table->dropForeign(['category_id_ai']);
            $table->dropForeign(['category_id_final']);
        });
    }
};