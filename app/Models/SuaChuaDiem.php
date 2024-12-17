<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaChuaDiem extends Model
{
    use HasFactory;

    protected $table = 'sua_chua_diem';

    protected $fillable = [
        'id',
        'sua_chua_id',
        'loai_sua_chua_id',
        'tu_km',
        'den_km',
    ];
    public function loai_sua_chua()
    {
        return $this->belongsTo(CauHinh::class, 'loai_sua_chua_id');
    }
}
