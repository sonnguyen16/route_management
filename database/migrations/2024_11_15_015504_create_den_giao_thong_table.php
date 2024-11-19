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
        Schema::create('den_giao_thong', function (Blueprint $table) {
            $table->id();
            $table->string('nut_giao');
            $table->string('so_pha_den');
            $table->string('thoi_gian_pha_den');
            $table->string('thoi_gian_hoat_dong');
            $table->string('lat');
            $table->string('lng');
            $table->tinyInteger('isdelete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('den_giao_thong');
    }
};
