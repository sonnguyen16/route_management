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
        Schema::table('plans', function (Blueprint $table) {
            // Fix precision for longitude and latitude
            // Change from decimal(10,8) to decimal(11,8) to accommodate values like -180.12345678
            $table->decimal('lng', 11, 8)->change();
            $table->decimal('lat', 11, 8)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Rollback to original precision
            $table->decimal('lng', 10, 8)->change();
            $table->decimal('lat', 10, 8)->change();
        });
    }
};
