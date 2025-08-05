<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lat',
        'lng',
        'accuracy',
        'altitude',
        'heading',
        'speed',
        'is_active',
        'recorded_at',
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'altitude' => 'decimal:2',
        'heading' => 'decimal:2',
        'speed' => 'decimal:2',
        'is_active' => 'boolean',
        'recorded_at' => 'datetime',
    ];

    /**
     * Get the user that owns the location history
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for recent locations
     */
    public function scopeRecent($query, $minutes = 5)
    {
        return $query->where('recorded_at', '>=', now()->subMinutes($minutes));
    }
}
