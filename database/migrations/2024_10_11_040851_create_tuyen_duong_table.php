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
        Schema::create('tuyen_duong', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->tinyInteger('loai');
            $table->tinyInteger('ma_phan_cap');
            $table->tinyInteger('diem_dau_huyen_id')->nullable();
            $table->tinyInteger('diem_dau_xa_id')->nullable();
            $table->tinyInteger('diem_cuoi_huyen_id')->nullable();
            $table->tinyInteger('diem_cuoi_xa_id')->nullable();
            $table->double('chieu_dai', 8, 2)->default(0);
            $table->double('chieu_rong', 8, 2)->default(0);
            $table->double('dien_tich', 8, 2)->default(0);
            $table->tinyInteger('loai_tuan_tra');
            $table->string('don_vi_quan_ly');
            $table->string('xi_nghiep');
            $table->integer('huyen_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuyen_duong');
    }
};
