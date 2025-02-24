<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToaDo extends Model
{
    use HasFactory;

    protected $table = 'toado';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'lat',
        'lng',
        'type',
    ];
}
