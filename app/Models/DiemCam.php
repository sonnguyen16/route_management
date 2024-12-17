<?php

namespace App\Models;

use App\Enums\DanhMucTaiLieu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiemCam extends Model
{
    use HasFactory;

    protected $table = 'diem_cam';

    protected $fillable = [
        'duong_cam_id',
        'noi_dung',
        'tu_ngay',
        'den_ngay',
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(DuongCam::class, 'duong_cam_id');
    }

}
