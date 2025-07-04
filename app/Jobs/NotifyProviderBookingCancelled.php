<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\BookingCancelledByCustomerMail;

class NotifyProviderBookingCancelled implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Booking $booking;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['service', 'provider', 'customer']);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->booking->provider->email)
            ->queue(new BookingCancelledByCustomerMail($this->booking));
    }
}
