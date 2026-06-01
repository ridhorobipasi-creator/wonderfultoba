<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /**
     * Change active language session.
     * Allowed locales: 'my' (Malaysia), 'id' (Indonesia), 'en' (English).
     *
     * @param  string  $locale
     * @return RedirectResponse
     */
    public function changeLocale($locale)
    {
        if (in_array($locale, ['my', 'id', 'en'])) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
