<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonVi extends Model
{
    use HasFactory;

    protected $table = 'don_vi';

    protected $fillable = [
        'ten',
        'dia_chi',
        'dien_thoai',
        'email',
        'fax',
        'website',
        'ma_so_thue',
        'lien_he',
        'loai'
    ];
    public function tai_lieu()
    {
        return $this->hasMany(TaiLieu::class, 'danh_muc', 'id')->where('isdelete',0)->where('type','don_vi');
    }
}