<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Cache;

class PacketUser extends Pivot
{
    /**
     * The table associated with the model.
     */
    protected $table = 'packet_user';

    /**
     * Boot the model and set up event listeners for cache invalidation.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear popular packets cache when purchase relationships change
        static::created(function () {
            Cache::forget('popular_packets');
        });

        static::deleted(function () {
            Cache::forget('popular_packets');
        });
    }
}
