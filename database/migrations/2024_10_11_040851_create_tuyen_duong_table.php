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
            $table->tinyInteger('diem_dau_huyen_id');
            $table->tinyInteger('diem_dau_xa_id');
            $table->double('diem_dau_lat', 8, 6);
            $table->double('diem_dau_lng', 8, 6);
            $table->tinyInteger('diem_cuoi_huyen_id');
            $table->tinyInteger('diem_cuoi_xa_id');
            $table->double('diem_cuoi_lat', 8, 6);
            $table->double('diem_cuoi_lng', 8, 6);
            $table->double('chieu_dai', 8, 2)->default(0);
            $table->double('chieu_rong', 8, 2)->default(0);
            $table->double('dien_tich', 8, 2)->default(0);
            $table->tinyInteger('loai_tuan_tra');
            $table->unsignedBigInteger('don_vi_id');
            $table->string('xi_nghiep');
            $table->string('key');
            $table->integer('huyen_id');
            $table->timestamps();

            $table->foreign('don_vi_id')->references('id')->on('don_vi')->onDelete('cascade');
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
