<?php

namespace App\Jobs;

use App\Mail\PaymentConfirmationMail;
use App\Mail\PaymentReceivedProviderMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendPaymentConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function handle(): void
    {
        $booking =  $this->payment->booking;
        $customer = $booking->customer;
        $provider = $booking->provider;

        // Generate PDF receipt
        $pdf = Pdf::loadView('pdf.receipt', [
            'payment' => $this->payment,
            'booking' => $booking,
            'customer' => $customer,
            'provider' => $provider,
        ]);

        // Pastikan folder wujud sebelum simpan
        $directory = storage_path('app/public/receipts');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdfName = 'receipts/' . $this->payment->id . '.pdf';
        $storagePath = storage_path('app/public/' . $pdfName);
        file_put_contents($storagePath, $pdf->output());


        // Save in receipts table
        Receipt::create([
            'payment_id' => $this->payment->id,
            'pdf_path' => $pdfName,
            'amount' => $this->payment->amount,
            'details' => json_encode([
                'payment_method' => $this->payment->method ?? null,
                'paid_at' => $this->payment->created_at,
                'customer_name' => $customer->name,
                'provider_name' => $provider->name,
                'service_name' => $booking->service->title ?? null,
                'service_date' => $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : null,
            ]),
        ]);

        // ✅ Send email with attached receipt (pass full path)
        Mail::to($customer->email)->queue(new PaymentConfirmationMail($this->payment, $storagePath));
        Mail::to($provider->email)->queue(new PaymentReceivedProviderMail($this->payment));
    }
}
