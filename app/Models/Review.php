<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'store_id',
        'rider_id',
        'store_rating',
        'store_comment',
        'rider_rating',
        'rider_comment',
        'health_satisfaction',
    ];

    protected $casts = [
        'store_rating' => 'integer',
        'rider_rating' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }
}
