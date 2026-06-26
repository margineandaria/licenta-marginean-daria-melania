<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ștergem coloana de care nu mai avem nevoie
            $table->dropColumn('monthly_email_report');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Recreăm coloana în caz de rollback
            $table->boolean('monthly_email_report')->default(true);
        });
    }
};