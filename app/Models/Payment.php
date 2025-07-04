<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payment extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'booking_id',
        'amount',
        'status',
        'method',
        'stripe_payment_intent_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
