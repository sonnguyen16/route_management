<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToaDoKhac extends Model
{
    use HasFactory;

    protected $table = 'toa_do_khac';

    protected $fillable = [
        'ten',
        'mo_ta',
        'lat',
        'lng',
        'loai',
    ];

    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'toa_do_id', 'id')
            ->where('danh_muc', DanhMucTaiLieu::toa_do->value);
    }
}
