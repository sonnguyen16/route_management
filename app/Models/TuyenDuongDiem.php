<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuyenDuongDiem extends Model
{
    use HasFactory;

    protected $table = 'tuyen_duong_diem';

    protected $fillable = [
        'id',
        'tuyen_duong_id',
        'noi_dung',
        'tu_km',
        'den_km',
        'chieu_rong',
    ];
    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class, 'tuyen_duong_id');
    }
}
