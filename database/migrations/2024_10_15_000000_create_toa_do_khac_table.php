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
        Schema::create('toa_do_khac', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->tinyInteger('loai');
            $table->string('mo_ta')->default('');
            $table->double('lat', 10, 6);
            $table->double('lng', 10, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toa_do_khac');
    }
};
