@component('mail::message')
<div style="text-align: center;">
    <img src="{{ asset('storage/ApplicationLogo.png') }}" alt="MyFixNow Logo" width="120" style="margin-bottom: 15px;">
</div>

# Payment Confirmation

Hello {{ $customer->name }},

Thank you for your payment. Below are your payment details:

@component('mail::table')
| Details          | Information |
|------------------|-------------|
| **Payment ID**   | {{ $payment->id }} |
| **Amount Paid**  | RM{{ number_format($payment->amount, 2) }} |
| **Paid At**      | {{ $payment->created_at->format('d M Y, h:i A') }} |
| **Booking ID**   | {{ $booking->id }} |
| **Service**      | {{ $booking->service->title ?? '-' }} |
| **Time Slot**    | 
@if($booking->time_slot)
    @php
        [$start, $end] = explode(' - ', $booking->time_slot);
        $startFormatted = \Carbon\Carbon::createFromFormat('H:i', $start)->format('h.i A');
        $endFormatted = \Carbon\Carbon::createFromFormat('H:i', $end)->format('h.i A');
    @endphp
    {{ $startFormatted }} - {{ $endFormatted }}
@else
    -
@endif
|
| **Service Date** | {{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-' }} |
| **Booking Notes** | {{ $booking->notes ?? '-' }} |
@endcomponent


You may find the attached receipt for your reference.

@component('mail::button', ['url' => config('app.url')])
Visit MyFixNow
@endcomponent

If you have any questions, please reach out to us at [support@myfixnow.com](mailto:support@myfixnow.com).

Thank you for using MyFixNow!

Regards,  
**MyFixNow Team**

@endcomponent
