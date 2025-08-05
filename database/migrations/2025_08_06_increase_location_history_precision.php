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
        Schema::table('location_histories', function (Blueprint $table) {
            // Tăng precision của lng và lat lên mức cao hơn
            // Từ decimal(10,8) -> decimal(15,12)
            // Cho phép lưu số lớn hơn và chính xác hơn
            $table->decimal('lng', 15, 12)->change();
            $table->decimal('lat', 15, 12)->change();

            // Tăng precision của các trường khác
            $table->decimal('accuracy', 15, 8)->nullable()->change();
            $table->decimal('altitude', 15, 8)->nullable()->change();
            $table->decimal('heading', 8, 4)->nullable()->change();
            $table->decimal('speed', 8, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('location_histories', function (Blueprint $table) {
            // Khôi phục về precision ban đầu
            $table->decimal('lng', 10, 8)->change();
            $table->decimal('lat', 10, 8)->change();
            $table->decimal('accuracy', 8, 2)->nullable()->change();
            $table->decimal('altitude', 8, 2)->nullable()->change();
            $table->decimal('heading', 5, 2)->nullable()->change();
            $table->decimal('speed', 5, 2)->nullable()->change();
        });
    }
};
