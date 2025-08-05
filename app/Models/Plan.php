<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lng',
        'lat',
        'description',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'lng' => 'decimal:8',
        'lat' => 'decimal:8',
    ];

    /**
     * Get the user that owns the plan
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if plan is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if plan is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
