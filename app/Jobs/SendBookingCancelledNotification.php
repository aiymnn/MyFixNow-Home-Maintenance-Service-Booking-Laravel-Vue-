<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\BookingCancelledCustomerMail;
use App\Mail\BookingCancelledProviderMail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBookingCancelledNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Booking $booking;
    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['customer', 'provider', 'service']);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send to Customer
        Mail::to($this->booking->customer->email)
            ->queue(new BookingCancelledCustomerMail($this->booking));

        // Send to Provider
        Mail::to($this->booking->provider->email)
            ->queue(new BookingCancelledProviderMail($this->booking));
    }
}
