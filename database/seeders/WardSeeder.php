<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wards = [
            // Wards for "Thành phố Vũng Tàu"
            ['name' => 'Phường 1', 'code' => 26506, 'division_type' => 'phường', 'codename' => 'phuong_1', 'district_code' => 747],
            ['name' => 'Phường Thắng Tam', 'code' => 26508, 'division_type' => 'phường', 'codename' => 'phuong_thang_tam', 'district_code' => 747],
            ['name' => 'Phường 2', 'code' => 26509, 'division_type' => 'phường', 'codename' => 'phuong_2', 'district_code' => 747],
            ['name' => 'Phường 3', 'code' => 26512, 'division_type' => 'phường', 'codename' => 'phuong_3', 'district_code' => 747],
            ['name' => 'Phường 4', 'code' => 26515, 'division_type' => 'phường', 'codename' => 'phuong_4', 'district_code' => 747],
            ['name' => 'Phường 5', 'code' => 26518, 'division_type' => 'phường', 'codename' => 'phuong_5', 'district_code' => 747],
            ['name' => 'Phường Thắng Nhì', 'code' => 26521, 'division_type' => 'phường', 'codename' => 'phuong_thang_nhi', 'district_code' => 747],
            ['name' => 'Phường 7', 'code' => 26524, 'division_type' => 'phường', 'codename' => 'phuong_7', 'district_code' => 747],
            ['name' => 'Phường Nguyễn An Ninh', 'code' => 26526, 'division_type' => 'phường', 'codename' => 'phuong_nguyen_an_ninh', 'district_code' => 747],
            ['name' => 'Phường 8', 'code' => 26527, 'division_type' => 'phường', 'codename' => 'phuong_8', 'district_code' => 747],
            ['name' => 'Phường 9', 'code' => 26530, 'division_type' => 'phường', 'codename' => 'phuong_9', 'district_code' => 747],
            ['name' => 'Phường Thắng Nhất', 'code' => 26533, 'division_type' => 'phường', 'codename' => 'phuong_thang_nhat', 'district_code' => 747],
            ['name' => 'Phường Rạch Dừa', 'code' => 26535, 'division_type' => 'phường', 'codename' => 'phuong_rach_dua', 'district_code' => 747],
            ['name' => 'Phường 10', 'code' => 26536, 'division_type' => 'phường', 'codename' => 'phuong_10', 'district_code' => 747],
            ['name' => 'Phường 11', 'code' => 26539, 'division_type' => 'phường', 'codename' => 'phuong_11', 'district_code' => 747],
            ['name' => 'Phường 12', 'code' => 26542, 'division_type' => 'phường', 'codename' => 'phuong_12', 'district_code' => 747],
            ['name' => 'Xã Long Sơn', 'code' => 26545, 'division_type' => 'xã', 'codename' => 'xa_long_son', 'district_code' => 747],

            // Wards for "Thành phố Bà Rịa"
            ['name' => 'Phường Phước Hưng', 'code' => 26548, 'division_type' => 'phường', 'codename' => 'phuong_phuoc_hung', 'district_code' => 748],
            ['name' => 'Phường Phước Hiệp', 'code' => 26551, 'division_type' => 'phường', 'codename' => 'phuong_phuoc_hiep', 'district_code' => 748],
            ['name' => 'Phường Phước Nguyên', 'code' => 26554, 'division_type' => 'phường', 'codename' => 'phuong_phuoc_nguyen', 'district_code' => 748],
            ['name' => 'Phường Long Toàn', 'code' => 26557, 'division_type' => 'phường', 'codename' => 'phuong_long_toan', 'district_code' => 748],
            ['name' => 'Phường Long Tâm', 'code' => 26558, 'division_type' => 'phường', 'codename' => 'phuong_long_tam', 'district_code' => 748],
            ['name' => 'Phường Phước Trung', 'code' => 26560, 'division_type' => 'phường', 'codename' => 'phuong_phuoc_trung', 'district_code' => 748],
            ['name' => 'Phường Long Hương', 'code' => 26563, 'division_type' => 'phường', 'codename' => 'phuong_long_huong', 'district_code' => 748],
            ['name' => 'Phường Kim Dinh', 'code' => 26566, 'division_type' => 'phường', 'codename' => 'phuong_kim_dinh', 'district_code' => 748],
            ['name' => 'Xã Tân Hưng', 'code' => 26567, 'division_type' => 'xã', 'codename' => 'xa_tan_hung', 'district_code' => 748],
            ['name' => 'Xã Long Phước', 'code' => 26569, 'division_type' => 'xã', 'codename' => 'xa_long_phuoc', 'district_code' => 748],
            ['name' => 'Xã Hoà Long', 'code' => 26572, 'division_type' => 'xã', 'codename' => 'xa_hoa_long', 'district_code' => 748],

            // Wards for "Huyện Châu Đức"
            ['name' => 'Xã Bàu Chinh', 'code' => 26574, 'division_type' => 'xã', 'codename' => 'xa_bau_chinh', 'district_code' => 750],
            ['name' => 'Thị trấn Ngãi Giao', 'code' => 26575, 'division_type' => 'thị trấn', 'codename' => 'thi_tran_ngai_giao', 'district_code' => 750],
            ['name' => 'Xã Bình Ba', 'code' => 26578, 'division_type' => 'xã', 'codename' => 'xa_binh_ba', 'district_code' => 750],
            ['name' => 'Xã Suối Nghệ', 'code' => 26581, 'division_type' => 'xã', 'codename' => 'xa_suoi_nghe', 'district_code' => 750],
            ['name' => 'Xã Xuân Sơn', 'code' => 26584, 'division_type' => 'xã', 'codename' => 'xa_xuan_son', 'district_code' => 750],
            ['name' => 'Xã Sơn Bình', 'code' => 26587, 'division_type' => 'xã', 'codename' => 'xa_son_binh', 'district_code' => 750],
            ['name' => 'Xã Bình Giã', 'code' => 26590, 'division_type' => 'xã', 'codename' => 'xa_binh_gia', 'district_code' => 750],
            ['name' => 'Xã Bình Trung', 'code' => 26593, 'division_type' => 'xã', 'codename' => 'xa_binh_trung', 'district_code' => 750],
            ['name' => 'Xã Xà Bang', 'code' => 26596, 'division_type' => 'xã', 'codename' => 'xa_xa_bang', 'district_code' => 750],
            ['name' => 'Xã Cù Bị', 'code' => 26599, 'division_type' => 'xã', 'codename' => 'xa_cu_bi', 'district_code' => 750],
            ['name' => 'Xã Láng Lớn', 'code' => 26602, 'division_type' => 'xã', 'codename' => 'xa_lang_lon', 'district_code' => 750],
            ['name' => 'Xã Quảng Thành', 'code' => 26605, 'division_type' => 'xã', 'codename' => 'xa_quang_thanh', 'district_code' => 750],
            ['name' => 'Xã Kim Long', 'code' => 26608, 'division_type' => 'xã', 'codename' => 'xa_kim_long', 'district_code' => 750],
            ['name' => 'Xã Suối Rao', 'code' => 26611, 'division_type' => 'xã', 'codename' => 'xa_suoi_rao', 'district_code' => 750],
            ['name' => 'Xã Đá Bạc', 'code' => 26614, 'division_type' => 'xã', 'codename' => 'xa_da_bac', 'district_code' => 750],
            ['name' => 'Xã Nghĩa Thành', 'code' => 26617, 'division_type' => 'xã', 'codename' => 'xa_nghia_thanh', 'district_code' => 750],

            // Wards for "Huyện Xuyên Mộc"
            ['name' => 'Thị trấn Phước Bửu', 'code' => 26620, 'division_type' => 'thị trấn', 'codename' => 'thi_tran_phuoc_buu', 'district_code' => 751],
            ['name' => 'Xã Phước Thuận', 'code' => 26623, 'division_type' => 'xã', 'codename' => 'xa_phuoc_thuan', 'district_code' => 751],
            ['name' => 'Xã Phước Tân', 'code' => 26626, 'division_type' => 'xã', 'codename' => 'xa_phuoc_tan', 'district_code' => 751],
            ['name' => 'Xã Xuyên Mộc', 'code' => 26629, 'division_type' => 'xã', 'codename' => 'xa_xuyen_moc', 'district_code' => 751],
            ['name' => 'Xã Bông Trang', 'code' => 26632, 'division_type' => 'xã', 'codename' => 'xa_bong_trang', 'district_code' => 751],
            ['name' => 'Xã Tân Lâm', 'code' => 26635, 'division_type' => 'xã', 'codename' => 'xa_tan_lam', 'district_code' => 751],
            ['name' => 'Xã Bàu Lâm', 'code' => 26638, 'division_type' => 'xã', 'codename' => 'xa_bau_lam', 'district_code' => 751],
            ['name' => 'Xã Hòa Bình', 'code' => 26641, 'division_type' => 'xã', 'codename' => 'xa_hoa_binh', 'district_code' => 751],
            ['name' => 'Xã Hòa Hưng', 'code' => 26644, 'division_type' => 'xã', 'codename' => 'xa_hoa_hung', 'district_code' => 751],
            ['name' => 'Xã Hòa Hiệp', 'code' => 26647, 'division_type' => 'xã', 'codename' => 'xa_hoa_hiep', 'district_code' => 751],
            ['name' => 'Xã Hòa Hội', 'code' => 26650, 'division_type' => 'xã', 'codename' => 'xa_hoa_hoi', 'district_code' => 751],
            ['name' => 'Xã Bưng Riềng', 'code' => 26653, 'division_type' => 'xã', 'codename' => 'xa_bung_rieng', 'district_code' => 751],
            ['name' => 'Xã Bình Châu', 'code' => 26656, 'division_type' => 'xã', 'codename' => 'xa_binh_chau', 'district_code' => 751],

            // Wards for "Huyện Long Điền"
            ['name' => 'Thị trấn Long Điền', 'code' => 26659, 'division_type' => 'thị trấn', 'codename' => 'thi_tran_long_dien', 'district_code' => 752],
            ['name' => 'Thị trấn Long Hải', 'code' => 26662, 'division_type' => 'thị trấn', 'codename' => 'thi_tran_long_hai', 'district_code' => 752],
            ['name' => 'Xã An Ngãi', 'code' => 26665, 'division_type' => 'xã', 'codename' => 'xa_an_ngai', 'district_code' => 752],
            ['name' => 'Xã Tam Phước', 'code' => 26668, 'division_type' => 'xã', 'codename' => 'xa_tam_phuoc', 'district_code' => 752],
            ['name' => 'Xã An Nhứt', 'code' => 26671, 'division_type' => 'xã', 'codename' => 'xa_an_nhut', 'district_code' => 752],
            ['name' => 'Xã Phước Tỉnh', 'code' => 26674, 'division_type' => 'xã', 'codename' => 'xa_phuoc_tinh', 'district_code' => 752],
            ['name' => 'Xã Phước Hưng', 'code' => 26677, 'division_type' => 'xã', 'codename' => 'xa_phuoc_hung', 'district_code' => 752],

            // Wards for "Huyện Đất Đỏ"
            ['name' => 'Thị trấn Đất Đỏ', 'code' => 26680, 'division_type' => 'thị trấn', 'codename' => 'thi_tran_dat_do', 'district_code' => 753],
            ['name' => 'Xã Phước Long Thọ', 'code' => 26683, 'division_type' => 'xã', 'codename' => 'xa_phuoc_long_tho', 'district_code' => 753],
            ['name' => 'Xã Phước Hội', 'code' => 26686, 'division_type' => 'xã', 'codename' => 'xa_phuoc_hoi', 'district_code' => 753],
            ['name' => 'Xã Long Mỹ', 'code' => 26689, 'division_type' => 'xã', 'codename' => 'xa_long_my', 'district_code' => 753],
            ['name' => 'Thị trấn Phước Hải', 'code' => 26692, 'division_type' => 'thị trấn', 'codename' => 'thi_tran_phuoc_hai', 'district_code' => 753],
            ['name' => 'Xã Long Tân', 'code' => 26695, 'division_type' => 'xã', 'codename' => 'xa_long_tan', 'district_code' => 753],
            ['name' => 'Xã Láng Dài', 'code' => 26698, 'division_type' => 'xã', 'codename' => 'xa_lang_dai', 'district_code' => 753],
            ['name' => 'Xã Lộc An', 'code' => 26701, 'division_type' => 'xã', 'codename' => 'xa_loc_an', 'district_code' => 753],

            // Wards for "Thị xã Phú Mỹ"
            ['name' => 'Phường Phú Mỹ', 'code' => 26704, 'division_type' => 'phường', 'codename' => 'phuong_phu_my', 'district_code' => 754],
            ['name' => 'Xã Tân Hoà', 'code' => 26707, 'division_type' => 'xã', 'codename' => 'xa_tan_hoa', 'district_code' => 754],
            ['name' => 'Xã Tân Hải', 'code' => 26710, 'division_type' => 'xã', 'codename' => 'xa_tan_hai', 'district_code' => 754],
            ['name' => 'Phường Phước Hoà', 'code' => 26713, 'division_type' => 'phường', 'codename' => 'phuong_phuoc_hoa', 'district_code' => 754],
            ['name' => 'Phường Tân Phước', 'code' => 26716, 'division_type' => 'phường', 'codename' => 'phuong_tan_phuoc', 'district_code' => 754],
            ['name' => 'Phường Mỹ Xuân', 'code' => 26719, 'division_type' => 'phường', 'codename' => 'phuong_my_xuan', 'district_code' => 754],
            ['name' => 'Xã Sông Xoài', 'code' => 26722, 'division_type' => 'xã', 'codename' => 'xa_song_xoai', 'district_code' => 754],
            ['name' => 'Phường Hắc Dịch', 'code' => 26725, 'division_type' => 'phường', 'codename' => 'phuong_hac_dich', 'district_code' => 754],
            ['name' => 'Xã Châu Pha', 'code' => 26728, 'division_type' => 'xã', 'codename' => 'xa_chau_pha', 'district_code' => 754],
            ['name' => 'Xã Tóc Tiên', 'code' => 26731, 'division_type' => 'xã', 'codename' => 'xa_toc_tien', 'district_code' => 754],

            // Huyện Côn Đảo hiện chỉ có một vùng huyện đảo không chia thành phường/xã cụ thể
        ];

        DB::table('phuong_xa')->insert($wards);
    }
}
