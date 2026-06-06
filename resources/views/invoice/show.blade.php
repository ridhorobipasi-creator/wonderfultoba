<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $booking->bookingCode }} - {{ $companyName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#004d40',
                            DEFAULT: '#006B54',
                            light: '#e0f2f1',
                            accent: '#d4af37',
                        },
                        neutral: {
                            900: '#1a202c',
                            800: '#2d3748',
                            600: '#718096',
                            300: '#e2e8f0',
                            100: '#f7fafc',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    backgroundImage: {
                        'pattern-subtle': "url('data:image/svg+xml,%3Csvg width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 20 20\\' xmlns=\\'http://www.w3.org/2000/svg\\'%3E%3Cg fill=\\'%23006b54\\' fill-opacity=\\'0.03\\' fill-rule=\\'evenodd\\'%3E%3Ccircle cx=\\'3\\' cy=\\'3\\' r=\\'3\\'/%3E%3Ccircle cx=\\'13\\' cy=\\'13\\' r=\\'3\\'/%3E%3C/g%3E%3C/svg%3E')",
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f1f5f9;
            -webkit-font-smoothing: antialiased;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .invoice-card {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }
        .invoice-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #004d40 0%, #006B54 50%, #d4af37 100%);
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .table-row-hover:hover {
            background-color: #f8fafc;
            transition: background-color 0.2s ease;
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media print {
            body { background: white; }
            .invoice-card { box-shadow: none; margin: 0; padding: 0; max-width: 100%; }
            .invoice-card::before { display: none; }
            .no-print { display: none !important; }
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
@php
    $pax        = max((int) ($booking->metadata['pax'] ?? 1), 1);
    $unitPrice  = $booking->totalPrice / $pax;

    if ($booking->type === 'package' && $booking->package) {
        $itemName = $booking->package->name;
        $itemDesc = trim(($booking->package->duration ?? '') . ' menikmati pesona wisata Sumatera Utara.');
        $itemDest = $booking->package->city->name ?? 'Sumatera Utara';
    } else {
        $itemName = 'Layanan ' . $companyName;
        $itemDesc = 'Pemesanan layanan wisata.';
        $itemDest = 'Sumatera Utara';
    }

    $statusMap = [
        'pending'   => ['label' => 'MENUNGGU PEMBAYARAN', 'bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'border' => 'border-amber-200',   'dot' => 'bg-amber-500'],
        'confirmed' => ['label' => 'PEMBAYARAN DIKONFIRMASI', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
        'completed' => ['label' => 'SELESAI',              'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
        'cancelled' => ['label' => 'DIBATALKAN',           'bg' => 'bg-rose-100',    'text' => 'text-rose-700',    'border' => 'border-rose-200',    'dot' => 'bg-rose-500'],
    ];
    $st = $statusMap[$booking->status] ?? $statusMap['pending'];
@endphp
<body class="py-12 px-4 sm:px-6 lg:px-8 flex justify-center min-h-screen">

    <div class="invoice-card bg-white w-full max-w-[850px] mx-auto rounded-lg overflow-hidden border border-neutral-300">

        <!-- Watermark effect -->
        <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-[0.02] pointer-events-none select-none z-0">
            <i class="fa-solid fa-mountain-sun text-[300px]"></i>
        </div>

        <div class="p-10 sm:p-14 relative z-10 bg-pattern-subtle">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start mb-10 pb-8 border-b-2 border-brand-light">
                <div class="flex items-center gap-4">
                    @if(!empty($logoUrl))
                        <div class="h-14 flex items-center">
                            <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="h-14 w-auto object-contain">
                        </div>
                    @else
                        <div class="w-14 h-14 bg-brand rounded-lg flex items-center justify-center text-white shadow-md">
                            <i class="fa-solid fa-water text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="font-serif text-3xl sm:text-4xl text-brand-dark font-bold tracking-tight mb-1">{{ Str::upper($companyName) }}</h1>
                        <p class="text-neutral-600 text-sm font-medium tracking-wide">{{ $legalName }}@if($taxId) &middot; NPWP: {{ $taxId }}@endif</p>
                    </div>
                </div>
                <div class="mt-6 sm:mt-0 text-left sm:text-right">
                    <h2 class="text-4xl sm:text-5xl font-serif italic text-brand-light font-bold opacity-30 absolute top-10 right-14 pointer-events-none select-none z-0">INVOICE</h2>
                    <h2 class="text-2xl font-bold text-neutral-800 tracking-wider relative z-10">INVOICE</h2>
                    <p class="text-sm text-neutral-500 mt-1 font-medium relative z-10">#{{ $booking->bookingCode }}</p>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- Billed To -->
                <div class="bg-neutral-100/50 p-5 rounded-xl border border-neutral-200">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-user-tag text-brand text-sm"></i>
                        <h3 class="text-xs font-bold text-brand uppercase tracking-wider">Diterbitkan Untuk</h3>
                    </div>
                    <div class="space-y-2">
                        <p class="text-lg font-bold text-neutral-900">{{ $booking->customerName }}</p>
                        @if($booking->customerEmail)
                        <div class="flex items-center gap-2 text-sm text-neutral-600">
                            <i class="fa-regular fa-envelope w-4"></i>
                            <span>{{ $booking->customerEmail }}</span>
                        </div>
                        @endif
                        @if($booking->customerPhone)
                        <div class="flex items-center gap-2 text-sm text-neutral-600">
                            <i class="fa-solid fa-phone w-4"></i>
                            <span>{{ $booking->customerPhone }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="bg-brand-light/30 p-5 rounded-xl border border-brand-light/50">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-circle-info text-brand text-sm"></i>
                        <h3 class="text-xs font-bold text-brand uppercase tracking-wider">Rincian Invoice</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-neutral-600">No. Referensi:</span>
                            <span class="font-bold text-neutral-900 bg-white px-2 py-1 rounded shadow-sm border border-neutral-200">{{ $booking->bookingCode }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-neutral-600">Tanggal Pesanan:</span>
                            <span class="font-semibold text-neutral-900">{{ optional($booking->startDate)->format('d M Y') ?? optional($booking->created_at)->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-neutral-600">Status Pembayaran:</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $st['bg'] }} {{ $st['text'] }} border {{ $st['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                {{ $st['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="mb-10 bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="py-4 px-6 text-xs font-bold text-neutral-600 uppercase tracking-wider w-1/2">Deskripsi Layanan</th>
                            <th class="py-4 px-6 text-xs font-bold text-neutral-600 uppercase tracking-wider text-center">Kuantitas</th>
                            <th class="py-4 px-6 text-xs font-bold text-neutral-600 uppercase tracking-wider text-right">Harga Satuan</th>
                            <th class="py-4 px-6 text-xs font-bold text-brand uppercase tracking-wider text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @if(isset($booking->metadata['price_breakdown']))
                            @php $pb = $booking->metadata['price_breakdown']; @endphp
                            <!-- Ekspedisi Dewasa -->
                            <tr class="table-row-hover border-b border-neutral-100">
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded-full bg-brand-light flex items-center justify-center text-brand flex-shrink-0">
                                            <i class="fa-solid fa-map-location-dot text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-neutral-900 text-sm mb-1">{{ $itemName }} (Dewasa)</p>
                                            <p class="text-xs text-neutral-500 leading-relaxed">
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-neutral-100 rounded text-xs font-medium text-neutral-600"><i class="fa-solid fa-location-dot mr-1"></i> Destinasi: {{ $itemDest }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 align-middle text-center font-semibold text-neutral-700">{{ $pb['pax_dewasa'] }}x</td>
                                <td class="py-4 px-6 align-middle text-right text-neutral-700">Rp {{ number_format($pb['price_dewasa_total'] / max($pb['pax_dewasa'], 1), 0, ',', '.') }}</td>
                                <td class="py-4 px-6 align-middle text-right text-neutral-900 font-bold">Rp {{ number_format($pb['price_dewasa_total'], 0, ',', '.') }}</td>
                            </tr>
                            <!-- Anak-anak -->
                            @if(isset($pb['pax_anak']) && $pb['pax_anak'] > 0)
                            <tr class="table-row-hover border-b border-neutral-100">
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-400 flex-shrink-0">
                                            <i class="fa-solid fa-child text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-neutral-900 text-sm mb-1">{{ $itemName }} (Anak-anak)</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 align-middle text-center font-semibold text-neutral-700">{{ $pb['pax_anak'] }}x</td>
                                <td class="py-4 px-6 align-middle text-right text-neutral-700">Rp {{ number_format($pb['price_anak_total'] / max($pb['pax_anak'], 1), 0, ',', '.') }}</td>
                                <td class="py-4 px-6 align-middle text-right text-neutral-900 font-bold">Rp {{ number_format($pb['price_anak_total'], 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <!-- Additional Services -->
                            @if(isset($pb['additional_services']))
                                @foreach($pb['additional_services'] as $srv)
                                <tr class="table-row-hover border-b border-neutral-100">
                                    <td class="py-4 px-6 align-middle">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 w-8 h-8 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-400 flex-shrink-0">
                                                <i class="fa-solid fa-star text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-neutral-900 text-sm mb-1">{{ $srv['name'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 align-middle text-center font-semibold text-neutral-700">1x</td>
                                    <td class="py-4 px-6 align-middle text-right text-neutral-700">Rp {{ number_format($srv['price'], 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 align-middle text-right text-neutral-900 font-bold">Rp {{ number_format($srv['price'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            @endif
                        @else
                            <tr class="table-row-hover">
                                <td class="py-6 px-6 align-top">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded-full bg-brand-light flex items-center justify-center text-brand flex-shrink-0">
                                            <i class="fa-solid fa-map-location-dot text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-neutral-900 text-base mb-1">{{ $itemName }}</p>
                                            <p class="text-sm text-neutral-500 leading-relaxed">
                                                {{ $itemDesc }} <br>
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-neutral-100 rounded text-xs font-medium text-neutral-600"><i class="fa-solid fa-location-dot mr-1"></i> Destinasi: {{ $itemDest }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-6 align-middle text-center font-semibold text-neutral-700">{{ $pax }} Pax</td>
                                <td class="py-6 px-6 align-middle text-right text-neutral-700">Rp {{ number_format($unitPrice, 0, ',', '.') }}</td>
                                <td class="py-6 px-6 align-middle text-right text-neutral-900 font-bold">Rp {{ number_format($booking->totalPrice, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="table-row-hover bg-white/50 h-8">
                            <td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-12">

                <!-- Payment Instructions -->
                <div class="w-full md:w-1/2">
                    <div class="bg-gradient-to-br from-brand-dark to-brand rounded-xl p-6 text-white shadow-md relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 opacity-10">
                            <i class="fa-solid fa-money-bill-wave text-8xl"></i>
                        </div>

                        <div class="flex items-center gap-2 mb-3 relative z-10">
                            <i class="fa-solid fa-building-columns text-brand-accent"></i>
                            <h4 class="text-xs font-bold text-brand-accent uppercase tracking-wider">Instruksi Pembayaran</h4>
                        </div>
                        <p class="text-sm text-brand-light font-medium leading-relaxed relative z-10">
                            Mohon lakukan transfer ke rekening berikut dan lampirkan kode referensi <span class="bg-white/20 px-1.5 py-0.5 rounded text-white font-bold tracking-wider">{{ $booking->bookingCode }}</span> pada berita acara transfer Anda.
                        </p>

                        @if($bankAccount)
                        <div class="mt-4 pt-4 border-t border-white/20 relative z-10">
                            <div class="flex items-center gap-3">
                                <div class="bg-white/15 rounded p-2 flex items-center justify-center">
                                    <i class="fa-solid fa-building-columns text-brand-accent"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-brand-light">a.n {{ $bankAccountName }}</p>
                                    <p class="font-bold tracking-wider">{{ $bankAccount }}
                                        <button class="ml-2 text-brand-accent hover:text-white transition-colors no-print" onclick="copyAccount(this)" data-account="{{ $bankAccount }}" title="Salin Rekening"><i class="fa-regular fa-copy"></i></button>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Calculation -->
                <div class="w-full md:w-5/12 bg-neutral-50 p-6 rounded-xl border border-neutral-200">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-neutral-600 font-medium">Subtotal</span>
                            <span class="font-bold text-neutral-800">Rp {{ number_format(isset($booking->metadata['price_breakdown']) ? $booking->metadata['price_breakdown']['subtotal'] : $booking->totalPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-neutral-600 font-medium">Pajak & Layanan</span>
                            <span class="font-bold text-neutral-800">Rp {{ number_format(isset($booking->metadata['price_breakdown']) ? ($booking->metadata['price_breakdown']['tax'] ?? 0) : 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-neutral-600 font-medium">Diskon</span>
                            <span class="font-bold text-neutral-800">- Rp 0</span>
                        </div>

                        <div class="pt-4 border-t border-neutral-300 border-dashed">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="block text-brand-dark font-bold uppercase tracking-wider text-xs mb-1">Total Tagihan</span>
                                    <span class="block text-[10px] text-neutral-500">(IDR)</span>
                                </div>
                                <span class="text-2xl font-bold text-brand-dark">Rp {{ number_format($booking->totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-8 border-t border-neutral-200 text-center">
                <p class="font-serif italic text-lg text-brand-dark mb-3">"Terima kasih telah memilih kami untuk petualangan Anda selanjutnya."</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-4 text-xs text-neutral-500">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $address }}</span>
                    @if($email)
                    <span class="hidden sm:inline text-neutral-300">|</span>
                    <a href="mailto:{{ $email }}" class="flex items-center gap-1 hover:text-brand transition-colors"><i class="fa-solid fa-envelope"></i> {{ $email }}</a>
                    @endif
                    @if($instagram)
                    <span class="hidden sm:inline text-neutral-300">|</span>
                    <a href="https://instagram.com/{{ ltrim($instagram, '@') }}" target="_blank" class="flex items-center gap-1 hover:text-brand transition-colors"><i class="fa-brands fa-instagram"></i> {{ '@' . ltrim($instagram, '@') }}</a>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Action Buttons (Visible only on screen) -->
    <div class="fixed bottom-8 right-8 flex flex-col gap-3 no-print z-50">
        <button onclick="window.print()" class="group relative bg-brand hover:bg-brand-dark text-white font-medium w-12 h-12 rounded-full shadow-lg transition flex items-center justify-center hover:scale-105" title="Cetak / Simpan PDF">
            <i class="fa-solid fa-print text-lg"></i>
            <span class="absolute right-14 bg-neutral-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Cetak / Simpan PDF</span>
        </button>
    </div>

    <script>
        function copyAccount(btn) {
            const acc = (btn.getAttribute('data-account') || '').replace(/\s/g, '');
            navigator.clipboard.writeText(acc).then(() => {
                const icon = btn.querySelector('i');
                const prev = icon.className;
                icon.className = 'fa-solid fa-check';
                setTimeout(() => { icon.className = prev; }, 1500);
            });
        }
    </script>

</body>
</html>
