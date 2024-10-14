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
        Schema::create('sua_chua', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuyen_duong_id');
            $table->unsignedBigInteger('don_vi_id');
            $table->date('ngay_khoi_cong');
            $table->date('ngay_hoan_thanh');
            $table->double('tu_km', 8, 2)->default(0);
            $table->double('den_km', 8, 2)->default(0);
            $table->date('ngay_duyet');
            $table->unsignedBigInteger('nguoi_duyet_id');
            $table->tinyInteger('loai_sua_chua')->default(1);
            $table->timestamps();

            $table->foreign('tuyen_duong_id')->references('id')->on('tuyen_duong')->onDelete('cascade');
            $table->foreign('don_vi_id')->references('id')->on('don_vi')->onDelete('cascade');
            $table->foreign('nguoi_duyet_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sua_chua');
    }
};
