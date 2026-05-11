<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Download Itinerary PDF for a package.
     * Uses Eloquent so all model attributes, casts, and resolveImageUrl() work correctly.
     */
    public function downloadItinerary($slug)
    {
        $package = Package::with(['packageImages', 'city'])
            ->where('slug', $slug)
            ->first();

        if (!$package) {
            abort(404, 'Paket tidak ditemukan.');
        }

        $city = $package->city;

        $siteSettings = [
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
            'company' => Setting::where('key', 'company')->first()?->value ?? [],
        ];

        // Resolve hero image for the PDF header using the centralized helper
        $heroImageUrl = $package->packageImages->first()?->image_url
            ?? $package->first_image
            ?? imageFallback();

        $data = [
            'package'      => $package,
            'city'         => $city,
            'siteSettings' => $siteSettings,
            'heroImageUrl' => $heroImageUrl,
        ];

        $pdf = Pdf::loadView('pdf.itinerary', $data);

        return $pdf->download("Itinerary-{$package->slug}.pdf");
    }

    public function streamInvoice($identifier)
    {
        try {
            $booking = Booking::where('bookingCode', $identifier)
                ->orWhere('id', $identifier)
                ->firstOrFail();
            return app(\App\Services\InvoiceService::class)->streamInvoice($booking);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PDF Stream Error: ' . $e->getMessage());
            return 'Gagal membuka PDF: ' . $e->getMessage();
        }
    }

    public function downloadInvoice($identifier)
    {
        try {
            $booking = Booking::where('bookingCode', $identifier)
                ->orWhere('id', $identifier)
                ->firstOrFail();
            return app(\App\Services\InvoiceService::class)->downloadInvoice($booking);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PDF Download Error: ' . $e->getMessage());
            return 'Gagal mengunduh PDF: ' . $e->getMessage();
        }
    }
}

