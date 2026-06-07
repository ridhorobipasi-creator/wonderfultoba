@props([
    'src' => null,
    'alt' => 'Image',
    'fallback' => null,
    'class' => '',
    'loading' => 'lazy',
    'decoding' => 'async',
    'placeholder' => null,
])

@php
    $localFallback = $fallback ?? asset('images/home/tour.webp');
    $finalSrc = $localFallback;

    if ($src && $src !== 'null') {
        if (Str::startsWith($src, ['http', '//', 'data:', 'blob:'])) {
            $finalSrc = $src;
        } else {
            $cleanPath = ltrim($src, '/');
            $matched = false;
            foreach (['assets/', 'images/', 'branding/', 'gallery/'] as $prefix) {
                if (Str::startsWith($cleanPath, $prefix)) {
                    $finalSrc = asset('storage/' . $cleanPath);
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $cleanPath = preg_replace('/^storage\//', '', $cleanPath);
                $finalSrc = asset('storage/' . $cleanPath);
            }
        }
    }

    // Blur-up: LQIP dipasang sebagai background; gambar asli fade-in saat selesai dimuat.
    $bgStyle = $placeholder
        ? "background-image:url('{$placeholder}');background-size:cover;background-position:center;background-repeat:no-repeat;"
        : '';
@endphp

<img
    src="{{ $finalSrc }}"
    alt="{{ $alt }}"
    loading="{{ $loading }}"
    decoding="{{ $decoding }}"
    @if($placeholder)
        style="{{ $bgStyle }}"
        onerror="this.onerror=null;this.src='{{ $localFallback }}';this.style.backgroundImage='none'"
    @else
        onerror="this.src='{{ $localFallback }}'"
    @endif
    {{ $attributes->merge(['class' => $class]) }}
>
