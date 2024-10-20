<?php

namespace Database\Seeders;

use App\Models\TuyenDuong;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $this->call([
            DistrictSeeder::class,
            WardSeeder::class,
            DonViSeeder::class,
        ]);

//        TuyenDuong::factory(100)->create();
        DB::table('tuyen_duong')->insert([
            'ten' => 'Đường Võ Nguyên Giáp',
            'loai' => 1,
            'ma_phan_cap' => 1,
            'diem_dau_huyen_id' => 1,
            'diem_dau_xa_id' => 1,
            'diem_dau_lat' => 10.123456,
            'diem_dau_lng' => 106.123456,
            'diem_cuoi_huyen_id' => 2,
            'diem_cuoi_xa_id' => 18,
            'diem_cuoi_lat' => 10.123456,
            'diem_cuoi_lng' => 106.123456,
            'chieu_dai' => 10.12,
            'chieu_rong' => 10.12,
            'dien_tich' => 10.12,
            'loai_tuan_tra' => 1,
            'don_vi_id' => 1,
            'xi_nghiep' => 'Xí nghiệp 1',
            'key' => 'vo_nguyen_giap',
            'huyen_id' => 1,
            'created_at' => now(),
        ]);
    }
}
