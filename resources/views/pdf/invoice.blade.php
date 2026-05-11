<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->bookingCode }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { border-bottom: 2px solid #10b981; padding-bottom: 20px; margin-bottom: 30px; }
        .logo-text { font-size: 28px; font-weight: 900; color: #064e3b; text-transform: uppercase; letter-spacing: -1px; }
        .invoice-title { float: right; font-size: 32px; font-weight: 100; color: #94a3b8; margin-top: -10px; }
        .info-section { margin-bottom: 40px; }
        .info-col { width: 48%; display: inline-block; vertical-align: top; }
        .label { font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .value { font-size: 14px; font-weight: 600; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .table th { background-color: #f8fafc; text-align: left; padding: 12px; font-size: 10px; text-transform: uppercase; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 15px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .total-section { float: right; width: 40%; }
        .total-row { padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .total-row.final { border-bottom: 2px solid #10b981; color: #059669; font-size: 18px; font-weight: 900; }
        .footer { margin-top: 100px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 11px; color: #94a3b8; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 10px; font-weight: 800; text-transform: uppercase; background-color: #ecfdf5; color: #059669; }
        .payment-box { margin-top: 40px; background-color: #f8fafc; padding: 20px; border-radius: 12px; }
    </style>
</head>
<body>
    @php
        $companyName     = $siteSettings['general']['site_name'] ?? 'Wonderful Toba';
        $legalName       = $siteSettings['company']['legal_name'] ?? 'PT Wonderful Toba Experience';
        $bankAccount     = $siteSettings['company']['bank_account'] ?? null;
        $bankAccountName = $siteSettings['company']['bank_account_name'] ?? $legalName;
        $taxId           = $siteSettings['company']['tax_id'] ?? null;
        $address         = $siteSettings['general']['address'] ?? 'Sumatera Utara';
        $email           = $siteSettings['general']['contact_email'] ?? '';
    @endphp

    <div class="container">
        <div class="header">
            <div class="invoice-title">INVOICE</div>
            @php
                $logoUrl = $siteSettings['general']['logo_url'] ?? ($siteSettings['cms_landing']['brand_logo_url'] ?? null);
            @endphp
            @if(!empty($logoUrl))
                @php
                    $logoPath = ltrim(str_replace('storage/', '', $logoUrl), '/');
                    $fullPath = storage_path('app/public/' . $logoPath);
                @endphp
                @if(file_exists($fullPath))
                    @php $mime = mime_content_type($fullPath) ?: 'image/png'; @endphp
                    <img src="data:{{ $mime }};base64,{{ base64_encode(file_get_contents($fullPath)) }}" style="height: 40px; width: auto;">
                @else
                    <div class="logo-text">{{ $companyName }}</div>
                @endif
            @else
                <div class="logo-text">{{ $companyName }}</div>
            @endif
            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                {{ $legalName }}@if($taxId) &nbsp;&middot;&nbsp; NPWP: {{ $taxId }}@endif
            </div>
        </div>

        <div class="info-section">
            <div class="info-col">
                <div class="label">Diterbitkan Untuk:</div>
                <div class="value">{{ $booking->customerName }}</div>
                <div class="value">{{ $booking->customerEmail }}</div>
                <div class="value">{{ $booking->customerPhone }}</div>
            </div>
            <div class="info-col" style="text-align: right;">
                <div class="label">Rincian Invoice:</div>
                <div class="value" style="color: #10b981;">{{ $booking->bookingCode }}</div>
                <div class="value">Tanggal Pesanan: {{ optional($booking->startDate)->format('d M Y') ?? '-' }}</div>
                <div class="value">Status: <span class="badge">{{ strtoupper($booking->status) }}</span></div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60%;">Deskripsi Layanan</th>
                    <th style="text-align: center;">Kuantitas</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if($booking->type === 'package' && $booking->package)
                            <div class="value">{{ $booking->package->name }}</div>
                            <div style="font-size: 11px; color: #64748b;">
                                {{ $booking->package->duration ?? '' }} –
                                Destinasi: {{ $booking->package->city->name ?? 'Sumatera Utara' }}
                            </div>
                        @else
                            <div class="value">Layanan {{ $companyName }}</div>
                            <div style="font-size: 11px; color: #64748b;">Pemesanan Layanan Wisata</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $booking->metadata['pax'] ?? 1 }} Pax</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->totalPrice / max(($booking->metadata['pax'] ?? 1), 1), 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->totalPrice, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span class="label">Subtotal</span>
                <span class="value" style="float: right;">Rp {{ number_format($booking->totalPrice, 0, ',', '.') }}</span>
                <div style="clear: both;"></div>
            </div>
            <div class="total-row">
                <span class="label">Pajak (0%)</span>
                <span class="value" style="float: right;">Rp 0</span>
                <div style="clear: both;"></div>
            </div>
            <div class="total-row final">
                <span style="text-transform: uppercase; font-size: 12px; font-weight: 900;">Total Bayar</span>
                <span style="float: right;">Rp {{ number_format($booking->totalPrice, 0, ',', '.') }}</span>
                <div style="clear: both;"></div>
            </div>
        </div>

        <div style="clear: both;"></div>

        <div class="payment-box">
            <div class="label">Instruksi Pembayaran:</div>
            <div style="font-size: 12px; color: #475569;">
                @if($bankAccount)
                    Silakan lakukan pembayaran ke rekening berikut:<br><br>
                    <strong>{{ $bankAccount }}</strong> (a.n {{ $bankAccountName }})<br><br>
                @endif
                <em>Mohon lampirkan kode booking <strong>{{ $booking->bookingCode }}</strong> pada berita transfer.</em>
            </div>
        </div>

        <div class="footer">
            <p>{{ $companyName }} – Premium Tour & Travel Sumatera Utara</p>
            <p>{{ $address }}@if($email) &nbsp;|&nbsp; {{ $email }}@endif</p>
        </div>
    </div>
</body>
</html>
