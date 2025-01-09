<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenGiaoThong extends Model
{
    use HasFactory;

    protected $table = 'den_giao_thong';

    protected $fillable = [
        'nut_giao',
        'so_pha_den',
        'thoi_gian_pha_den',
        'thoi_gian_hoat_dong',
        'vi_tri',
        'isdelete',
        'tai_lieu',
        'ngay_lap_dat',
    ];
    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','den_giao_thong');
    }
}