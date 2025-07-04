<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - MyFixNow</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; margin: 0; padding: 0; background: #f9f9f9; }
        .container { background: #fff; padding: 30px; max-width: 700px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; }
        .logo { width: 120px; margin-bottom: 10px; }
        .title { font-size: 22px; font-weight: bold; margin-bottom: 5px; color: #2a7de1; }
        .section { margin-top: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; border-radius: 6px; overflow: hidden; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #e8f0fe; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
        .qr { text-align: center; margin-top: 20px; }
        .qr img { width: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('storage/ApplicationLogo.png') }}" alt="MyFixNow Logo" class="logo">
            <div class="title">Payment Receipt</div>
            <div>Thank you for using MyFixNow</div>
        </div>

        <div class="section">
            <h3>Payment Details</h3>
            <table>
                <tr><th>Payment ID</th><td>{{ $payment->id }}</td></tr>
                <tr><th>Amount Paid</th><td>RM{{ number_format($payment->amount, 2) }}</td></tr>
                <tr><th>Paid At</th><td>{{ $payment->created_at->format('d M Y, h:i A') }}</td></tr>
                <tr><th>Payment Method</th><td>{{ $payment->method ?? '-' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <h3>Booking Details</h3>
            <table>
                <tr><th>Booking ID</th><td>{{ $booking->id }}</td></tr>
                <tr><th>Customer</th><td>{{ $customer->name }}</td></tr>
                <tr><th>Provider</th><td>{{ $provider->name }}</td></tr>
                <tr><th>Service</th><td>{{ $booking->service->name ?? '-' }}</td></tr>
                <tr><th>Service Date</th><td>{{ $booking->start_date ? $booking->start_date->format('d M Y') : '-' }}</td></tr>
            </table>
        </div>

        <div class="qr">
            {{-- Optionally generate QR code to link back to payment tracking --}}
            {{-- <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(100)->generate($payment->id)) }}"> --}}
        </div>

        <div class="footer">
            This is a system-generated receipt and does not require a signature.<br>
            For any inquiries, please contact <strong>support@myfixnow.com</strong>.<br>
            <em>All prices are inclusive of applicable taxes (if any).</em>
        </div>
    </div>
</body>
</html>
