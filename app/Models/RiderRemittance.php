<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderRemittance extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'period_start',
        'period_end',
        'cycle_key',
        'total_collected_cash',
        'total_remit_amount',
        'rider_delivery_fees',
        'orders_count',
        'status',
        'payment_method',
        'reference_no',
        'proof_image',
        'notes',
        'admin_notes',
        'remitted_at',
        'verified_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_collected_cash' => 'float',
        'total_remit_amount' => 'float',
        'rider_delivery_fees' => 'float',
        'orders_count' => 'integer',
        'remitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function getProofUrlAttribute(): ?string
    {
        if ($this->proof_image) {
            if (str_starts_with($this->proof_image, 'http')) {
                return $this->proof_image;
            }
            if (file_exists(public_path($this->proof_image)) || str_starts_with($this->proof_image, 'images/') || str_starts_with($this->proof_image, 'uploads/')) {
                return asset($this->proof_image);
            }
            return asset('storage/' . $this->proof_image);
        }
        return null;
    }
}

