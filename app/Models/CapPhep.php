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
        'ngay_cap_phep'
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class);
    }

    public function don_vi()
    {
        return $this->belongsTo(DonVi::class);
    }

    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'tuyen_duong_id', 'tuyen_duong_id')
            ->where('danh_muc', DanhMucTaiLieu::cap_phep->value);
    }
}
