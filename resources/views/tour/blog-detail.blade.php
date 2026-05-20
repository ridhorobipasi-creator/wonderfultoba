@extends('layouts.app')

@section('title', ($post->title ?? 'Blog') . ' – Sujai Laketoba')
@section('description', \Illuminate\Support\Str::limit($post->content ?? '', 160))

@section('og_image', $post->image ?: asset('images/og-default.webp'))

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "image": [
    "{{ $post->image }}"
  ],
  "datePublished": "{{ date('c', strtotime($post->createdAt)) }}",
  "author": [{
      "@type": "Organization",
      "name": "Sujai Laketoba"
    }]
}
</script>
@endpush

@section('content')
<div class="bg-slate-50 min-h-screen pb-32">
    <!-- Immersive Cinematic Hero -->
    <div class="relative h-[55dvh] w-full overflow-hidden bg-slate-900">
        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60 animate-subtle-zoom">
        
        <!-- Overlays -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-slate-900/40"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 via-transparent to-transparent"></div>

        <!-- Hero Content -->
        <div class="relative z-10 h-full max-w-5xl mx-auto px-6 md:px-8 flex flex-col justify-center text-center md:text-left pt-16">
            <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000">
                <a href="/tour/blog" class="inline-flex items-center gap-2 text-white/80 hover:text-white font-bold text-xs uppercase tracking-wider mb-8 transition-all group">
                    <div class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 group-hover:bg-toba-green group-hover:border-toba-green transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </div>
                    {{ __('Kembali ke Jurnal') }}
                </a>
                
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-6">
                    <span class="px-3.5 py-1.5 bg-toba-green text-white rounded-lg text-[10px] font-semibold uppercase tracking-wider shadow-sm">
                        {{ $post->category }}
                    </span>
                    <span class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-lg text-[10px] font-semibold uppercase tracking-wider">
                        {{ \Carbon\Carbon::parse($post->createdAt)->locale(session('locale', 'my') === 'en' ? 'en' : (session('locale', 'my') === 'my' ? 'ms' : 'id'))->translatedFormat('d F Y') }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-5xl font-light text-white tracking-tight leading-tight drop-shadow-sm">{{ $post->title }}</h1>
            </div>
        </div>
    </div>

    <!-- Reading Area -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 -mt-16 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Main Content: Article Body -->
            <div class="lg:col-span-8">
                <article class="bg-white rounded-3xl p-8 md:p-14 shadow-sm border border-slate-100">
                    <!-- Intro Meta -->
                    <div class="flex items-center gap-4 mb-10 pb-8 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg">W</div>
                        <div>
                            <p class="text-[9px] font-semibold text-toba-green uppercase tracking-wider mb-0.5">{{ __('Ditulis Oleh') }}</p>
                            <p class="text-base font-bold text-slate-900 tracking-tight">{{ __('Tim Redaksi Sujai Laketoba') }}</p>
                        </div>
                    </div>

                    <div class="prose prose-lg prose-slate max-w-none text-slate-600 font-normal leading-relaxed">
                        @if(!empty($post->content) && strlen($post->content) > 10)
                            {!! nl2br($post->content) !!}
                        @else
                            <p class="text-slate-400 italic">{{ $post->excerpt }}</p>
                        @endif
                    </div>

                    <!-- Tags -->
                    @if(isset($post->tags) && count($post->tags) > 0)
                        <div class="mt-12 pt-8 border-t border-slate-100 flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="px-4 py-1.5 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-semibold uppercase tracking-wider border border-slate-100 hover:bg-toba-green hover:text-white transition-all cursor-default">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Premium Share Bar -->
                    <div class="mt-12 p-6 bg-slate-50 border border-slate-100 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <h4 class="text-base font-bold text-slate-900 tracking-tight mb-1">{{ __('Bagikan Inspirasi Ini') }}</h4>
                            <p class="text-xs text-slate-500 font-normal">{{ __('Bantu orang lain menemukan petualangan impian mereka.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://wa.me/?text={{ urlencode(__('Baca artikel ini dari Sujai Laketoba: ') . $post->title . ' ' . url()->current()) }}" 
                               target="_blank"
                               class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-sm hover:scale-105 transition-all">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                               target="_blank"
                               class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-sm hover:scale-105 transition-all">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <button @click="navigator.clipboard.writeText(window.location.href); alert('{{ __('Link disalin!') }}')"
                               class="w-10 h-10 bg-white text-slate-400 border border-slate-200 rounded-xl flex items-center justify-center shadow-sm hover:scale-105 transition-all">
                                <i class="fas fa-link text-sm"></i>
                            </button>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 space-y-8 animate-in fade-in slide-in-from-right-12 duration-1000">
                    
                    <!-- Sidebar CTA -->
                    <div class="bg-slate-950 rounded-3xl p-8 text-white shadow-sm border border-slate-900 relative overflow-hidden">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-toba-green/10 rounded-full blur-[80px]"></div>
                        <div class="relative z-10">
                            <span class="text-toba-accent font-semibold text-[10px] uppercase tracking-wider mb-4 block">{{ __('Eksplorasi Sekarang') }}</span>
                            <h3 class="text-2xl font-light text-white mb-4 tracking-tight leading-tight">{!! __('Siap Untuk <br/>Menjelajah?') !!}</h3>
                            <p class="text-slate-400 text-xs font-normal mb-8 leading-relaxed">
                                {{ __('Wujudkan cerita petualangan Anda sendiri. Pilih paket wisata yang paling sesuai dengan jiwa petualang Anda.') }}
                            </p>
                            <a href="/tour/packages" class="w-full flex items-center justify-center gap-2 py-3.5 bg-toba-green text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-toba-accent hover:text-slate-900 transition-all duration-300 shadow-sm group">
                                {{ __('Lihat Paket Wisata') }}
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Social proof box -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                        <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mb-4">{{ __('Penulis Resmi') }}</p>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-toba-green/10 text-toba-green flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm">Sujai Laketoba</p>
                                <p class="text-[9px] font-semibold text-toba-green uppercase tracking-wider">{{ __('Editorial Team') }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 font-normal leading-relaxed">
                            {{ __('Kami berdedikasi untuk memberikan panduan perjalanan paling akurat dan inspiratif di Sumatera Utara.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Posts (Premium Grid) -->
        @if(count($relatedPosts) > 0)
            <div class="mt-24">
                <div class="flex items-center space-x-2 mb-8">
                    <span class="text-toba-green font-bold text-xs uppercase tracking-wider">
                        {{ __('Inspirasi Lainnya') }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $rp)
                        <article class="group">
                            <a href="/tour/blog/{{ $rp->slug ?? $rp->id }}" class="block">
                                <div class="relative h-60 rounded-3xl overflow-hidden mb-4 border border-slate-100 shadow-sm">
                                    <img src="{{ $rp->image }}" alt="{{ $rp->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[1.5s]">
                                    <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                                </div>
                                <div class="px-1">
                                    <span class="text-[9px] font-semibold text-toba-green uppercase tracking-wider mb-1.5 block">{{ $rp->category }}</span>
                                    <h4 class="text-base font-bold text-slate-900 mb-3 group-hover:text-toba-green transition-colors leading-tight tracking-tight line-clamp-2">{{ $rp->title }}</h4>
                                    <div class="flex items-center gap-1.5 text-slate-900 font-bold text-xs group-hover:text-toba-green transition-all">
                                        {{ __('Selanjutnya') }}
                                        <svg class="w-3.5 h-3.5 text-toba-green" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes subtle-zoom {
        from { transform: scale(1); }
        to { transform: scale(1.05); }
    }
    .animate-subtle-zoom {
        animation: subtle-zoom 20s infinite alternate ease-in-out;
    }
</style>
@endsection
