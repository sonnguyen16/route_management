<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapPhep extends Model
{
    use HasFactory;

    protected $table = 'cap_phep';

    protected $fillable = [
        'tuyen_duong_id',
        'don_vi_id',
        'so_cap_phep',
        'ngay_cap_phep',
        'ngay_het_han',
        'noi_dung',
        'tu_km',
        'den_km',
        'cap_phep_id',
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class);
    }

    public function don_vi()
    {
        return $this->belongsTo(DonVi::class);
    }
    public function cap_phep_diem()
    {
        return $this->hasMany(CapPhepDiem::class, 'cap_phep_id', 'id')->where('isdelete',0);
    }
    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','cap_phep');
      //  return $this->hasMany(TaiLieu::class, 'tuyen_duong_id', 'tuyen_duong_id')
      //      ->where('danh_muc', DanhMucTaiLieu::cap_phep->value);
    }
    public function doan_duong()
    {
        return $this->hasMany(CapPhep::class, 'cap_phep_id', 'id')->where('isdelete',0);
    }

    public function toa_do()
    {
        return $this->hasMany(ToaDo::class, 'parent_id', 'id')->where('type', 'cap_phep');
    }
}
