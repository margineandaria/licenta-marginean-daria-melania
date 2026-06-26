<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Modificăm enum-ul să accepte și 'admin'
            $table->enum('role', ['parent', 'child', 'admin'])->default('parent')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['parent', 'child'])->default('parent')->change();
        });
    }
};
