<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class BannerCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'background_image',
        'display_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Scope for active cards
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered display
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Get validation rules for creating/updating banner cards
     */
    public static function validationRules($id = null)
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'background_image' => 'required|string|max:255',
            'display_order' => [
                'required',
                'integer',
                'between:1,6',
                Rule::unique('banner_cards', 'display_order')
                    ->where('is_active', true)
                    ->ignore($id)
            ],
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get validation rules for image upload
     */
    public static function imageValidationRules()
    {
        return [
            'background_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    /**
     * Boot method to add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure maximum 6 active cards business rule
        static::creating(function ($bannerCard) {
            if ($bannerCard->is_active) {
                $activeCount = static::where('is_active', true)->count();
                if ($activeCount >= 6) {
                    throw new \Exception('Maximum 6 active banner cards allowed');
                }
            }
        });

        static::updating(function ($bannerCard) {
            if ($bannerCard->is_active && $bannerCard->getOriginal('is_active') === false) {
                $activeCount = static::where('is_active', true)
                    ->where('id', '!=', $bannerCard->id)
                    ->count();
                if ($activeCount >= 6) {
                    throw new \Exception('Maximum 6 active banner cards allowed');
                }
            }
        });
    }
}
