<?php

namespace App\Jobs;

use App\Mail\PaymentReminderMail;
use App\Models\Booking;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminderToCustomer implements ShouldQueue
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
        Mail::to($this->booking->customer->email)
            ->queue(new PaymentReminderMail($this->booking));
    }
}
