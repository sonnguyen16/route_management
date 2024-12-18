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
        Schema::create('cau', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('ly_trinh');
            $table->string('chieu_rong');
            $table->string('chieu_dai');
            $table->tinyInteger('loai_ket_cau_nhip_id');
            $table->string('nam_khai_thac');
            $table->string('tai_trong');
            $table->string('kiem_dinh');
            $table->tinyInteger('tuyen_duong_id');
            $table->tinyInteger('loai_cau_id');
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
        Schema::dropIfExists('cau');
    }
};
