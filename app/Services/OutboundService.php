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

    /**
     * Get CMS Outbound Settings.
     */
    public function getOutboundSettings()
    {
        return Setting::where('key', 'cms_outbound')->first()?->value ?? [];
    }

    /**
     * Get all active outbound services.
     */
    public function getServices()
    {
        return \App\Models\OutboundService::where('isActive', true)
            ->orderBy('orderPriority')
            ->get();
    }

    /**
     * Get all outbound videos.
     */
    public function getVideos()
    {
        return \App\Models\OutboundVideo::latest('createdAt')->get();
    }

    /**
     * Get all outbound locations.
     */
    public function getLocations()
    {
        return \App\Models\OutboundLocation::all();
    }

    /**
     * Get all active clients.
     */
    public function getClients()
    {
        return \App\Models\Client::where('isActive', true)
            ->orderBy('orderPriority')
            ->get();
    }

    /**
     * Get active gallery images for Outbound.
     */
    public function getGallery()
    {
        return \App\Models\GalleryImage::where('isActive', true)
            ->where('category', 'Outbound')
            ->orderBy('orderPriority')
            ->get();
    }

    /**
     * Get featured outbound active packages.
     */
    public function getFeaturedPackages($limit = 3)
    {
        return \App\Models\Package::where('status', 'active')
            ->where('isOutbound', true)
            ->where('isFeatured', true)
            ->orderBy('sortOrder')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all active outbound packages.
     */
    public function getPackages()
    {
        return \App\Models\Package::where('status', 'active')
            ->where('isOutbound', true)
            ->orderBy('sortOrder')
            ->get();
    }

    /**
     * Get all outbound package tiers.
     */
    public function getTiers()
    {
        return \App\Models\PackageTier::where('category', 'outbound')->get();
    }
}

