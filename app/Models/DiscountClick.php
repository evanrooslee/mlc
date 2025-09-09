<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountClick extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone_number',
        'user_id',
        'user_name',
        'clicked_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'clicked_at' => 'datetime'
    ];

    /**
     * Get the user that made the discount click.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
