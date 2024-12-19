<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cau extends Model
{
    use HasFactory;

    protected $table = 'cau';

    protected $fillable = [
        'ten',
        'ly_trinh',
        'chieu_rong',
        'chieu_dai',
        'loai_ket_cau_nhip_id',
        'nam_khai_thac',
        'tai_trong',
        'kiem_dinh',
        'tuyen_duong_id',
        'loai_cau_id',
        'vi_tri',
        'isdelete'
    ];
    public function loai_ket_cau_nhip()
    {
        return $this->belongsTo(CauHinh::class, 'loai_ket_cau_nhip_id');
    }
    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class,'tuyen_duong_id');
    }
    public function loai_cau()
    {
        return $this->belongsTo(CauHinh::class, 'loai_cau_id');
    }
    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','cau');
    }
}