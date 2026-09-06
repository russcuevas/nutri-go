<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'channel_name',
        'bio',
        'avatar',
        'specialties',
        'youtube_url',
        'tiktok_url',
        'instagram_url',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function recipes()
    {
        return $this->hasMany(RecipeAndVlog::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/' . $this->avatar);
        }
        return 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&q=80';
    }
}
