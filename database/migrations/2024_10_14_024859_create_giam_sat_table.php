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
        Schema::create('giam_sat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuyen_duong_id');
            $table->unsignedBigInteger('don_vi_id');
            $table->timestamps();

            $table->foreign('tuyen_duong_id')->references('id')->on('tuyen_duong')->onDelete('cascade');
            $table->foreign('don_vi_id')->references('id')->on('don_vi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giam_sat');
    }
};
