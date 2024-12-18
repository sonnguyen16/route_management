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
        'isdelete'
    ];
}