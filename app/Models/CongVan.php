<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CongVan extends Model
{
    use HasFactory;

    protected $table = 'cong_van';

    protected $fillable = [
        'ten',
        'loai',
        'don_vi_id',
        'ngay_gui',
        'ngay_nhan',
        'nguoi_xu_ly_id',
        'trang_thai',
        'ghi_chu'
    ];


    public function don_vi()
    {
        return $this->belongsTo(DonVi::class);
    }

    public function nguoi_xu_ly()
    {
        return $this->belongsTo(User::class);
    }

    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'cong_van_id', 'id')
            ->where('danh_muc', DanhMucTaiLieu::cong_van->value);
    }
}
