<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHinh extends Model
{
    use HasFactory;

    protected $table = 'cau_hinh';

    protected $fillable = [
        'ten',
        'loai'
    ];
}
