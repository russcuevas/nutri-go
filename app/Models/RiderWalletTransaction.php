<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'order_id',
        'type',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
