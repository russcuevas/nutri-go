<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'store_id',
        'rider_id',
        'status',
        'subtotal',
        'delivery_fee',
        'platform_fee',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_proof_image',
        'payment_reference_no',
        'recipient_name',
        'recipient_phone',
        'delivery_barangay',
        'delivery_address',
        'delivery_landmark',
        'delivery_notes',
        'delivery_latitude',
        'delivery_longitude',
        'distance_km',
        'delivery_type',
        'scheduled_at',
        'estimated_prep_time_mins',
        'estimated_delivery_time_mins',
        'store_decline_reason',
        'rider_proof_image',
        'rider_delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'delivery_fee' => 'float',
        'platform_fee' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
        'delivery_latitude' => 'float',
        'delivery_longitude' => 'float',
        'distance_km' => 'float',
        'scheduled_at' => 'datetime',
        'rider_delivered_at' => 'datetime',
    ];

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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackings()
    {
        return $this->hasMany(OrderTracking::class)->orderBy('created_at', 'asc');
    }

    public function latestTracking()
    {
        return $this->hasOne(OrderTracking::class)->latestOfMany();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending_store' => 'Pending Store Approval',
            'store_accepted_preparing' => 'Store Preparing Order',
            'ready_for_pickup' => 'Ready for Rider Pickup',
            'rider_assigned' => 'Rider Assigned',
            'rider_picked_up' => 'Rider Picked Up Order',
            'on_the_way' => 'On The Way to You',
            'delivered' => 'Delivered',
            'declined_by_store' => 'Declined by Store',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending_store' => 'bg-amber-100 text-amber-800 border-amber-300',
            'store_accepted_preparing' => 'bg-blue-100 text-blue-800 border-blue-300',
            'ready_for_pickup' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'rider_assigned' => 'bg-purple-100 text-purple-800 border-purple-300',
            'rider_picked_up', 'on_the_way' => 'bg-teal-100 text-teal-800 border-teal-300',
            'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'declined_by_store', 'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function getTotalCaloriesAttribute(): int
    {
        return (int) $this->items->sum('calories');
    }

    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof_image) {
            return str_starts_with($this->payment_proof_image, 'http')
                ? $this->payment_proof_image
                : asset('storage/' . $this->payment_proof_image);
        }
        return null;
    }
}
