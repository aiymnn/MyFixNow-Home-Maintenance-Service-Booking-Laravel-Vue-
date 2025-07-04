<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'duration_minutes',
        'location',
        'image',
        'is_approved'
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gallery()
    {
        return $this->hasMany(ServiceImage::class);
    }


    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function areas()
    {
        return $this->belongsToMany(ServiceArea::class, 'service_area_services', 'service_id', 'area_id')
            ->withPivot('id'); // 👈 add this!
    }

    public function reviews()
    {
        return $this->hasManyThrough(
            BookingReview::class,
            Booking::class,
            'service_id',
            'booking_id',
            'id',
            'id'
        )
            ->select([
                'booking_reviews.*',
                'bookings.service_id as laravel_through_key'
            ]);
    }

    public function availabilitySlots()
    {
        return $this->hasMany(AvailabilitySlot::class);
    }
}
