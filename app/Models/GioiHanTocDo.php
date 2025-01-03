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
        'noi_dung',
        'tu_ngay',
        'den_ngay',
        'tu_km',
        'den_km',
        'gioi_han_toc_do_id',
        'tai_lieu',
        
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
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','gioi_han_toc_do');
        //    ->where('danh_muc', DanhMucTaiLieu::toc_do->value);
    }
    public function diem_gioi_han_toc_do()
    {
        return $this->hasMany(DiemGioiHanTocDo::class, 'gioi_han_toc_do_id', 'id')->where('isdelete',0);
    }
    public function doan_duong()
    {
        return $this->hasMany(GioiHanTocDo::class, 'gioi_han_toc_do_id', 'id')->where('isdelete',0);
    }
}
