<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiemTaiNan extends Model
{
    use HasFactory;

    protected $table = 'diem_tai_nan';
    protected $fillable = [
        'ten',
        'dien_thoai',
        'dia_chi',
        'ghi_chu',
        'loai_diem_tai_nan_id',
        'lng',
        'lat',
        'tuyen_duong_id'
    ];
}