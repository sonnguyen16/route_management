<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuyenDuong extends Model
{
    use HasFactory;

    protected $table = 'tuyen_duong';
    protected $fillable = [
        'ten',
        'loai_tuyen_duong_id',
        'phan_cap_id',
        'diem_dau_huyen_id',
        'diem_dau_xa_id',
        'diem_dau_lat',
        'diem_dau_lng',
        'diem_cuoi_huyen_id',
        'diem_cuoi_xa_id',
        'diem_cuoi_lat',
        'diem_cuoi_lng',
        'chieu_dai',
        'chieu_rong',
        'dien_tich',
        'lo_gioi',
        'don_vi_id',
        'key',
        'tuyen_duong_id',
        'diem_dau',
        'diem_cuoi',
    ];

    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','duong');
        // return $this->hasMany(TaiLieu::class)
          //  ->where('danh_muc', DanhMucTaiLieu::tuyen_duong->value);
    }

    public function huyen()
    {
        return $this->belongsTo(Huyen::class, 'huyen_id');
    }

    public function diem_dau_huyen()
    {
        return $this->belongsTo(Huyen::class, 'diem_dau_huyen_id');
    }

    public function diem_dau_xa()
    {
        return $this->belongsTo(Xa::class, 'diem_dau_xa_id');
    }

    public function diem_cuoi_huyen()
    {
        return $this->belongsTo(Huyen::class, 'diem_cuoi_huyen_id');
    }

    public function diem_cuoi_xa()
    {
        return $this->belongsTo(Xa::class, 'diem_cuoi_xa_id');
    }

    public function don_vi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id');
    }

    public function toa_do()
    {
        return $this->hasMany(ToaDo::class, 'parent_id', 'id')->where('type', 'tuyen_duong');
    }
    public function loai_tuyen_duong()
    {
        return $this->belongsTo(CauHinh::class, 'loai_tuyen_duong_id');
    }
    public function phan_cap()
    {
        return $this->belongsTo(CauHinh::class, 'phan_cap_id');
    }

    public function doan_duong()
    {
        return $this->hasMany(TuyenDuong::class, 'tuyen_duong_id')->where('isdelete',0);
    }

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class, 'tuyen_duong_id')->where('isdelete',0);
    }
}
