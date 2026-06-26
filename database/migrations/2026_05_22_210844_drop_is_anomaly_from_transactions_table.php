<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('is_anomaly'); // Șterge coloana
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_anomaly')->default(false); // O pune la loc dacă dai rollback (pune tipul corect dacă era altceva)
        });
    }
};
