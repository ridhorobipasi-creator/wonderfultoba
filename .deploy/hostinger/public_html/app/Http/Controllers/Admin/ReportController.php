<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function financial(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));

        $query = Booking::whereYear('createdAt', $year);
        if ($month !== 'all') {
            $query->whereMonth('createdAt', $month);
        }

        // 1. Monthly/Filtered Summary
        $monthlyBookings = $query->clone()
            ->where('status', 'confirmed')
            ->get();

        $stats = [
            'total_orders' => $monthlyBookings->count(),
            'revenue' => $monthlyBookings->sum('totalPrice'),
            'tour' => $monthlyBookings->where('package.isOutbound', false)->count(),
            'outbound' => $monthlyBookings->where('package.isOutbound', true)->count(),
        ];

        // 2. Status Summary (Monthly/Filtered)
        $statusCounts = $query->clone()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusSummary = [
            'pending' => $statusCounts['pending'] ?? 0,
            'confirmed' => $statusCounts['confirmed'] ?? 0,
            'finished' => $statusCounts['finished'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0,
        ];

        // 3. Yearly Summary
        $yearlyBookings = Booking::whereYear('createdAt', $year)
            ->where('status', 'confirmed')
            ->get();

        $yearlySummary = [
            'orders' => $yearlyBookings->count(),
            'revenue' => $yearlyBookings->sum('totalPrice'),
            'tour' => $yearlyBookings->where('package.isOutbound', false)->count(),
            'outbound' => $yearlyBookings->where('package.isOutbound', true)->count(),
        ];

        // 4. Monthly Chart Data
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "CAST(strftime('%m', createdAt) AS INTEGER)" : "MONTH(createdAt)";

        $chartData = Booking::whereYear('createdAt', $year)
            ->where('status', 'confirmed')
            ->select(
                DB::raw("$monthExpr as month_num"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month_num')
            ->get()
            ->pluck('total', 'month_num')
            ->toArray();

        $monthlyChart = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyChart[$m] = $chartData[$m] ?? 0;
        }

        // 5. Booking List
        $bookings = $query->clone()
            ->with(['package', 'customer'])
            ->latest('createdAt')
            ->get();

        return view('admin.reports.financial', compact(
            'year', 'month', 'stats', 'statusSummary', 'yearlySummary', 'monthlyChart', 'bookings'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));
        $format = $request->get('format', 'csv');
        
        $exportQuery = Booking::with(['package', 'customer'])
            ->whereYear('createdAt', $year);
        
        if ($month !== 'all') {
            $exportQuery->whereMonth('createdAt', $month);
        }

        $bookings = $exportQuery->latest('createdAt')->get();

        if ($format === 'xlsx') {
            $filename = "Laporan_Keuangan_{$year}_{$month}.xlsx";
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\FinancialExport($bookings), 
                $filename
            );
        }

        // Default CSV
        $filename = "Laporan_Keuangan_{$year}_{$month}.csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['No', 'Tgl Pesan', 'ID Transaksi', 'Tipe', 'Item', 'Pelanggan', 'Total', 'Status']);

        foreach ($bookings as $index => $booking) {
            fputcsv($handle, [
                $index + 1,
                $booking->createdAt->format('d/m/Y'),
                $booking->bookingCode,
                $booking->package?->isOutbound ? 'Outbound' : 'Tour',
                $booking->package?->name ?? 'Custom',
                $booking->customer?->name ?? 'Demo User',
                $booking->totalPrice,
                ucfirst($booking->status)
            ]);
        }

        fclose($handle);
        exit;
    }
}
