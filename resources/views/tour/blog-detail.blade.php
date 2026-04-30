@extends('layouts.app')

@section('title', ($post->title ?? 'Blog') . ' – Wonderful Toba')
@section('description', \Illuminate\Support\Str::limit($post->content ?? '', 160))

@section('content')
<div class="bg-slate-50 min-h-screen pb-24 pt-24">
    <!-- Hero Image -->
    <div class="relative h-[55vh] overflow-hidden">
        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/20 via-slate-900/40 to-slate-900/90"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 max-w-4xl mx-auto">
            <div class="flex items-center gap-3 mb-4">
                <span class="inline-block px-4 py-1.5 bg-toba-green text-white text-xs font-black uppercase tracking-widest rounded-full shadow-lg">
                    {{ $post->category }}
                </span>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-white leading-tight drop-shadow-lg">{{ $post->title }}</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 mt-8">
        <!-- Back -->
        <a href="/tour/blog" class="inline-flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-8 transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Blog
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-8 items-start">
            <!-- Main Article -->
            <article class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-slate-100">
                <!-- Meta -->
                <div class="flex flex-wrap items-center gap-4 text-slate-400 text-sm mb-8 pb-8 border-b border-slate-100">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ date('d F Y', strtotime($post->createdAt)) }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Tim Wonderful Toba
                    </span>
                    <div class="flex gap-2 flex-wrap">
                        @foreach(($post->tags ?? []) as $tag)
                            <span class="flex items-center gap-1 text-xs font-bold text-toba-green bg-toba-green/10 px-3 py-1 rounded-full">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Content -->
                <div class="prose prose-slate max-w-none text-slate-600 font-medium leading-relaxed text-lg">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Share Bar -->
                <div class="mt-12 pt-8 border-t border-slate-100">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <p class="font-black text-slate-900">Bagikan artikel ini:</p>
                        <div class="flex items-center gap-3">
                            <a 
                                href="https://wa.me/?text={{ urlencode('Baca artikel ini dari Wonderful Toba: ' . $post->title . ' ' . url()->current()) }}"
                                target="_blank"
                                class="flex items-center gap-2 bg-[#25D366] text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-[#1da851] transition-colors shadow-lg shadow-[#25D366]/20"
                            >
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <div class="lg:sticky lg:top-28 space-y-6">
                <!-- Author Box -->
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-toba-green to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-xl">W</div>
                        <div>
                            <p class="font-black text-slate-900">Tim Wonderful Toba</p>
                            <p class="text-xs font-bold text-toba-green uppercase tracking-wider">Official Writer</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">
                        Kami berbagi tips, panduan, dan inspirasi wisata terbaik di Sumatera Utara.
                    </p>
                </div>

                <!-- CTA Box -->
                <div class="bg-slate-900 rounded-[2rem] p-6 shadow-sm text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-toba-green/20 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                    <h3 class="font-black text-lg mb-2 relative z-10">Siap Berwisata?</h3>
                    <p class="text-slate-400 text-sm mb-5 relative z-10">
                        Temukan paket perjalanan terbaik ke destinasi impian Anda.
                    </p>
                    <a
                        href="https://wa.me/6281323888207"
                        target="_blank"
                        class="block w-full py-3 bg-toba-green text-white font-black text-sm rounded-2xl text-center hover:bg-toba-green/90 transition-colors relative z-10 shadow-lg shadow-toba-green/20"
                    >
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Posts -->
        @if(count($relatedPosts) > 0)
            <div class="mt-16">
                <h3 class="text-2xl font-black text-slate-900 mb-8">Artikel Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $rp)
                        <a href="/tour/blog/{{ $rp->slug ?? $rp->id }}" class="bg-white rounded-[1.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                            <div class="h-44 overflow-hidden">
                                <img src="{{ $rp->image }}" alt="{{ $rp->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-5">
                                <span class="text-[10px] font-black text-toba-green uppercase tracking-wider bg-toba-green/10 px-2.5 py-1 rounded-full">{{ $rp->category }}</span>
                                <h4 class="font-black text-slate-900 text-sm mt-3 line-clamp-2 group-hover:text-toba-green transition-colors leading-snug">{{ $rp->title }}</h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
