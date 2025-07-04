<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AvailabilitySlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Provider/Availability/Index', [
            'availabilities' => AvailabilitySlot::whereHas(
                'service',
                fn($q) =>
                $q->where('user_id', Auth::id())
            )->with('service:id,title')->paginate(10),
            'userServices' => Auth::user()->services()->select('id', 'title')->get(),
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    protected function validateAvailability(Request $request): array
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'day_of_week' => [
                'required',
                Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
        ], [
            'start_time.date_format' => 'Start time must be in HH:MM format.',
            'end_time.date_format' => 'End time must be in HH:MM format.',
        ]);

        if ($validated['start_time'] >= $validated['end_time']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'end_time' => 'End time must be after the start time.',
            ]);
        }

        // Append ":00" to ensure TIME consistency in DB
        $validated['start_time'] .= ':00';
        $validated['end_time'] .= ':00';

        return $validated;
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'day_of_week' => [
                'required',
                Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            ],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*.start_time' => ['required', 'date_format:H:i'],
            'slots.*.end_time' => ['required', 'date_format:H:i'],
        ]);

        // Get service and ensure ownership
        $service = \App\Models\Service::where('id', $validated['service_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $dayOfWeek = $validated['day_of_week'];

        foreach ($validated['slots'] as $slot) {
            $startTime = $slot['start_time'] . ':00';
            $endTime = $slot['end_time'] . ':00';

            if ($startTime >= $endTime) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'slots' => ['End time must be after start time for all slots.'],
                ]);
            }

            // Check overlap for each slot
            $overlap = $service->availabilitySlots()
                ->where('day_of_week', $dayOfWeek)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                })
                ->exists();

            if ($overlap) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'slots' => ['One or more slots overlap with existing slots for this service.'],
                ]);
            }

            // Insert slot
            $service->availabilitySlots()->create([
                'day_of_week' => $dayOfWeek,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);
        }

        return back()->with('success', 'Availability slots created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(AvailabilitySlot $availabilitySlot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AvailabilitySlot $availabilitySlot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AvailabilitySlot $availability)
    {
        $validated = $request->validate([
            'day_of_week' => [
                'required',
                Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
        ], [
            'start_time.date_format' => 'Start time must be in HH:MM format.',
            'end_time.date_format' => 'End time must be in HH:MM format.',
        ]);

        // dd([$validated, $availability]);

        if ($validated['start_time'] >= $validated['end_time']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'end_time' => 'End time must be after the start time.',
            ]);
        }

        // Convert to full TIME
        $validated['start_time'] .= ':00';
        $validated['end_time'] .= ':00';

        // Ensure the slot belongs to the user
        if ($availability->service->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        // Check for overlapping slots (excluding current slot)
        $overlap = AvailabilitySlot::where('service_id', $availability->service_id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $availability->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlap) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'start_time' => 'This time slot overlaps with an existing one for this service.',
            ]);
        }

        $availability->update($validated);

        return back()->with('success', 'Availability slot updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AvailabilitySlot $availability)
    {
        // Optional: Check if the authenticated user is allowed to delete this slot
        if ($availability->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $availability->delete();

        return redirect()->back()->with('success', 'Availability slot deleted successfully.');
    }
}
