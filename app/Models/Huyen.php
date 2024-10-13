<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huyen extends Model
{
    use HasFactory;

    protected $table = 'quan_huyen';

    public function xa()
    {
        return $this->hasMany(Xa::class, 'district_code', 'code');
    }
}
