@props([
    'src' => null, 
    'alt' => 'Image', 
    'fallback' => null, 
    'class' => '',
    'loading' => 'lazy',
    'decoding' => 'async'
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
@endphp

<img 
    src="{{ $finalSrc }}" 
    alt="{{ $alt }}" 
    loading="{{ $loading }}" 
    decoding="{{ $decoding }}" 
    {{ $attributes->merge(['class' => $class]) }} 
    onerror="this.src='{{ $localFallback }}'"
>
