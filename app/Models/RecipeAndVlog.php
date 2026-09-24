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

    public function getLinkedProducts()
    {
        if (empty($this->linked_product_ids)) {
            return collect();
        }
        return Product::whereIn('id', $this->linked_product_ids)->with('store')->get();
    }
}
