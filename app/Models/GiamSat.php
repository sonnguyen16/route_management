<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiamSat extends Model
{
    use HasFactory;

    protected $table = 'giam_sat';

    protected $fillable = [
        'tuyen_duong_id',
        'don_vi_id',
        'tai_lieu',
        'hu_hong',
        'vi_tri',
        'muc_do',
        'tinh_trang_khac_phuc',
        'giam_sat_id',
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
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','giam_sat');
    }
    public function doan_duong()
    {
        return $this->hasMany(GiamSat::class, 'giam_sat_id','id')->where('isdelete',0);
    }
}
