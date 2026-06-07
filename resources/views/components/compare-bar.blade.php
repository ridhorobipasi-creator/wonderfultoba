{{-- Smart Comparison: floating bar + modal. Data dari Alpine.store('compare') (localStorage). --}}
<div x-data="{
        get s() { return $store.compare; },
        fullToast: false,
        fmt(n) { return n ? new Intl.NumberFormat('id-ID').format(n) : '-'; },
        fallback: '{{ asset('images/home/tour.webp') }}',
     }"
     x-on:compare-full.window="fullToast = true; setTimeout(() => fullToast = false, 3500)">

    {{-- Toast: maksimum tercapai --}}
    <div x-cloak x-show="fullToast"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed top-24 left-1/2 -translate-x-1/2 z-[130] bg-slate-900 text-white px-6 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-bold">
        <i class="fas fa-circle-info text-toba-accent"></i>
        Maksimal <span x-text="s.max"></span> paket untuk dibandingkan.
    </div>

    {{-- Floating compare bar --}}
    <div x-cloak x-show="s.count > 0"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[95] w-[92%] max-w-2xl">
        <div class="bg-white/95 backdrop-blur-2xl border border-slate-200/70 rounded-3xl shadow-premium px-4 py-3 flex items-center gap-3">
            <div class="flex -space-x-3 shrink-0">
                <template x-for="item in s.items" :key="item.id">
                    <div class="relative w-12 h-12 rounded-2xl overflow-hidden ring-2 ring-white shadow-md bg-slate-200">
                        <img :src="item.image || fallback" :alt="item.name" class="w-full h-full object-cover" :onerror="`this.src='${fallback}'`">
                        <button @click="s.remove(item.id)" class="absolute -top-1 -right-1 w-4 h-4 bg-slate-900 text-white rounded-full text-[8px] flex items-center justify-center hover:bg-rose-500 transition" aria-label="Hapus dari perbandingan">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </template>
                <template x-for="i in Math.max(0, s.max - s.count)" :key="'ph'+i">
                    <div class="w-12 h-12 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center text-slate-300">
                        <i class="fas fa-plus text-xs"></i>
                    </div>
                </template>
            </div>
            <div class="flex-grow min-w-0 hidden sm:block">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bandingkan Paket</p>
                <p class="text-sm font-black text-slate-900"><span x-text="s.count"></span> dari <span x-text="s.max"></span> dipilih</p>
            </div>
            <button @click="s.clear()" class="shrink-0 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition px-2">Reset</button>
            <button @click="s.open = true" :disabled="s.count < 2"
                    class="shrink-0 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all flex items-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed"
                    :class="s.count >= 2 ? 'bg-toba-green text-white hover:bg-slate-900 shadow-lg shadow-toba-green/20' : 'bg-slate-100 text-slate-400'">
                <i class="fas fa-table-columns"></i>
                Bandingkan
            </button>
        </div>
    </div>

    {{-- Modal perbandingan side-by-side --}}
    <div x-cloak x-show="s.open" class="fixed inset-0 z-[125] flex items-end md:items-center justify-center p-0 md:p-6"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="s.open = false"></div>
        <div class="relative bg-white w-full md:max-w-5xl max-h-[92vh] md:max-h-[88vh] rounded-t-3xl md:rounded-3xl shadow-premium overflow-hidden flex flex-col"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-12 md:scale-95 opacity-0" x-transition:enter-end="translate-y-0 md:scale-100 opacity-100">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 md:px-8 py-5 border-b border-slate-100 shrink-0">
                <div>
                    <p class="text-[10px] font-black text-toba-green uppercase tracking-[0.3em]">Smart Comparison</p>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">Perbandingan Paket</h3>
                </div>
                <button @click="s.open = false" class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-900 hover:text-white transition" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Body: scrollable, kolom per paket --}}
            <div class="overflow-auto p-5 md:p-7">
                <div class="grid gap-4" :style="`grid-template-columns: 130px repeat(${s.count}, minmax(180px, 1fr));`">
                    {{-- Baris: Foto + Nama --}}
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest self-end pb-2">Paket</div>
                    <template x-for="item in s.items" :key="'h'+item.id">
                        <div class="text-center">
                            <div class="relative h-28 rounded-2xl overflow-hidden bg-slate-200 mb-3">
                                <img :src="item.image || fallback" :alt="item.name" class="w-full h-full object-cover" :onerror="`this.src='${fallback}'`">
                            </div>
                            <p class="font-black text-slate-900 text-sm leading-tight" x-text="item.name"></p>
                        </div>
                    </template>

                    {{-- Lokasi --}}
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest py-3 border-t border-slate-100">Lokasi</div>
                    <template x-for="item in s.items" :key="'loc'+item.id">
                        <div class="text-center py-3 border-t border-slate-100 text-sm font-bold text-slate-700" x-text="item.location || 'Sumatera Utara'"></div>
                    </template>

                    {{-- Durasi --}}
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest py-3 border-t border-slate-100">Durasi</div>
                    <template x-for="item in s.items" :key="'dur'+item.id">
                        <div class="text-center py-3 border-t border-slate-100 text-sm font-bold text-slate-700" x-text="item.duration || '-'"></div>
                    </template>

                    {{-- Harga --}}
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest py-3 border-t border-slate-100">Harga / Pax</div>
                    <template x-for="item in s.items" :key="'pr'+item.id">
                        <div class="text-center py-3 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-400">Rp</span>
                            <span class="text-lg font-black text-toba-green" x-text="fmt(item.price)"></span>
                        </div>
                    </template>

                    {{-- Termasuk --}}
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest py-3 border-t border-slate-100">Termasuk</div>
                    <template x-for="item in s.items" :key="'inc'+item.id">
                        <div class="py-3 border-t border-slate-100">
                            <template x-if="item.includes && item.includes.length">
                                <ul class="space-y-1.5">
                                    <template x-for="(inc, k) in item.includes" :key="k">
                                        <li class="flex items-start gap-2 text-xs text-slate-600 font-medium">
                                            <i class="fas fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                                            <span x-text="inc"></span>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!item.includes || !item.includes.length">
                                <p class="text-xs text-slate-300 text-center">—</p>
                            </template>
                        </div>
                    </template>

                    {{-- Tidak termasuk --}}
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest py-3 border-t border-slate-100">Tidak Termasuk</div>
                    <template x-for="item in s.items" :key="'exc'+item.id">
                        <div class="py-3 border-t border-slate-100">
                            <template x-if="item.excludes && item.excludes.length">
                                <ul class="space-y-1.5">
                                    <template x-for="(exc, k) in item.excludes" :key="k">
                                        <li class="flex items-start gap-2 text-xs text-slate-400 font-medium">
                                            <i class="fas fa-times text-slate-300 mt-0.5 text-[10px]"></i>
                                            <span x-text="exc"></span>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!item.excludes || !item.excludes.length">
                                <p class="text-xs text-slate-300 text-center">—</p>
                            </template>
                        </div>
                    </template>

                    {{-- CTA --}}
                    <div class="py-3 border-t border-slate-100"></div>
                    <template x-for="item in s.items" :key="'cta'+item.id">
                        <div class="py-3 border-t border-slate-100 text-center">
                            <a :href="'/tour/package/' + (item.slug || item.id)"
                               class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-toba-green transition">
                                Lihat Detail
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
