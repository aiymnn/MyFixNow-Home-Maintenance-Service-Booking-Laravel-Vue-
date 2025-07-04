<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - MyFixNow</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; padding: 0 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 100px; margin-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; color: #2a7de1; margin-bottom: 5px; }
        .subtitle { font-size: 12px; color: #555; }
        .section { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('storage/ApplicationLogo.png') }}" class="logo" alt="MyFixNow Logo">
            <div class="title">Payment Receipt</div>
            <div class="subtitle">Thank you for using MyFixNow</div>
        </div>

        <div class="section">
            <strong>Payment Details</strong>
            <table>
                <tr><th>Payment ID</th><td>{{ $payment->id }}</td></tr>
                <tr><th>Amount Paid</th><td>RM{{ number_format($payment->amount, 2) }}</td></tr>
                <tr><th>Paid At</th><td>{{ $payment->created_at->format('d M Y, h:i A') }}</td></tr>
                <tr><th>Payment Method</th><td>{{ ucfirst($payment->method ?? '-') }}</td></tr>
            </table>
        </div>

        <div class="section">
            <strong>Booking Details</strong>
            <table>
                <tr>
                    <th style="text-align: left; padding: 6px;">Booking ID</th>
                    <td style="padding: 6px;">{{ $booking->id }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 6px;">Customer</th>
                    <td style="padding: 6px;">{{ $customer->name }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 6px;">Provider</th>
                    <td style="padding: 6px;">{{ $provider->name }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 6px;">Time Slot</th>
                    <td style="padding: 6px;">
                        @php
                            $timeSlotFormatted = '-';
                            if ($booking->time_slot) {
                                [$start, $end] = explode(' - ', $booking->time_slot);
                                $startFormatted = \Carbon\Carbon::createFromFormat('H:i', trim($start))->format('h:i A');
                                $endFormatted = \Carbon\Carbon::createFromFormat('H:i', trim($end))->format('h:i A');
                                $timeSlotFormatted = "$startFormatted - $endFormatted";
                            }
                        @endphp
                        {{ $timeSlotFormatted }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 6px;">Service</th>
                    <td style="padding: 6px;">
                        {{ $booking->service->title ?? $booking->service->name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 6px;">Service Date</th>
                    <td style="padding: 6px;">
                        {{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 6px;">Booking Notes</th>
                    <td style="padding: 6px;">
                        {{ $booking->notes ?? '-' }}
                    </td>
                </tr>
            </table>
        </div>
        

        <div class="footer">
            This is a system-generated receipt and does not require a signature.<br>
            For inquiries: support@myfixnow.com
        </div>
    </div>
</body>
</html>
