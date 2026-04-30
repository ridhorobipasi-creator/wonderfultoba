<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::where('key', 'cms_landing')->first()?->value ?? [];
        return view('admin.cms.index', compact('settings'));
    }

    public function tour()
    {
        $settings = \App\Models\Setting::where('key', 'cms_tour')->first()?->value ?? [];
        return view('admin.cms.tour', compact('settings'));
    }

    public function outbound()
    {
        $settings = \App\Models\Setting::where('key', 'cms_outbound')->first()?->value ?? [];
        return view('admin.outbound.cms', compact('settings'));
    }

    public function save(Request $request, $key)
    {
        $data = $request->except('_token');
        
        \App\Models\Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $data]
        );

        return back()->with('success', 'Konfigurasi CMS berhasil disimpan!');
    }
}
