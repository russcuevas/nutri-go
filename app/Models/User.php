<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'avatar',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStore(): bool
    {
        return $this->role === 'store';
    }

    public function isRider(): bool
    {
        return $this->role === 'rider';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function rider()
    {
        return $this->hasOne(Rider::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)->where('status', 'active')->where(function($q) {
            $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
        });
    }

    public function isVipSubscriber(): bool
    {
        return $this->activeSubscription()->exists();
    }
}
