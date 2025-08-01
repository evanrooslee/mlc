<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Packet extends Model
{
    use HasFactory;

    /**
     * Boot the model and set up event listeners for cache invalidation.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear popular packets cache when packet data changes
        static::saved(function () {
            Cache::forget('popular_packets');
        });

        static::deleted(function () {
            Cache::forget('popular_packets');
        });
    }

    protected $fillable = [
        'title',
        'code',
        'grade',
        'subject',
        'type',
        'benefit',
        'price',
        'discount_id',
        'image',
    ];

    protected $casts = [
        'grade' => 'integer',
        'price' => 'integer',
    ];

    /**
     * The discount that belongs to the packet.
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * The users that belong to the packet.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'packet_user')
            ->using(PacketUser::class);
    }

    /**
     * Scope to order packets by purchase count (popularity).
     */
    public function scopePopular($query)
    {
        return $query->withCount('users')
            ->orderBy('users_count', 'desc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the number of users who purchased this packet.
     */
    public function getPurchaseCountAttribute()
    {
        return $this->users()->count();
    }

    /**
     * Get formatted image URL with fallback for missing images.
     */
    public function getImageUrlAttribute()
    {
        // Check if image exists and is not empty, and file actually exists
        if (!empty($this->image) && file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        // Fallback to hero illustration as default placeholder
        return asset('images/hero-illustration.png');
    }

    /**
     * Get optimized image URL for specific dimensions.
     */
    public function getOptimizedImageUrl($width = 400, $height = 225)
    {
        $imageUrl = $this->image_url;

        // For future implementation: could add image resizing service here
        // For now, return the original image URL with proper dimensions
        return $imageUrl;
    }
}
