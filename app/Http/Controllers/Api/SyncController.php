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
        Setting::updateOrCreate(
            ['key' => 'cms_sync_version'],
            ['value' => (string)time()]
        );
    }
}
