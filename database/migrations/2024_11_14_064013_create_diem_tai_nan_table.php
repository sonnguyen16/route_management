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
        Schema::create('diem_tai_nan', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('dien_thoai');
            $table->string('dia_chi');
            $table->string('ghi_chu');
            $table->string('loai_diem_tai_nan_id');
            $table->string('lng');
            $table->string('lat');
            $table->string('tuyen_duong_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diem_tai_nan');
    }
};
