<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Itinerary {{ $package->name }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { color: #10b981; font-size: 24px; font-weight: bold; }
        .package-title { font-size: 22px; font-weight: 800; margin-bottom: 10px; }
        .meta { color: #666; font-size: 14px; margin-bottom: 20px; }
        .section-title { font-size: 18px; font-weight: bold; color: #10b981; border-left: 4px solid #10b981; padding-left: 10px; margin: 30px 0 15px; }
        .itinerary-day { margin-bottom: 20px; }
        .day-title { font-weight: bold; font-size: 16px; margin-bottom: 5px; }
        .day-desc { font-size: 14px; color: #555; }
        .grid { display: table; width: 100%; border-collapse: collapse; }
        .col { display: table-cell; width: 50%; vertical-align: top; padding: 10px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; pt: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">WONDERFUL TOBA</div>
        <div class="package-title">{{ $package->name }}</div>
        <div class="meta">{{ $package->duration }} | {{ $city->name ?? 'Sumatera Utara' }}</div>
    </div>

    <div class="section-title">Overview</div>
    <p style="font-size: 14px;">{{ $package->description }}</p>

    <div class="section-title">Itinerary Perjalanan</div>
    @foreach(($package->itinerary ?? []) as $item)
        <div class="itinerary-day">
            <div class="day-title">Hari {{ $item['day'] }}: {{ $item['title'] }}</div>
            <div class="day-desc">{{ $item['description'] }}</div>
        </div>
    @endforeach

    <div class="grid">
        <div class="col">
            <div class="section-title">Harga Termasuk</div>
            <ul style="font-size: 13px;">
                @foreach(($package->includes ?? []) as $inc)
                    <li>{{ $inc }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col">
            <div class="section-title">Harga Tidak Termasuk</div>
            <ul style="font-size: 13px;">
                @foreach(($package->excludes ?? []) as $exc)
                    <li>{{ $exc }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Copyright &copy; {{ date('Y') }} Wonderful Toba Travel. Seluruh hak cipta dilindungi.</p>
        <p>Hubungi Kami: +62 813-2388-8207 | outbound@wonderfultoba.com</p>
    </div>
</body>
</html>
