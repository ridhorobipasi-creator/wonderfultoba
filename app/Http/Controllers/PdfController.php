<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Setting;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    /**
     * Download Itinerary PDF for a package.
     * Uses Eloquent so all model attributes, casts, and resolveImageUrl() work correctly.
     */
    public function downloadItinerary($slug, Request $request)
    {
        $package = Package::with(['packageImages', 'city'])
            ->where('slug', $slug)
            ->first();

        if (! $package) {
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

        // Nama pelanggan untuk personalisasi cover (opsional, dari query string)
        $customerName = trim((string) $request->query('name', ''));
        $customerName = mb_substr(strip_tags($customerName), 0, 60);

        // QR Code -> Google Maps rute Kualanamu menuju destinasi
        $destination = ($city->name ?? $package->locationTag ?? 'Danau Toba').' Sumatera Utara';
        $mapsUrl = 'https://www.google.com/maps/dir/?api=1&origin='.rawurlencode('Bandara Kualanamu')
            .'&destination='.rawurlencode($destination).'&travelmode=driving';
        $qrDataUri = $this->makeQrDataUri($mapsUrl);

        $data = [
            'package' => $package,
            'city' => $city,
            'siteSettings' => $siteSettings,
            'heroImageUrl' => $heroImageUrl,
            'customerName' => $customerName,
            'mapsUrl' => $mapsUrl,
            'qrDataUri' => $qrDataUri,
        ];

        $pdf = Pdf::loadView('pdf.itinerary', $data);

        return $pdf->download("Itinerary-{$package->slug}.pdf");
    }

    /**
     * Generate QR code sebagai data-URI PNG (aman untuk dompdf, tanpa JS).
     * Mengembalikan null jika gagal agar PDF tetap bisa dibuat.
     */
    private function makeQrDataUri(string $content): ?string
    {
        try {
            $result = (new Builder(
                writer: new PngWriter,
                data: $content,
                size: 220,
                margin: 8,
            ))->build();

            return $result->getDataUri();
        } catch (\Throwable $e) {
            Log::warning('QR generation failed: '.$e->getMessage());

            return null;
        }
    }

    public function streamInvoice($identifier)
    {
        try {
            $booking = Booking::where('bookingCode', $identifier)
                ->orWhere('id', $identifier)
                ->firstOrFail();

            return app(InvoiceService::class)->streamInvoice($booking);
        } catch (\Exception $e) {
            Log::error('PDF Stream Error: '.$e->getMessage());

            return 'Gagal membuka PDF: '.$e->getMessage();
        }
    }

    public function downloadInvoice($identifier)
    {
        try {
            $booking = Booking::where('bookingCode', $identifier)
                ->orWhere('id', $identifier)
                ->firstOrFail();

            return app(InvoiceService::class)->downloadInvoice($booking);
        } catch (\Exception $e) {
            Log::error('PDF Download Error: '.$e->getMessage());

            return 'Gagal mengunduh PDF: '.$e->getMessage();
        }
    }
}
