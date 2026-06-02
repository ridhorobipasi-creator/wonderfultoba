<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Traits\LogsActivity;

class FinanceController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $transactions = Booking::where('status', 'confirmed')
            ->latest('createdAt')
            ->paginate(20);

        return view('admin.finance.index', compact('transactions'));
    }

    public function export()
    {
        $transactions = Booking::where('status', 'confirmed')
            ->latest('createdAt')
            ->get();

        $filename = 'finance-report-'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Booking Code', 'Customer', 'Email', 'Type', 'Item', 'Total Price', 'Booking Date']);

            foreach ($transactions as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->bookingCode,
                    $booking->customerName,
                    $booking->customerEmail,
                    ucfirst($booking->type),
                    $booking->package->name ?? 'N/A',
                    $booking->totalPrice,
                    $booking->createdAt->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        $this->logActivity('export', 'Exported finance report');

        return response()->stream($callback, 200, $headers);
    }
}
