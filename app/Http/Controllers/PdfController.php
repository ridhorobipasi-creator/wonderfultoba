<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Setting;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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

        $data = [
            'package' => $package,
            'city' => $city,
            'siteSettings' => $siteSettings,
            'heroImageUrl' => $heroImageUrl,
        ];

        $pdf = Pdf::loadView('pdf.itinerary', $data);

        return $pdf->download("Itinerary-{$package->slug}.pdf");
    }

    public function streamInvoice($identifier)
    {
        try {
            $view = $this->renderInvoice($identifier);
            return response((string) $view->render());
        } catch (\Throwable $e) {
            Log::error('Invoice Render Error: '.$e->getMessage());

            return 'Gagal membuka invoice: '.$e->getMessage();
        }
    }

    public function downloadInvoice($identifier)
    {
        try {
            $booking = Booking::where('bookingCode', $identifier)
                ->orWhere('id', $identifier)
                ->firstOrFail();

            return app(InvoiceService::class)->downloadInvoice($booking);
        } catch (\Throwable $e) {
            Log::error('Invoice Download Error: '.$e->getMessage());

            return 'Gagal mengunduh invoice: '.$e->getMessage();
        }
    }

    /**
     * Render the premium HTML invoice view for a booking (printable via browser).
     */
    private function renderInvoice($identifier)
    {
        $booking = Booking::with(['package', 'package.city'])
            ->where('bookingCode', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        $general = Setting::where('key', 'general')->first()?->value ?? [];
        $company = Setting::where('key', 'company')->first()?->value ?? [];
        $landing = Setting::where('key', 'cms_landing')->first()?->value ?? [];

        $logoRaw = $general['logo_light_url'] ?? ($landing['brand_logo_url'] ?? null);

        $data = [
            'booking' => $booking,
            'companyName' => $general['site_name'] ?? 'Sujai Laketoba',
            'legalName' => $company['legal_name'] ?? 'PT Sujai Laketoba Experience',
            'taxId' => $company['tax_id'] ?? null,
            'bankAccount' => $company['bank_account'] ?? null,
            'bankAccountName' => $company['bank_account_name'] ?? ($company['legal_name'] ?? null),
            'address' => $general['office_address'] ?? 'Sumatera Utara',
            'email' => $general['contact_email'] ?? null,
            'instagram' => $general['social_instagram'] ?? null,
            'logoUrl' => $logoRaw ? imageUrl($logoRaw) : null,
        ];

        return view('invoice.show', $data);
    }
}
