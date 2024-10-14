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
        Schema::create('cong_van', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->tinyInteger('loai')->default(1);
            $table->unsignedBigInteger('don_vi_id')->nullable();
            $table->date('ngay_gui');
            $table->date('ngay_nhan');
            $table->unsignedBigInteger('nguoi_xu_ly')->nullable();
            $table->tinyInteger('trang_thai')->default(0);
            $table->string('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('don_vi_id')->references('id')->on('don_vi')->onDelete('set null');
            $table->foreign('nguoi_xu_ly')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cong_van');
    }
};
