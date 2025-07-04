<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'customer_id',
        'provider_id',
        'service_id',
        'availability_slot_id',
        'date',
        'time_slot',
        'price_at_booking',
        'status',
        'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(BookingReview::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function availabilitySlot()
    {
        return $this->belongsTo(AvailabilitySlot::class);
    }
}
