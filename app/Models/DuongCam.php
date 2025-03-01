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
        'tu_km',
        'den_km',
        'noi_dung',
        'duong_cam_id',
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
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','duong_cam');;
           // ->where('danh_muc', DanhMucTaiLieu::sua_chua->value);
    }
    public function diem_cam()
    {
        return $this->hasMany(DiemCam::class, 'duong_cam_id', 'id')->where('isdelete',0);
    }
    public function doan_duong()
    {
        return $this->hasMany(DuongCam::class, 'duong_cam_id', 'id')->where('isdelete',0);
    }

    public function toa_do()
    {
        return $this->hasMany(ToaDo::class, 'parent_id', 'id')->where('type', 'duong_cam');
    }
}
