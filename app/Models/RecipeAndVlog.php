<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeAndVlog extends Model
{
    use HasFactory;

    protected $table = 'recipes_and_vlogs';

    protected $fillable = [
        'creator_id',
        'store_id',
        'title',
        'slug',
        'description',
        'video_url',
        'video_embed',
        'thumbnail',
        'prep_time_mins',
        'calories',
        'protein_g',
        'carbs_g',
        'fat_g',
        'ingredients',
        'instructions',
        'is_premium_only',
        'linked_product_ids',
        'views_count',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'linked_product_ids' => 'array',
        'is_premium_only' => 'boolean',
        'calories' => 'integer',
        'protein_g' => 'float',
        'carbs_g' => 'float',
        'fat_g' => 'float',
        'prep_time_mins' => 'integer',
        'views_count' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            if (str_starts_with($this->thumbnail, 'http')) return $this->thumbnail;
            if (file_exists(public_path($this->thumbnail)) || str_starts_with($this->thumbnail, 'images/') || str_starts_with($this->thumbnail, 'uploads/')) {
                return asset($this->thumbnail);
            }
            return asset('storage/' . $this->thumbnail);
        }
        return 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&q=80';
    }

    public function getIsLocalVideoAttribute(): bool
    {
        if (empty($this->video_url)) {
            return false;
        }

        if (str_starts_with($this->video_url, 'images/creators/videos') || 
            str_starts_with($this->video_url, 'videos/') ||
            str_starts_with($this->video_url, 'uploads/') ||
            preg_match('/\.(mp4|webm|ogg|mov|mkv)(\?.*)?$/i', $this->video_url)) {
            if (!preg_match('/(youtube\.com|youtu\.be|vimeo\.com|tiktok\.com)/i', $this->video_url)) {
                return true;
            }
        }

        return false;
    }

    public function getVideoSourceUrlAttribute(): string
    {
        if (empty($this->video_url)) {
            return '';
        }

        if (str_starts_with($this->video_url, 'http://') || str_starts_with($this->video_url, 'https://')) {
            return $this->video_url;
        }

        return asset($this->video_url);
    }

    public function getVideoEmbedHtmlAttribute(): string
    {
        if ($this->is_local_video) {
            $src = e($this->video_source_url);
            $poster = e($this->thumbnail_url);
            return '<video controls playsinline class="w-full h-full object-contain bg-black" poster="' . $poster . '"><source src="' . $src . '">Your browser does not support HTML5 video.</video>';
        }

        $url = $this->video_url ?? '';

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match)) {
            $videoId = $match[1];
            return '<iframe width="100%" height="100%" src="https://www.youtube-nocookie.com/embed/' . $videoId . '" title="' . e($this->title) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="w-full h-full"></iframe>';
        }

        if (!empty($this->video_embed)) {
            return $this->video_embed;
        }

        if (!empty($url)) {
            return '<iframe width="100%" height="100%" src="' . e($url) . '" title="' . e($this->title) . '" frameborder="0" allowfullscreen class="w-full h-full"></iframe>';
        }

        return '<div class="w-full h-full flex items-center justify-center text-gray-500 bg-gray-900 text-xs">No video stream available</div>';
    }

    public function getLinkedProducts()
    {
        if (empty($this->linked_product_ids)) {
            return collect();
        }
        return Product::whereIn('id', $this->linked_product_ids)->with('store')->get();
    }
}
