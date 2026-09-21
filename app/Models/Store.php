<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'slug',
        'description',
        'logo',
        'banner',
        'barangay',
        'address_line',
        'latitude',
        'longitude',
        'phone',
        'health_category',
        'business_permit_no',
        'business_registration_number',
        'tax_identification_number',
        'business_establishment_date',
        'health_certificate',
        'gcash_name',
        'gcash_number',
        'gcash_qr',
        'status',
        'rejection_reason',
        'commission_percent',
        'opening_time',
        'closing_time',
        'is_open',
        'rating',
        'total_reviews',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'rating' => 'float',
        'commission_percent' => 'float',
        'business_establishment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            if (str_starts_with($this->logo, 'http')) return $this->logo;
            if (str_starts_with($this->logo, 'uploads/') || file_exists(public_path($this->logo))) return asset($this->logo);
            return asset('storage/' . $this->logo);
        }
        return asset('images/nutrigo-logo.jpg');
    }

    public function getBannerUrlAttribute()
    {
        if ($this->banner) {
            if (str_starts_with($this->banner, 'http')) return $this->banner;
            if (str_starts_with($this->banner, 'uploads/') || file_exists(public_path($this->banner))) return asset($this->banner);
            return asset('storage/' . $this->banner);
        }
        return 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1200&q=80';
    }

    public function getGcashQrUrlAttribute()
    {
        if ($this->gcash_qr) {
            if (str_starts_with($this->gcash_qr, 'http')) return $this->gcash_qr;
            if (str_starts_with($this->gcash_qr, 'uploads/') || file_exists(public_path($this->gcash_qr))) return asset($this->gcash_qr);
            return asset('storage/' . $this->gcash_qr);
        }
        return null;
    }

    public function getGcashQrCodeAttribute()
    {
        return $this->gcash_qr_url;
    }
}
