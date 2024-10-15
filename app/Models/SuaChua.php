<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaChua extends Model
{
    use HasFactory;

    protected $table = 'sua_chua';

    protected $fillable = [
        'tuyen_duong_id',
        'don_vi_id',
        'ngay_khoi_cong',
        'ngay_hoan_thanh',
        'tu_km',
        'den_km',
        'ngay_duyet',
        'nguoi_duyet_id',
        'loai_sua_chua',
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class);
    }

    public function don_vi()
    {
        return $this->belongsTo(DonVi::class);
    }

    public function nguoi_duyet()
    {
        return $this->belongsTo(User::class);
    }

    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'tuyen_duong_id', 'tuyen_duong_id');
    }
}
