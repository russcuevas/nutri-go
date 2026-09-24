<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'payment_reference',
        'payment_proof',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function isValid(): bool
    {
        return $this->status === 'active' && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if ($this->payment_proof) {
            if (str_starts_with($this->payment_proof, 'http')) {
                return $this->payment_proof;
            }
            if (file_exists(public_path($this->payment_proof)) || str_starts_with($this->payment_proof, 'images/') || str_starts_with($this->payment_proof, 'uploads/')) {
                return asset($this->payment_proof);
            }
            return asset('storage/' . $this->payment_proof);
        }
        return null;
    }
}
