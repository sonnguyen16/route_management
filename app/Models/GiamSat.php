<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiamSat extends Model
{
    use HasFactory;

    protected $table = 'giam_sat';

    protected $fillable = [
        'tuyen_duong_id',
        'don_vi_id',
    ];

    public function tuyen_duong()
    {
        return $this->belongsTo(TuyenDuong::class, 'tuyen_duong_id');
    }

    public function don_vi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id');
    }
}
