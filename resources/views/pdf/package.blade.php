<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Itinerary - {{ $package->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #10B981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0f172a;
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 14px;
        }
        .package-title {
            font-size: 24px;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .meta-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            padding: 5px 0;
            font-size: 14px;
        }
        .meta-info strong {
            color: #10B981;
        }
        .price {
            font-size: 20px;
            font-weight: bold;
            color: #ef4444;
            text-align: right;
        }
        .section-title {
            font-size: 18px;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .description {
            font-size: 14px;
            text-align: justify;
        }
        .inc-exc-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .inc-exc-table td {
            vertical-align: top;
            width: 50%;
            padding-right: 15px;
        }
        .inc-exc-list {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
        }
        .inc-exc-list li {
            margin-bottom: 5px;
        }
        .itinerary-item {
            margin-bottom: 15px;
        }
        .itinerary-day {
            font-weight: bold;
            color: #10B981;
            font-size: 14px;
        }
        .itinerary-title {
            font-weight: bold;
            color: #0f172a;
            font-size: 14px;
        }
        .itinerary-activities {
            margin: 5px 0 0 0;
            padding-left: 20px;
            font-size: 13px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Wonderful Toba</h1>
        <p>Solusi Perjalanan & Outbound Terbaik di Sumatera Utara</p>
        <p>Telp: {{ $settings['contact_wa'] ?? '+6281234567890' }} | Email: {{ $settings['contact_email'] ?? 'hello@wonderfultoba.id' }}</p>
    </div>

    <div class="package-title">{{ $package->name }}</div>

    <div class="meta-info">
        <table>
            <tr>
                <td><strong>Durasi:</strong> {{ $package->duration }}</td>
                <td class="price">Rp {{ number_format($package->price, 0, ',', '.') }} <span style="font-size:12px;color:#64748b;font-weight:normal;">/ pax</span></td>
            </tr>
            <tr>
                <td><strong>Lokasi:</strong> {{ $package->locationTag ?? 'Sumatera Utara' }}</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="section-title">Deskripsi Paket</div>
    <div class="description">
        {{ $package->description }}
    </div>

    <table class="inc-exc-table">
        <tr>
            <td>
                <div class="section-title">Fasilitas Termasuk (Include)</div>
                <ul class="inc-exc-list">
                    @forelse($package->includes ?? [] as $inc)
                        <li>{{ $inc }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ul>
            </td>
            <td>
                <div class="section-title">Tidak Termasuk (Exclude)</div>
                <ul class="inc-exc-list">
                    @forelse($package->excludes ?? [] as $exc)
                        <li>{{ $exc }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ul>
            </td>
        </tr>
    </table>

    <div class="section-title">Itinerary Perjalanan</div>
    @forelse($package->itinerary ?? [] as $item)
        <div class="itinerary-item">
            <span class="itinerary-day">Hari ke-{{ $item['day'] }}:</span>
            <span class="itinerary-title">{{ $item['title'] }}</span>
            <ul class="itinerary-activities">
                @foreach($item['activities'] ?? [] as $act)
                    <li>{{ $act }}</li>
                @endforeach
            </ul>
        </div>
    @empty
        <p style="font-size:13px;">Detail itinerary belum tersedia.</p>
    @endforelse

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem Wonderful Toba.<br>
        Harga dan ketersediaan dapat berubah sewaktu-waktu. Silakan hubungi admin untuk konfirmasi.
    </div>

</body>
</html>
