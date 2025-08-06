<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DenGiaoThong;

class DenGiaoThongSeeder extends Seeder
{
    public function run(): void
    {
        $denGiaoThong = [
            [
                'nut_giao' => 'Ngã tư Lê Lợi - Nguyễn Huệ',
                'so_pha_den' => 4,
                'thoi_gian_pha_den' => 30,
                'thoi_gian_hoat_dong' => '06:00-22:00',
                'vi_tri' => 'Ngã tư trung tâm thành phố',
                'isdelete' => 0,
                'ngay_lap_dat' => '2024-01-15',
                'lat' => 10.495088,
                'lng' => 107.242997
            ],
            [
                'nut_giao' => 'Ngã tư Trần Hưng Đạo - Lý Tự Trọng',
                'so_pha_den' => 3,
                'thoi_gian_pha_den' => 25,
                'thoi_gian_hoat_dong' => '05:30-23:00',
                'vi_tri' => 'Khu vực trường học',
                'isdelete' => 0,
                'ngay_lap_dat' => '2024-02-20',
                'lat' => 10.485088,
                'lng' => 107.232997
            ],
            [
                'nut_giao' => 'Ngã tư Võ Văn Tần - Điện Biên Phủ',
                'so_pha_den' => 5,
                'thoi_gian_pha_den' => 35,
                'thoi_gian_hoat_dong' => '06:00-24:00',
                'vi_tri' => 'Khu vực thương mại',
                'isdelete' => 0,
                'ngay_lap_dat' => '2024-03-10',
                'lat' => 10.505088,
                'lng' => 107.252997
            ],
            [
                'nut_giao' => 'Ngã tư Nguyễn Thị Minh Khai - Cách Mạng Tháng 8',
                'so_pha_den' => 4,
                'thoi_gian_pha_den' => 28,
                'thoi_gian_hoat_dong' => '05:00-22:30',
                'vi_tri' => 'Khu vực dân cư',
                'isdelete' => 0,
                'ngay_lap_dat' => '2024-01-25',
                'lat' => 10.475088,
                'lng' => 107.222997
            ],
            [
                'nut_giao' => 'Ngã tư Lê Duẩn - Pasteur',
                'so_pha_den' => 3,
                'thoi_gian_pha_den' => 22,
                'thoi_gian_hoat_dong' => '06:30-21:00',
                'vi_tri' => 'Khu vực bệnh viện',
                'isdelete' => 0,
                'ngay_lap_dat' => '2024-02-15',
                'lat' => 10.515088,
                'lng' => 107.262997
            ]
        ];

        foreach ($denGiaoThong as $den) {
            DenGiaoThong::create($den);
        }
    }
}
