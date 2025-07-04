@component('mail::message')
# New Booking Request 📬

Hello {{ $booking->provider->name }},

You have received a new booking request via **MyFixNow**.  
Please review and respond to this booking promptly.

---

## 📋 Booking Details

**Booking ID:** `{{ $booking->id }}`  
**Customer:** {{ $booking->customer->name }}  
**Service:** {{ $booking->service->title }}  
**Date:** {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}  
**Time Slot:** {{ $booking->time_slot }}  
**Notes:**  
@isset($booking->notes)
> {{ $booking->notes }}
@else
> *No additional notes from customer.*
@endisset

---

@component('mail::button', ['url' => route('bookings.index', $booking)])
View & Respond to Booking
@endcomponent

Thank you for using **{{ config('app.name') }}**.
@endcomponent
