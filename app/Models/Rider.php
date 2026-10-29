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
        'license_back_image',
        'face_image',
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

    public function remittances()
    {
        return $this->hasMany(RiderRemittance::class);
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
        return $this->resolveImageUrl($this->license_image);
    }

    public function getLicenseFrontImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->license_image);
    }

    public function getLicenseBackImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->license_back_image);
    }

    public function getFaceImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->face_image);
    }

    protected function resolveImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        if (file_exists(public_path($path)) || str_starts_with($path, 'images/') || str_starts_with($path, 'uploads/')) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }
}
