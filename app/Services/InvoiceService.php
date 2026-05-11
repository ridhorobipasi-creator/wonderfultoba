<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Generate Invoice PDF for a booking
     */
    public function generateInvoice(Booking $booking)
    {
        $siteSettings = [
            'general' => \App\Models\Setting::where('key', 'general')->first()?->value ?? [],
            'company' => \App\Models\Setting::where('key', 'company')->first()?->value ?? [],
            'cms_landing' => \App\Models\Setting::where('key', 'cms_landing')->first()?->value ?? [],
        ];

        $data = [
            'booking' => $booking->load(['package', 'package.city']),
            'date' => now()->format('d F Y'),
            'siteSettings' => $siteSettings,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        
        return $pdf;
    }

    /**
     * Stream the invoice to the browser
     */
    public function streamInvoice(Booking $booking)
    {
        $pdf = $this->generateInvoice($booking);
        return $pdf->stream("Invoice-{$booking->bookingCode}.pdf");
    }

    /**
     * Download the invoice
     */
    public function downloadInvoice(Booking $booking)
    {
        $pdf = $this->generateInvoice($booking);
        return $pdf->download("Invoice-{$booking->bookingCode}.pdf");
    }
}
