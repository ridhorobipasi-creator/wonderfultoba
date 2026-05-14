<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class OutboundService
{
    /**
     * Process an outbound quote request and generate WA Message.
     *
     * @param array $data
     * @return string WhatsApp URL
     */
    public function processQuoteRequest(array $data)
    {
        // 1. Construct WhatsApp Message
        $waMessage = "*PERMINTAAN PENAWARAN OUTBOUND*\n\n" .
                     "Nama Instansi/PIC: " . $data['company_name'] . "\n" .
                     "Jumlah Peserta: " . $data['participants'] . "\n" .
                     "Lokasi Kegiatan: " . $data['location'] . "\n" .
                     "Jenis Kegiatan: " . $data['activity_type'] . "\n" .
                     "Estimasi Tanggal: " . date('d F Y', strtotime($data['estimated_date'])) . "\n" .
                     "WhatsApp: " . $data['whatsapp'] . "\n\n" .
                     "Mohon segera dibuatkan penawarannya. Terima kasih!";

        // 2. Get Admin Phone Number from Settings
        $settings = Setting::where('key', 'cms_outbound')->first()?->value ?? [];
        $genSettings = Setting::where('key', 'general')->first()?->value ?? [];
        $waNumber = preg_replace('/[^0-9]/', '', $settings['cta_whatsapp_number'] ?? $genSettings['whatsapp'] ?? '6281323888207');
        
        return "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);
    }
}
