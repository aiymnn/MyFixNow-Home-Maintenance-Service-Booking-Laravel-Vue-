<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BookingReview extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = ['booking_id', 'rating', 'comment', 'attachment'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
