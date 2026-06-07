@extends('layouts.app')

@section('title', 'Buat Paket Suka-Suka – Wonderful Toba')
@section('description', 'Racik sendiri paket wisata Danau Toba impian Anda. Pilih paket dasar, tambahkan layanan ekstra, dan dapatkan estimasi harga seketika.')

@section('content')
<div
    x-data="{
        packages: {{ Illuminate\Support\Js::from($packages) }},
        addons: {{ Illuminate\Support\Js::from($addons) }},
        waNumber: '{{ $waNumber }}',
        selectedId: {{ $packages[0]['id'] ?? 'null' }},
        pax: 2,
        chosen: {},
        get pkg() { return this.packages.find(p => String(p.id) === String(this.selectedId)) || null; },
        get baseSubtotal() { return this.pkg ? this.pkg.price * Math.max(1, this.pax) : 0; },
        get chosenAddons() { return this.addons.filter(a => this.chosen[a.id]); },
        addonCost(a) { return a.price * (a.per === 'pax' ? Math.max(1, this.pax) : 1); },
        get addonsSubtotal() { return this.chosenAddons.reduce((t, a) => t + this.addonCost(a), 0); },
        get grandTotal() { return this.baseSubtotal + this.addonsSubtotal; },
        fmt(n) { return new Intl.NumberFormat('id-ID').format(Math.round(n || 0)); },
        get waUrl() {
            if (!this.pkg) return '#';
            let m = 'Halo Wonderful Toba, saya ingin memesan *Paket Suka-Suka* hasil racikan saya:%0A%0A';
            m += '*Paket Dasar:* ' + this.pkg.name + '%0A';
            m += '*Durasi:* ' + (this.pkg.duration || '-') + '%0A';
            m += '*Jumlah Peserta:* ' + this.pax + ' orang%0A';
            m += '*Subtotal Paket:* Rp ' + this.fmt(this.baseSubtotal) + '%0A%0A';
            if (this.chosenAddons.length) {
                m += '*Add-on Tambahan:*%0A';
                this.chosenAddons.forEach(a => {
                    m += '- ' + a.name + ' (Rp ' + this.fmt(this.addonCost(a)) + ')%0A';
                });
                m += '*Subtotal Add-on:* Rp ' + this.fmt(this.addonsSubtotal) + '%0A%0A';
            }
            m += '*ESTIMASI TOTAL: Rp ' + this.fmt(this.grandTotal) + '*%0A%0A';
            m += 'Mohon konfirmasi ketersediaan & harga finalnya. Terima kasih!';
            return 'https://wa.me/' + this.waNumber + '?text=' + m;
        }
    }"
    class="bg-slate-50 min-h-screen"
>
    <!-- Hero -->
    <div class="relative bg-slate-900 pt-32 pb-24 overflow-hidden">
        <img src="{{ asset('images/home/tour.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-25" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-slate-900/70 to-slate-900"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
            <span class="inline-block px-4 py-1.5 bg-toba-green text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-6">Custom Trip Builder</span>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-tight mb-5">Buat Paket <span class="text-toba-accent">Suka-Suka</span></h1>
            <p class="text-white/70 font-medium max-w-2xl mx-auto text-base md:text-lg">Racik sendiri perjalanan impian Anda. Pilih paket dasar, tambahkan layanan ekstra, dan lihat estimasi harga berubah seketika.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-12 relative z-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            <!-- Left: Builder -->
            <div class="lg:col-span-8 space-y-10">

                <!-- Step 1: Base Package -->
                <div class="bg-white rounded-3xl p-7 md:p-10 shadow-card border border-slate-200/70">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-toba-green text-white flex items-center justify-center font-black shrink-0">1</div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight">Pilih Paket Dasar</h2>
                            <p class="text-sm text-slate-500 font-medium">Titik awal perjalanan Anda</p>
                        </div>
                    </div>

                    <template x-if="packages.length === 0">
                        <p class="text-slate-400 font-medium text-sm bg-slate-50 rounded-2xl p-6 text-center">Belum ada paket tersedia. Silakan hubungi kami via WhatsApp.</p>
                    </template>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <template x-for="p in packages" :key="p.id">
                            <button type="button" @click="selectedId = p.id"
                                :class="String(selectedId) === String(p.id) ? 'border-toba-green ring-2 ring-toba-green/20 bg-toba-green/5' : 'border-slate-100 hover:border-slate-200 bg-white'"
                                class="text-left rounded-2xl border-2 p-3 flex gap-4 items-center transition-all">
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-200 shrink-0">
                                    <img :src="p.image" :alt="p.name" class="w-full h-full object-cover" loading="lazy" onerror="this.src='{{ asset('images/home/tour.webp') }}'">
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <p class="font-black text-slate-900 text-sm leading-tight line-clamp-2" x-text="p.name"></p>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1" x-text="p.duration"></p>
                                    <p class="text-toba-green font-black text-sm mt-1"><span class="text-[10px]">Rp</span> <span x-text="fmt(p.price)"></span><span class="text-[10px] text-slate-400 font-bold">/pax</span></p>
                                </div>
                                <div class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-white"
                                     :class="String(selectedId) === String(p.id) ? 'bg-toba-green' : 'bg-slate-200'">
                                    <i class="fas fa-check text-[10px]"></i>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Step 2: Peserta -->
                <div class="bg-white rounded-3xl p-7 md:p-10 shadow-card border border-slate-200/70">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-toba-green text-white flex items-center justify-center font-black shrink-0">2</div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight">Jumlah Peserta</h2>
                            <p class="text-sm text-slate-500 font-medium">Harga paket & sebagian add-on dihitung per orang</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5">
                        <button type="button" @click="pax = Math.max(1, pax - 1)" class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-700 font-black text-2xl hover:bg-slate-900 hover:text-white transition" aria-label="Kurangi">−</button>
                        <input type="number" min="1" x-model.number="pax" class="w-24 h-14 text-center bg-slate-50 border-2 border-slate-100 focus:border-toba-green rounded-2xl font-black text-2xl text-slate-900 outline-none">
                        <button type="button" @click="pax = pax + 1" class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-700 font-black text-2xl hover:bg-slate-900 hover:text-white transition" aria-label="Tambah">+</button>
                        <span class="text-sm font-bold text-slate-400">orang</span>
                    </div>
                </div>

                <!-- Step 3: Add-ons -->
                <div class="bg-white rounded-3xl p-7 md:p-10 shadow-card border border-slate-200/70">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-toba-green text-white flex items-center justify-center font-black shrink-0">3</div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight">Tambah Layanan Ekstra</h2>
                            <p class="text-sm text-slate-500 font-medium">Ceklis sesuai kebutuhan, opsional</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <template x-for="a in addons" :key="a.id">
                            <label :class="chosen[a.id] ? 'border-toba-green bg-toba-green/5' : 'border-slate-100 hover:border-slate-200'"
                                   class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all">
                                <input type="checkbox" x-model="chosen[a.id]" class="sr-only">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition"
                                     :class="chosen[a.id] ? 'bg-toba-green text-white' : 'bg-slate-100 text-slate-400'">
                                    <i class="fas" :class="a.icon || 'fa-plus'"></i>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="font-black text-slate-900 text-sm" x-text="a.name"></p>
                                    <p class="text-xs text-slate-500 font-medium" x-text="a.desc"></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-black text-slate-900 text-sm">+ Rp <span x-text="fmt(a.price)"></span></p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest" x-text="a.per === 'pax' ? 'per orang' : 'per trip'"></p>
                                </div>
                                <div class="shrink-0 w-6 h-6 rounded-md flex items-center justify-center text-white border-2 transition"
                                     :class="chosen[a.id] ? 'bg-toba-green border-toba-green' : 'border-slate-200'">
                                    <i x-show="chosen[a.id]" class="fas fa-check text-[10px]"></i>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right: Summary (sticky) -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 bg-slate-900 rounded-3xl p-8 text-white shadow-premium">
                    <span class="text-toba-accent font-black text-[10px] uppercase tracking-[0.3em] mb-6 block">Ringkasan Racikan</span>

                    <template x-if="pkg">
                        <div>
                            <div class="flex justify-between items-start gap-3 pb-5 border-b border-white/10">
                                <div class="min-w-0">
                                    <p class="font-black text-white text-sm leading-tight" x-text="pkg.name"></p>
                                    <p class="text-[10px] text-white/40 font-black uppercase tracking-widest mt-1">
                                        <span x-text="fmt(pkg.price)"></span> × <span x-text="pax"></span> pax
                                    </p>
                                </div>
                                <p class="font-black text-sm whitespace-nowrap">Rp <span x-text="fmt(baseSubtotal)"></span></p>
                            </div>

                            <div class="py-5 space-y-3 border-b border-white/10" x-show="chosenAddons.length">
                                <template x-for="a in chosenAddons" :key="a.id">
                                    <div class="flex justify-between items-center gap-3 text-sm">
                                        <span class="text-white/70 font-medium min-w-0 truncate" x-text="a.name"></span>
                                        <span class="font-black whitespace-nowrap">Rp <span x-text="fmt(addonCost(a))"></span></span>
                                    </div>
                                </template>
                            </div>

                            <div class="pt-6 mb-8">
                                <p class="text-[10px] text-white/40 font-black uppercase tracking-widest mb-1">Estimasi Total</p>
                                <p class="text-4xl font-black tracking-tighter"><span class="text-toba-accent text-xl mr-1">Rp</span><span x-text="fmt(grandTotal)"></span></p>
                                <p class="text-[10px] text-white/40 font-medium mt-2 leading-relaxed">*Estimasi. Harga final dikonfirmasi tim kami sesuai ketersediaan & musim.</p>
                            </div>

                            <a :href="waUrl" target="_blank" rel="noopener"
                               class="w-full py-5 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl shadow-emerald-500/20 flex items-center justify-center gap-3">
                                <i class="fab fa-whatsapp text-2xl"></i>
                                Booking via WhatsApp
                            </a>
                            <p class="text-center text-[9px] font-black uppercase tracking-widest text-white/30 mt-4">Rincian racikan terkirim otomatis</p>
                        </div>
                    </template>

                    <template x-if="!pkg">
                        <p class="text-white/50 font-medium text-sm py-8 text-center">Pilih paket dasar untuk mulai menghitung.</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
