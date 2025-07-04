@component('mail::message')
# Booking Created Successfully 🎉

Hello {{ $booking->customer->name }},

Thank you for booking with **MyFixNow**! We have received your booking request, and it is currently awaiting provider confirmation.

---

## 📋 Booking Details

**Booking ID:** `{{ $booking->id }}`  
**Service:** {{ $booking->service->title }}  
**Provider:** {{ $booking->provider->name }}  
**Date:** {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}  
**Time Slot:** {{ $booking->time_slot }}  
**Amount to Pay:** RM{{ number_format($booking->price_at_booking, 2) }}

---

## 🕒 What happens next?

- Your provider will review and confirm your booking.
- Once approved, you will receive an email to proceed with payment.
- If you decide to cancel, you can do so from your dashboard before making payment.

@component('mail::button', ['url' => route('portal.bookings', $booking)])
View Your Booking
@endcomponent

---

Thank you for using **MyFixNow**. We look forward to serving you!

If you have any questions, please reply to this email.

Regards,  
**{{ config('app.name') }}** Support Team

@endcomponent
