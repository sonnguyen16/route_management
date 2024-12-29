<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapPhepDiem extends Model
{
    use HasFactory;

    protected $table = 'cap_phep_diem';

    protected $fillable = [
        'cap_phep_id',
        'ten',
        'tu_km',
        'den_km',
        'tu_ngay',
        'den_ngay',
        'noi_dung'
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class);
    }

    public function cap_phep()
    {
        return $this->belongsTo(CapPhep::class,'cap_phep_id');
    }

}
