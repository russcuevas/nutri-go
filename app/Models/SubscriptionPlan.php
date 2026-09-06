<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_period',
        'badge',
        'description',
        'perks',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'float',
        'perks' => 'array',
        'is_featured' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }
}
