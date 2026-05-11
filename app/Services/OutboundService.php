<?php

namespace App\Services;

use App\Models\Client;
use App\Models\GalleryImage;
use App\Models\OutboundLocation;
use App\Models\OutboundService as OutboundServiceModel;
use App\Models\OutboundVideo;
use App\Models\Package;
use App\Models\PackageTier;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OutboundService
{
    /**
     * Get outbound landing page settings
     */
    public function getOutboundSettings(): array
    {
        return Setting::where('key', 'cms_outbound')->first()?->value ?? [];
    }

    /**
     * Get all active outbound packages
     */
    public function getPackages(): Collection
    {
        return Package::where('status', 'active')
            ->where('isOutbound', true)
            ->get();
    }

    /**
     * Get featured/pinned outbound packages
     */
    public function getFeaturedPackages($limit = 3): Collection
    {
        $settings = $this->getOutboundSettings();
        $pinnedIds = $settings['featured_package_ids'] ?? [];

        if (!empty($pinnedIds)) {
            return Package::whereIn('id', (array)$pinnedIds)
                ->where('status', 'active')
                ->where('isOutbound', true)
                ->get();
        }

        return Package::where('status', 'active')
            ->where('isOutbound', true)
            ->where('isFeatured', true)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get outbound-specific services
     */
    public function getServices(): Collection
    {
        return OutboundServiceModel::all();
    }

    /**
     * Get video highlights
     */
    public function getVideos(): Collection
    {
        return OutboundVideo::all();
    }

    /**
     * Get outbound locations
     */
    public function getLocations(): Collection
    {
        return OutboundLocation::all();
    }

    /**
     * Get corporate clients
     */
    public function getClients(): Collection
    {
        return Client::all();
    }

    /**
     * Get outbound gallery
     */
    public function getGallery(): Collection
    {
        return GalleryImage::where('category', 'outbound')->get();
    }

    /**
     * Get service tiers
     */
    public function getTiers(): Collection
    {
        return PackageTier::all();
    }
}
