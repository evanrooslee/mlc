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
        'source',
        'url',
        'image',
        'is_starred',
    ];

    protected $casts = [
        'is_starred' => 'boolean',
    ];

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
