<?php

namespace App\Http\Controllers;

use App\Jobs\SendPaymentConfirmation;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function create(Request $request, Booking $booking)
    {
        if ($booking->status !== 'accepted') {
            return redirect()->route('portal.bookings')->with('error', 'Payment not allowed. Please wait for provider approval.');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => $booking->service->title,
                    ],
                    'unit_amount' => (int)($booking->price_at_booking * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('portal.payments.success', $booking),
            'cancel_url' => route('portal.payments.cancel', $booking),
            'metadata' => [
                'booking_id' => $booking->id,
            ],
        ]);

        return Inertia::location($session->url);
    }


    public function success(Request $request, Booking $booking)
    {
        if ($booking->status !== 'accepted') {
            return redirect()->route('portal.bookings')->with('error', 'Invalid booking status.');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $sessions = \Stripe\Checkout\Session::all([
            'limit' => 1,
        ]);

        $session = $sessions->data[0] ?? null;

        if ($session && $session->payment_status === 'paid') {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->price_at_booking,
                'status' => 'paid',
                'method' => 'stripe',
                'stripe_payment_intent_id' => $session->payment_intent,
            ]);

            // ✅ Update status to 'in_progress' after payment, or keep as 'accepted' if you prefer manual start
            $booking->update([
                'status' => 'in_progress',
            ]);

            SendPaymentConfirmation::dispatch($payment);

            return redirect()->route('portal.bookings')->with('success', 'Payment successful. Thank you!');
        }

        return redirect()->route('portal.bookings')->with('error', 'Payment verification failed.');
    }



    public function cancel(Request $request, Booking $booking)
    {
        // Update booking to cancelled if payment not completed
        if ($booking->status === 'pending') {
            $booking->update([
                'status' => 'cancelled',
            ]);
        }

        return redirect()
            ->route('portal.bookings')
            ->with('error', 'Payment was cancelled. Booking has been cancelled.');
    }
}
