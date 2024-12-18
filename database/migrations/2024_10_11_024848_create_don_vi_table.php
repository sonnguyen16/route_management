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
        Schema::create('don_vi', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('dia_chi');
            $table->string('dien_thoai');
            $table->string('email');
            $table->string('fax');
            $table->string('website');
            $table->string('ma_so_thue');
            $table->string('lien_he');
            $table->tinyInteger('loai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_vi');
    }
};
