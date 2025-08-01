<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'publisher',
        'video',
    ];

    /**
     * Scope a query to filter by subject.
     */
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * Scope a query to filter by publisher.
     */
    public function scopeByPublisher($query, $publisher)
    {
        return $query->where('publisher', $publisher);
    }
}
