@component('mail::message')
# Booking Cancellation Confirmed

Hi {{ $booking->provider->name }},

You have **cancelled** the following booking:

- **Customer:** {{ $booking->customer->name }}
- **Service:** {{ $booking->service->title }}
- **Date:** {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
- **Time Slot:** {{ $booking->time_slot }}

The customer has been notified about this cancellation.

If this was a mistake, please contact the customer directly or our support team.

@component('mail::button', ['url' => config('app.url')])
Go to MyFixNow
@endcomponent

Thank you for keeping your customers informed.

Regards,  
**MyFixNow Team**
@endcomponent
