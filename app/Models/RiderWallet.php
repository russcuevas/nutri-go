<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'balance',
        'total_earnings',
        'pending_payout',
    ];

    protected $casts = [
        'balance' => 'float',
        'total_earnings' => 'float',
        'pending_payout' => 'float',
    ];

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }
}
