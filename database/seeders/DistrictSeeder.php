<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            [
                'name' => 'Thành phố Vũng Tàu',
                'code' => 747,
                'division_type' => 'thành phố',
                'codename' => 'thanh_pho_vung_tau',
                'province_code' => 77
            ],
            [
                'name' => 'Thành phố Bà Rịa',
                'code' => 748,
                'division_type' => 'thành phố',
                'codename' => 'thanh_pho_ba_ria',
                'province_code' => 77
            ],
            [
                'name' => 'Huyện Châu Đức',
                'code' => 750,
                'division_type' => 'huyện',
                'codename' => 'huyen_chau_duc',
                'province_code' => 77
            ],
            [
                'name' => 'Huyện Xuyên Mộc',
                'code' => 751,
                'division_type' => 'huyện',
                'codename' => 'huyen_xuyen_moc',
                'province_code' => 77
            ],
            [
                'name' => 'Huyện Long Điền',
                'code' => 752,
                'division_type' => 'huyện',
                'codename' => 'huyen_long_dien',
                'province_code' => 77
            ],
            [
                'name' => 'Huyện Đất Đỏ',
                'code' => 753,
                'division_type' => 'huyện',
                'codename' => 'huyen_dat_do',
                'province_code' => 77
            ],
            [
                'name' => 'Thị xã Phú Mỹ',
                'code' => 754,
                'division_type' => 'thị xã',
                'codename' => 'thi_xa_phu_my',
                'province_code' => 77
            ],
            [
                'name' => 'Huyện Côn Đảo',
                'code' => 755,
                'division_type' => 'huyện',
                'codename' => 'huyen_con_dao',
                'province_code' => 77
            ],
        ];

        DB::table('quan_huyen')->insert($districts);
    }
}
