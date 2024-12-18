<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiemGioiHanTocDo extends Model
{
    use HasFactory;

    protected $table = 'diem_gioi_han_toc_do';

    protected $fillable = [
        'gioi_han_toc_do_id',
        'noi_dung',
        'tu_ngay',
        'den_ngay',
    ];
}
