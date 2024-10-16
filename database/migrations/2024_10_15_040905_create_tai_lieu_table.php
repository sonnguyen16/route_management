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
        Schema::create('tai_lieu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuyen_duong_id')->nullable();
            $table->unsignedBigInteger('cong_van_id')->nullable();
            $table->integer('danh_muc')->nullable()->default(1);
            $table->string('ten');
            $table->string('mo_ta')->nullable();
            $table->integer('dung_luong');
            $table->string('file');
            $table->string('loai');
            $table->timestamps();

            $table->foreign('tuyen_duong_id')->references('id')->on('tuyen_duong')->onDelete('cascade');
            $table->foreign('cong_van_id')->references('id')->on('cong_van')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tai_lieu');
    }
};
