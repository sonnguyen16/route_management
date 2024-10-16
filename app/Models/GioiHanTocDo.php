<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GioiHanTocDo extends Model
{
    use HasFactory;

    protected $table = 'gioi_han_toc_do';

    protected $fillable = [
        'tuyen_duong_id',
        'don_vi_id',
        'toc_do',
        'tu_km',
        'den_km',
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class, 'tuyen_duong_id');
    }

    public function don_vi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id');
    }

    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'tuyen_duong_id', 'tuyen_duong_id')
            ->where('danh_muc', DanhMucTaiLieu::toc_do->value);
    }
}
