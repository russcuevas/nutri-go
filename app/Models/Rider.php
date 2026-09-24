<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'plate_number',
        'license_number',
        'license_image',
        'phone',
        'barangay',
        'address_line',
        'current_latitude',
        'current_longitude',
        'status',
        'rejection_reason',
        'is_online',
        'active_orders_count',
        'total_deliveries',
        'rating',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'current_latitude' => 'float',
        'current_longitude' => 'float',
        'rating' => 'float',
        'active_orders_count' => 'integer',
        'total_deliveries' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallet()
    {
        return $this->hasOne(RiderWallet::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payoutRequests()
    {
        return $this->hasMany(RiderPayoutRequest::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if rider can accept new orders (Maximum 3 active orders per trip)
     */
    public function canAcceptOrders(): bool
    {
        if (!$this->isApproved() || !$this->is_online) {
            return false;
        }

        $activeOrdersCount = Order::where('rider_id', $this->id)
            ->whereIn('status', ['rider_assigned', 'rider_picked_up', 'on_the_way'])
            ->count();

        return $activeOrdersCount < 3;
    }

    public function getActiveOrdersCountAttribute(): int
    {
        return Order::where('rider_id', $this->id)
            ->whereIn('status', ['rider_assigned', 'rider_picked_up', 'on_the_way'])
            ->count();
    }

    public function getLicenseImageUrlAttribute(): ?string
    {
        if ($this->license_image) {
            if (str_starts_with($this->license_image, 'http')) {
                return $this->license_image;
            }
            if (file_exists(public_path($this->license_image)) || str_starts_with($this->license_image, 'images/') || str_starts_with($this->license_image, 'uploads/')) {
                return asset($this->license_image);
            }
            return asset('storage/' . $this->license_image);
        }
        return null;
    }
}
