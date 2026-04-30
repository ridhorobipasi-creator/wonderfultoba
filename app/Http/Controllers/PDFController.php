<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    private function decodeJsonFields($item, $fields)
    {
        if (! $item) {
            return $item;
        }
        foreach ($fields as $field) {
            if (isset($item->$field) && is_string($item->$field)) {
                $item->$field = json_decode($item->$field, true);
            }
        }

        return $item;
    }

    public function downloadItinerary($slug)
    {
        $package = DB::table('packages')
            ->where('slug', $slug)
            ->first();

        if (! $package) {
            abort(404);
        }

        $package = $this->decodeJsonFields($package, [
            'images', 'includes', 'excludes', 'pricingDetails', 'itinerary',
        ]);

        $city = DB::table('cities')->where('id', $package->cityId)->first();

        $pdf = Pdf::loadView('pdf.itinerary', compact('package', 'city'));

        return $pdf->download('Itinerary-'.$package->slug.'.pdf');
    }
}
