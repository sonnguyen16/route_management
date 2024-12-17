<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuongCam extends Model
{
    use HasFactory;

    protected $table = 'duong_cam';

    protected $fillable = [
        'tuyen_duong_id',
        'don_vi_quyet_dinh_id',
        'don_vi_thuc_hien_id',
        'tu_ngay',
        'den_ngay',
        'ly_do',
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class, 'tuyen_duong_id');
    }

    public function don_vi_quyet_dinh()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_quyet_dinh_id');
    }

    public function don_vi_thuc_hien()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_thuc_hien_id');
    }
    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0);
           // ->where('danh_muc', DanhMucTaiLieu::sua_chua->value);
    }
    public function diem_cam()
    {
        return $this->hasMany(DiemCam::class, 'duong_cam_id', 'id')->where('isdelete',0);
    }
}
