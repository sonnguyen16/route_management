<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaiLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tai_lieu = [
          [
              'ten' => 'TaiLieuPMTramCan_20240716.pdf',
              'tuyen_duong_id' => 1,
              'file' => 'tuyen_duong/hLQBSAvbGxqu8z02kKVPQepec66ioehZ2M2zTZxp.pdf',
                'danh_muc' => 1
          ],
          [
              'ten' => 'TaiLieuPMXuaNhapHang.docx.pdf',
              'tuyen_duong_id' => 1,
              'file' => 'tuyen_duong/t6VgAs2ttRAyxW39gHXRedOtfH8tRHkm2hfTEWb6.pdf',
                'danh_muc' => 1
          ],
          [
                'ten' => 'TaiLieuPMTramCan_20240716.pdf',
                'tuyen_duong_id' => 2,
                'file' => 'tuyen_duong/hLQBSAvbGxqu8z02kKVPQepec66ioehZ2M2zTZxp.pdf',
                'danh_muc' => 1
          ],
          [
                'ten' => 'TaiLieuPMXuaNhapHang.docx.pdf',
                'tuyen_duong_id' => 2,
                'file' => 'tuyen_duong/t6VgAs2ttRAyxW39gHXRedOtfH8tRHkm2hfTEWb6.pdf',
                'danh_muc' => 1
          ]
        ];

        DB::table('tai_lieu')->insert($tai_lieu);
    }
}
