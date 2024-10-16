<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaiLieu extends Model
{
    use HasFactory;

    protected $table = 'tai_lieu';
    protected $fillable = [
        'ten',
        'tuyen_duong_id',
        'cong_van_id',
        'danh_muc',
        'file',
        'loai',
        'dung_luong',
        'mo_ta',
    ];
}
