@component('mail::message')
# Booking Cancelled

Hi {{ $booking->customer->name }},

We regret to inform you that your booking with **{{ $booking->provider->name }}** for:

- **Service:** {{ $booking->service->title }}
- **Date:** {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
- **Time Slot:** {{ $booking->time_slot }}

has been **cancelled by the provider**.

If you need further assistance or wish to rebook, please visit your dashboard below.

@component('mail::button', ['url' => config('app.url')])
Go to MyFixNow
@endcomponent

We apologize for any inconvenience caused.

Thanks,  
**MyFixNow Team**
@endcomponent
