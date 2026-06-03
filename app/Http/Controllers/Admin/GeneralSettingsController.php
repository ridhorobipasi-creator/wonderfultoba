<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GeneralSettingsController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $settings = Setting::where('key', 'general')->first();
        $general = $settings ? $settings->value : [];

        $companySetting = Setting::where('key', 'company')->first();
        $company = $companySetting ? $companySetting->value : [];

        return view('admin.settings.index', compact('general', 'company'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'logo_light_file', 'logo_dark_file', 'icon_file', 'company']);

        $setting = Setting::firstOrCreate(['key' => 'general']);
        $existing = $setting->value ?? [];

        // Handle File Uploads
        if ($request->hasFile('logo_light_file')) {
            $data['logo_light_url'] = $this->uploadAndIndex($request->file('logo_light_file'), 'branding', 'branding');
        }
        if ($request->hasFile('logo_dark_file')) {
            $data['logo_dark_url'] = $this->uploadAndIndex($request->file('logo_dark_file'), 'branding', 'branding');
        }
        if ($request->hasFile('icon_file')) {
            $data['icon_url'] = $this->uploadAndIndex($request->file('icon_file'), 'branding', 'branding');
        }

        // Handle Media Library Selections
        if ($request->filled('logo_light_url')) {
            $data['logo_light_url'] = $request->logo_light_url;
        }
        if ($request->filled('logo_dark_url')) {
            $data['logo_dark_url'] = $request->logo_dark_url;
        }
        if ($request->filled('icon_url')) {
            $data['icon_url'] = $request->icon_url;
        }

        $finalData = array_merge($existing, $data);
        $setting->value = $finalData;
        $setting->save();

        // Company / invoice identity is stored as its own settings group,
        // because PdfController & InvoiceService load it under the 'company' key.
        if ($request->has('company')) {
            $companySetting = Setting::firstOrCreate(['key' => 'company']);
            $companySetting->value = array_merge($companySetting->value ?? [], $request->input('company'));
            $companySetting->save();
        }

        Cache::flush();

        return back()->with('success', 'Pengaturan umum berhasil diperbarui!');
    }
}
