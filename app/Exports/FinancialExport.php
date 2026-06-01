<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bookings;

    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tgl Pesan',
            'ID Transaksi',
            'Tipe',
            'Item',
            'Pelanggan',
            'Total',
            'Status',
        ];
    }

    public function map($booking): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $booking->createdAt->format('d/m/Y H:i'),
            $booking->bookingCode,
            $booking->package?->isOutbound ? 'Outbound' : 'Tour',
            $booking->package?->name ?? 'Custom',
            $booking->customer?->name ?? 'Demo User',
            'Rp '.number_format($booking->totalPrice, 0, ',', '.'),
            ucfirst($booking->status),
        ];
    }
}
