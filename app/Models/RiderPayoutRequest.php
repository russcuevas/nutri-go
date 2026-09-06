<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderPayoutRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'amount',
        'gcash_number',
        'gcash_account_name',
        'status',
        'admin_reference_no',
        'proof_image',
        'notes',
        'requested_at',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function getProofUrlAttribute()
    {
        if ($this->proof_image) {
            return str_starts_with($this->proof_image, 'http')
                ? $this->proof_image
                : asset('storage/' . $this->proof_image);
        }
        return null;
    }
}
