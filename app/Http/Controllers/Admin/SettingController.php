<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // For array values (grouped settings), merge with existing data
                $setting = Setting::firstOrCreate(['key' => $key]);
                $existingValue = $setting->value ?? [];
                
                // Recursively merge to handle deep structures like tour_landing[hero][title]
                $newValue = array_replace_recursive($existingValue, $value);
                
                $setting->update(['value' => $newValue]);
            } else {
                // For simple key-value pairs (if any)
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
