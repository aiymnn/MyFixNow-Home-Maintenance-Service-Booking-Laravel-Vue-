<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyProviderBookingCancelled;
use App\Jobs\SendBookingCreatedNotification;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\ServiceCategory;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Carbon\Carbon;
use Stripe\Checkout\Session as StripeSession;

class PortalController extends Controller
{
    public function home()
    {
        return Inertia::render('Welcome');
    }

    public function services(Request $request)
    {
        $query = Service::select('id', 'title', 'description', 'price', 'image')
            ->with('category:id,name') // eager load category name for filtering display
            ->withCount('bookings')
            ->withAvg('reviews', 'rating')
            ->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('category') && $request->category !== 'All') {
            $query->whereHas(
                'category',
                fn($q) =>
                $q->where('name', $request->category)
            );
        }

        if ($request->sort === 'Price Low to High') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'Price High to Low') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $services = $query->paginate(12)->withQueryString();
        $categories = ServiceCategory::orderBy('name')->pluck('name');

        return Inertia::render('Portal/Service/Services', [
            'services' => $services,
            'categories' => $categories,
            'filters' => $request->only('search', 'category', 'sort'),
        ]);
    }


    public function service(Service $service)
    {
        $service->load([
            'category:id,name',
            'gallery:id,service_id,image_path',
            'provider:id,name',
            'areas:id,name,state_id',
            'areas.state:id,name',
            'reviews' => fn($q) => $q->latest()->take(3)->with([
                'booking:id,customer_id',
                'booking.customer:id,name'
            ]),
        ])
            ->loadCount('bookings')
            ->loadAvg('reviews', 'rating');

        // ✅ Fetch all slots under this service
        $availabilitySlots = AvailabilitySlot::where('service_id', $service->id)
            ->select('id', 'service_id', 'day_of_week', 'start_time', 'end_time')
            ->orderByRaw("FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('start_time')
            ->get();

        // ✅ Log availability slots for debug
        // logger()->info('Availability Slots:', $availabilitySlots->toArray());

        // ✅ Fetch slot IDs that are currently booked (pending or in_progress)
        $bookedSlotIds = Booking::where('service_id', $service->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->pluck('availability_slot_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // logger()->info('Booked Slot IDs:', $bookedSlotIds);

        // ✅ Append availability summary
        $availabilityDaysCount = $availabilitySlots->pluck('day_of_week')->unique()->count();
        $availabilitySlotsCount = $availabilitySlots->count();

        $service->availability_summary = [
            'days_count' => $availabilityDaysCount,
            'slots_count' => $availabilitySlotsCount,
        ];

        return Inertia::render('Portal/Service/Service', [
            'service' => $service,
            'availabilitySlots' => $availabilitySlots,
            'bookedSlotIds' => $bookedSlotIds,
        ]);
    }


    public function bookings(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Booking::with(['service', 'provider', 'payment'])
            ->where('customer_id', auth()->id());

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->get();

        return Inertia::render('Portal/Bookings', [
            'bookings' => $bookings,
            'status' => $status,
        ]);
    }



    // public function books(Request $request, Service $service)
    // {
    //     // dd('sini la');
    //     $validated = $request->validate([
    //         'date' => 'required|date|after_or_equal:today',
    //         'time_slot' => 'required|string',
    //         'notes' => 'nullable|string',
    //     ]);

    //     // dd($validated);

    //     Booking::create([
    //         'customer_id' => auth()->id(),
    //         'provider_id' => $service->user_id,
    //         'service_id' => $service->id,
    //         'date' => $validated['date'],
    //         'time_slot' => $validated['time_slot'],
    //         'price_at_booking' => $service->price,
    //         'status' => 'pending',
    //         'notes' => $validated['notes'] ?? null,
    //     ]);

    //     return back()->with('success', 'Booking successfully created.');
    // }

    public function books(Request $request, Service $service)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'availability_slot_id' => 'required|exists:availability_slots,id',
            'notes' => 'nullable|string',
        ]);

        $slot = AvailabilitySlot::findOrFail($validated['availability_slot_id']);

        // dd($slot->start_time, $slot->end_time);

        $formattedTimeSlot = $slot->start_time->format('H:i') . ' - ' . $slot->end_time->format('H:i');

        $booking = Booking::create([
            'customer_id' => auth()->id(),
            'provider_id' => $service->user_id,
            'service_id' => $service->id,
            'availability_slot_id' => $validated['availability_slot_id'],
            'time_slot' => $formattedTimeSlot,
            'date' => $validated['date'],
            'price_at_booking' => $service->price,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        SendBookingCreatedNotification::dispatch($booking);

        return redirect()->route('portal.bookings')
            ->with('success', 'Booking created successfully. Waiting for provider approval.');
    }

    public function booksCancel(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        // Dispatch job to notify provider
        NotifyProviderBookingCancelled::dispatch($booking);

        return redirect()->route('portal.bookings')
            ->with('success', 'Booking cancelled successfully.');
    }
}
