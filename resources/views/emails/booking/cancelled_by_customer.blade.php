@component('mail::message')
# Booking Cancelled

Hello {{ $provider->name }},

The following booking has been **cancelled by the customer**:

@component('mail::panel')
**Booking ID:** {{ $booking->id }}  
**Customer:** {{ $customer->name }}  
**Service:** {{ $booking->service->title ?? '-' }}  
**Date:** {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}  
**Time Slot:** {{ $booking->time_slot ?? '-' }}
@endcomponent

No further action is required.

@component('mail::button', ['url' => route('bookings.index')])
View Bookings
@endcomponent

Thank you for using MyFixNow.

Regards,  
**MyFixNow Team**
@endcomponent
