<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'percentage',
        'is_valid'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'percentage' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_valid', 1);
    }

    public function packets()
    {
        return $this->hasMany(Packet::class);
    }
}
