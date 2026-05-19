<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    /**
     * Get the current CMS sync version.
     */
    public function getVersion()
    {
        $version = Setting::where('key', 'cms_sync_version')->first()?->value ?? '0';
        return response()->json(['version' => $version]);
    }

    /**
     * Update the CMS sync version to trigger frontend refreshes.
     */
    public static function triggerSync()
    {
        // 1. Clear server-side cache so new data is fetched immediately
        \Illuminate\Support\Facades\Cache::flush();

        // 2. Update version for client-side realtime sync
        Setting::updateOrCreate(
            ['key' => 'cms_sync_version'],
            ['value' => (string)time()]
        );
    }
}
