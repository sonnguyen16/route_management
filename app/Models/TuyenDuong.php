<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuyenDuong extends Model
{
    use HasFactory;

    protected $table = 'tuyen_duong';
    protected $fillable = [
        'ten',
        'loai',
        'ma_phan_cap',
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
        'loai_tuan_tra',
        'don_vi_id',
        'xi_nghiep',
        'huyen_id',
    ];

    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class);
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
}
