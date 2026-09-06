<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'original_price',
        'calories',
        'protein_g',
        'carbs_g',
        'fat_g',
        'dietary_tags',
        'is_healthy_choice',
        'is_supplement',
        'alternative_to_id',
        'healthy_alternative_notes',
        'is_available',
        'stock',
    ];

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'calories' => 'integer',
        'protein_g' => 'float',
        'carbs_g' => 'float',
        'fat_g' => 'float',
        'dietary_tags' => 'array',
        'is_healthy_choice' => 'boolean',
        'is_supplement' => 'boolean',
        'is_available' => 'boolean',
        'stock' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function alternativeTo()
    {
        return $this->belongsTo(Product::class, 'alternative_to_id');
    }

    public function alternatives()
    {
        return $this->hasMany(Product::class, 'alternative_to_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . $this->image);
        }
        return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80';
    }
}
