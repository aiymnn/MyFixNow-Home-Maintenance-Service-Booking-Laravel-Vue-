@component('mail::message')
# Complete Your Payment 💳

Hi {{ $customer->name }},

Your booking with **{{ $provider->name }}** for the service:

**{{ $booking->service->title }}**  
on **{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }} at {{ $booking->time_slot }}**

has been **accepted**! 🎉

---

To proceed, please complete your payment to confirm your slot.

@component('mail::button', ['url' => route('portal.payments.create', $booking)])
Pay Now
@endcomponent

If you do not wish to proceed, you may ignore this email, and your booking will be automatically cancelled after a period of inactivity.

Thank you for using MyFixNow!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
