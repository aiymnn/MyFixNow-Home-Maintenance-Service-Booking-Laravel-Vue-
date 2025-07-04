<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Jobs\SendBookingCancelledNotification;
use App\Jobs\SendPaymentReminderToCustomer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with([
            'customer:id,name,email,phone',
            'service:id,title,user_id',
            'payment:id,booking_id,amount,status' // add this
        ])
            ->whereHas('service', fn($q) => $q->where('user_id', Auth::id()))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Provider/Booking/Index', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['accepted', 'rejected'])],
        ]);

        // dd([$validated['status'], $booking]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        if ($validated['status'] === 'accepted') {
            SendPaymentReminderToCustomer::dispatch($booking);
        }

        if ($validated['status'] === 'rejected') {
            SendBookingCancelledNotification::dispatch($booking);
        }

        return back()->with('success', 'Booking status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
