<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'source',
        'url',
        'content',
        'image',
        'is_starred',
    ];

    protected $casts = [
        'is_starred' => 'boolean',
    ];

    /**
     * Boot the model and set up event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate slug when creating an article
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = \Illuminate\Support\Str::slug($article->title);
                
                // Handle duplicate slugs by appending a number
                $originalSlug = $article->slug;
                $count = 1;
                while (static::where('slug', $article->slug)->exists()) {
                    $article->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        // Update slug when title changes
        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = \Illuminate\Support\Str::slug($article->title);
                
                // Handle duplicate slugs
                $originalSlug = $article->slug;
                $count = 1;
                while (static::where('slug', $article->slug)->where('id', '!=', $article->id)->exists()) {
                    $article->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the image attribute with proper path handling.
     * Ensures backward compatibility for images stored with or without 'articles/' prefix.
     */
    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If the image path already starts with 'articles/', return as is
        if (str_starts_with($value, 'articles/')) {
            return $value;
        }

        // If it doesn't start with 'articles/', prepend it for backward compatibility
        return 'articles/' . $value;
    }

    /**
     * Scope a query to only include starred articles.
     */
    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    /**
     * Scope a query to only include non-starred articles.
     */
    public function scopeNotStarred($query)
    {
        return $query->where('is_starred', false);
    }

    /**
     * Set an article as starred and unstar all other articles.
     * Ensures only one article can be starred at a time.
     *
     * @param int $articleId The ID of the article to star
     * @return bool True if successful, false otherwise
     */
    public static function setStarred($articleId)
    {
        // Start a database transaction to ensure consistency
        return DB::transaction(function () use ($articleId) {
            // First, unstar all articles
            static::query()->update(['is_starred' => false]);

            // Then star the specified article
            $article = static::find($articleId);
            if ($article) {
                $article->is_starred = true;
                return $article->save();
            }

            return false;
        });
    }
}
