<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    use HasFactory;

    protected $table = 'customer_reviews';

    protected $fillable = [
        'name',
        'email',
        'contact',
        'rating',
        'message',
        'is_active',
        'avatar_url',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get avatar URL or generate dynamic initials avatar
     */
    public function getAvatarAttribute(): string
    {
        if (!empty($this->avatar_url)) {
            return $this->avatar_url;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=16a34a&color=ffffff&bold=true&rounded=true';
    }

    /**
     * Scope for reviews displayed on the website
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for reviews hidden/pending
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
