<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $danh_muc = ['ten' => 'Tài liệu về tuyến đường'];

        DB::table('danh_muc')->insert($danh_muc);
    }
}
