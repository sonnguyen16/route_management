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
        Schema::create('gioi_han_toc_do', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuyen_duong_id');
            $table->double('tu_km', 8, 2)->default(0);
            $table->double('den_km', 8, 2)->default(0);
            $table->double('toc_do', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('tuyen_duong_id')->references('id')->on('tuyen_duong')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gioi_han_toc_do');
    }
};
