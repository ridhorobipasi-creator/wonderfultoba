@extends('layouts.app')

@section('title', ($post->title ?? 'Blog') . ' – Wonderful Toba')
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
      "name": "Wonderful Toba"
    }]
}
</script>
@endpush

@section('content')
<div class="bg-white min-h-screen pb-32">
    <!-- Immersive Cinematic Hero -->
    <div class="relative h-[70dvh] w-full overflow-hidden bg-slate-900">
        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60 animate-subtle-zoom">
        
        <!-- Overlays -->
        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-slate-900/40"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 via-transparent to-transparent"></div>

        <!-- Hero Content -->
        <div class="relative z-10 h-full max-w-5xl mx-auto px-6 md:px-8 flex flex-col justify-center text-center md:text-left">
            <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000">
                <a href="/tour/blog" class="inline-flex items-center gap-3 text-white/80 hover:text-white font-black text-[10px] uppercase tracking-[0.4em] mb-10 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 group-hover:bg-toba-green group-hover:border-toba-green transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </div>
                    Kembali ke Jurnal
                </a>
                
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-8">
                    <span class="px-5 py-2 bg-toba-green text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-xl shadow-toba-green/20">
                        {{ $post->category }}
                    </span>
                    <span class="px-5 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full text-[10px] font-black uppercase tracking-widest">
                        {{ date('d F Y', strtotime($post->createdAt)) }}
                    </span>
                </div>

                <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter leading-[1.05] drop-shadow-2xl" x-text="package.name">{{ $post->title }}</h1>
            </div>
        </div>
    </div>

    <!-- Reading Area -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 -mt-20 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            
            <!-- Main Content: Article Body -->
            <div class="lg:col-span-8">
                <article class="bg-white rounded-[3rem] p-10 md:p-20 shadow-[0_50px_100px_-20px_rgba(15,23,42,0.08)] border border-slate-50">
                    <!-- Intro Meta -->
                    <div class="flex items-center gap-4 mb-16 pb-10 border-b border-slate-50">
                        <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-2xl shadow-xl">W</div>
                        <div>
                            <p class="text-[10px] font-black text-toba-green uppercase tracking-[0.3em] mb-1">Ditulis Oleh</p>
                            <p class="text-lg font-black text-slate-900 tracking-tight">Tim Redaksi Wonderful Toba</p>
                        </div>
                    </div>

                    <!-- Article Body -->
                    <div class="prose prose-xl prose-slate max-w-none text-slate-600 font-medium leading-[1.8] tracking-normal">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Tags -->
                    @if(isset($post->tags) && count($post->tags) > 0)
                        <div class="mt-20 pt-10 border-t border-slate-50 flex flex-wrap gap-3">
                            @foreach($post->tags as $tag)
                                <span class="px-6 py-2 bg-slate-50 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-widest border border-slate-100 hover:bg-toba-green hover:text-white transition-all cursor-default">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Premium Share Bar -->
                    <div class="mt-16 p-10 bg-slate-50 rounded-[2.5rem] border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div>
                            <h4 class="text-xl font-black text-slate-900 tracking-tight mb-1">Bagikan Inspirasi Ini</h4>
                            <p class="text-sm text-slate-500 font-medium">Bantu orang lain menemukan petualangan impian mereka.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="https://wa.me/?text={{ urlencode('Baca artikel ini dari Wonderful Toba: ' . $post->title . ' ' . url()->current()) }}" 
                               target="_blank"
                               class="w-14 h-14 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-xl shadow-emerald-200 hover:scale-110 transition-all">
                                <i class="fab fa-whatsapp text-2xl"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                               target="_blank"
                               class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-xl shadow-blue-200 hover:scale-110 transition-all">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            <button @click="navigator.clipboard.writeText(window.location.href); alert('Link disalin!')"
                               class="w-14 h-14 bg-white text-slate-400 border border-slate-100 rounded-2xl flex items-center justify-center shadow-xl hover:scale-110 transition-all">
                                <i class="fas fa-link text-xl"></i>
                            </button>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 space-y-8 animate-in fade-in slide-in-from-right-12 duration-1000">
                    
                    <!-- Sidebar CTA -->
                    <div class="bg-slate-900 rounded-[3.5rem] p-10 md:p-12 text-white shadow-[0_50px_100px_-20px_rgba(15,23,42,0.4)] relative overflow-hidden">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-toba-green/20 rounded-full blur-[80px]"></div>
                        <div class="relative z-10">
                            <span class="text-toba-accent font-black text-[11px] uppercase tracking-[0.4em] mb-6 block">Eksplorasi Sekarang</span>
                            <h3 class="text-3xl font-black text-white mb-6 tracking-tighter leading-tight">Siap Untuk <br/>Menjelajah?</h3>
                            <p class="text-slate-400 text-sm font-medium mb-10 leading-relaxed">
                                Wujudkan cerita petualangan Anda sendiri. Pilih paket wisata yang paling sesuai dengan jiwa petualang Anda.
                            </p>
                            <a href="/tour/packages" class="w-full flex items-center justify-center gap-3 py-6 bg-toba-green text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] hover:bg-toba-accent hover:text-slate-900 transition-all duration-500 shadow-2xl shadow-toba-green/20 group">
                                Lihat Paket Wisata
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Social proof box -->
                    <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-xl shadow-slate-200/50">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Penulis Resmi</p>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-toba-green/10 text-toba-green flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <div>
                                <p class="font-black text-slate-900">Wonderful Toba</p>
                                <p class="text-[9px] font-black text-toba-green uppercase tracking-widest">Editorial Team</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">
                            Kami berdedikasi untuk memberikan panduan perjalanan paling akurat dan inspiratif di Sumatera Utara.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Posts (Premium Grid) -->
        @if(count($relatedPosts) > 0)
            <div class="mt-32">
                <div class="flex items-center space-x-3 mb-12">
                    <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                    <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">Inspirasi Lainnya</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    @foreach($relatedPosts as $rp)
                        <article class="group">
                            <a href="/tour/blog/{{ $rp->slug ?? $rp->id }}" class="block">
                                <div class="relative h-64 rounded-[2.5rem] overflow-hidden mb-6 shadow-lg">
                                    <img src="{{ $rp->image }}" alt="{{ $rp->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                                </div>
                                <div class="px-2">
                                    <span class="text-[10px] font-black text-toba-green uppercase tracking-[0.3em] mb-3 block">{{ $rp->category }}</span>
                                    <h4 class="text-xl font-black text-slate-900 mb-4 group-hover:text-toba-green transition-colors leading-tight tracking-tight line-clamp-2">{{ $rp->title }}</h4>
                                    <div class="flex items-center gap-2 text-slate-900 font-black text-[9px] uppercase tracking-widest group-hover:gap-4 transition-all">
                                        Selanjutnya
                                        <svg class="w-4 h-4 text-toba-green" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
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
        to { transform: scale(1.1); }
    }
    .animate-subtle-zoom {
        animation: subtle-zoom 20s infinite alternate ease-in-out;
    }
</style>
@endsection
