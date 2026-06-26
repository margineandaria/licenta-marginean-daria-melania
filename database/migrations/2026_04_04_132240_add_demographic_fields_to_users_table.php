<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adăugăm datele demografice și financiare necesare pentru estimări
            $table->string('education_level')->nullable()->after('role'); 
            $table->string('work_domain')->nullable()->after('education_level');
            $table->string('geographic_zone')->nullable()->after('work_domain');
            $table->string('age_category')->nullable()->after('geographic_zone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'education_level', 
                'work_domain', 
                'geographic_zone', 
                'age_category'
            ]);
        });
    }
};