@component('mail::message')
# Payment Received

Hello {{ $provider->name }},

Your customer, **{{ $customer->name }}**, has completed the payment for their booking.

@component('mail::panel')
**Booking ID:** {{ $booking->id }}  
**Service:** {{ $booking->service->title ?? '-' }}  
**Date:** {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}  
**Time Slot:** {{ $booking->time_slot ?? '-' }}  
**Amount Paid:** RM{{ number_format($payment->amount, 2) }}
@endcomponent

Please prepare to deliver the service as scheduled.

@component('mail::button', ['url' => config('app.url')])
View Booking
@endcomponent

If you have any questions or need assistance, contact us at [support@myfixnow.com](mailto:support@myfixnow.com).

Thank you for using **MyFixNow**!

Regards,  
**MyFixNow Team**
@endcomponent
