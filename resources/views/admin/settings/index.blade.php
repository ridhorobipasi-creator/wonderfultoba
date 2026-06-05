@extends('admin.layout')

@section('title', 'Pengaturan Umum')
@section('page-title', 'Pengaturan Umum')

@section('content')
<div x-data="{ activeTab: 'branding', exchangeRateType: '{{ $general['finance']['exchange_rate_type'] ?? 'manual' }}' }" class="space-y-8">

    <div class="bg-white p-2 rounded-[2rem] shadow-sm border border-slate-100 inline-flex items-center space-x-1">
        <button @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-8 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all">
            <i class="fas fa-palette mr-2"></i> Branding
        </button>
        <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-8 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all">
            <i class="fas fa-address-book mr-2"></i> Kontak & WA
        </button>
        <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-8 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all">
            <i class="fas fa-search mr-2"></i> SEO Global
        </button>
        <button @click="activeTab = 'company'" :class="activeTab === 'company' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-8 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all">
            <i class="fas fa-building mr-2"></i> Perusahaan & Invoice
        </button>
        <button @click="activeTab = 'finance'" :class="activeTab === 'finance' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-8 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all">
            <i class="fas fa-coins mr-2"></i> Keuangan & Pajak
        </button>
    </div>

    <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Branding Tab -->
        <div x-show="activeTab === 'branding'" x-transition class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm space-y-8">
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        <span class="w-2 h-8 bg-toba-green rounded-full"></span> Identitas Visual
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Logo Utama (Light Mode)</label>
                            <div class="relative group h-32 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden flex flex-col items-center justify-center p-4">
                                <img src="{{ $general['logo_light_url'] ?? asset('assets/img/logo.png') }}" class="max-h-full object-contain" id="preview-logo-light">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                    <button type="button" @click="openMedia('logo_light')" class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center hover:bg-indigo-500 transition-all"><i class="fas fa-images"></i></button>
                                </div>
                                <input type="hidden" name="logo_light_url" value="{{ $general['logo_light_url'] ?? '' }}">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Logo Footer (Dark Mode)</label>
                            <div class="relative group h-32 rounded-3xl bg-slate-900 border-2 border-dashed border-slate-700 overflow-hidden flex flex-col items-center justify-center p-4">
                                <img src="{{ $general['logo_dark_url'] ?? asset('assets/img/logo-white.png') }}" class="max-h-full object-contain" id="preview-logo-dark">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                    <button type="button" @click="openMedia('logo_dark')" class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center hover:bg-indigo-500 transition-all"><i class="fas fa-images"></i></button>
                                </div>
                                <input type="hidden" name="logo_dark_url" value="{{ $general['logo_dark_url'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Site Favicon</label>
                            <div class="flex items-center gap-6 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center overflow-hidden border border-slate-200">
                                    <img src="{{ $general['icon_url'] ?? asset('favicon.ico') }}" class="w-8 h-8 object-contain" id="preview-favicon">
                                </div>
                                <div class="flex-1 space-y-2">
                                    <p class="text-[8px] text-slate-400 leading-relaxed font-bold uppercase tracking-tighter">Format .ico atau .png (64x64px)</p>
                                    <input type="hidden" name="icon_url" value="{{ $general['icon_url'] ?? '' }}">
                                </div>
                                <button type="button" @click="openMedia('favicon')" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-500 transition-all shadow-sm">
                                    <i class="fas fa-images text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Situs</label>
                            <input type="text" name="site_name" value="{{ $general['site_name'] ?? 'Sujai Laketoba' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm">
                    <h4 class="text-sm font-black text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-toba-green"></i> Informasi Tambahan
                    </h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Slogan Situs</label>
                            <input type="text" name="site_tagline" value="{{ $general['site_tagline'] ?? 'Temukan Keindahan Danau Toba Bersama Kami' }}" class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold text-xs text-slate-700">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Deskripsi Singkat Footer</label>
                            <textarea name="site_footer_desc" rows="4" class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold text-xs text-slate-500 leading-relaxed">{{ $general['site_footer_desc'] ?? 'Platform tour & travel terpercaya untuk eksplorasi Danau Toba dan sekitarnya.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Tab -->
        <div x-show="activeTab === 'contact'" x-transition class="space-y-8">
            <div class="bg-white rounded-[3.5rem] p-12 border border-slate-100 shadow-sm max-w-4xl mx-auto space-y-10">
                <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span> Konfigurasi Kontak & WA
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="p-8 bg-emerald-50 rounded-[2.5rem] space-y-4">
                            <div class="flex items-center gap-4 text-emerald-600">
                                <i class="fab fa-whatsapp text-3xl"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">WhatsApp Main Number</span>
                            </div>
                            <input type="text" name="wa_number" value="{{ $general['wa_number'] ?? '628123456789' }}" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-black text-slate-900 text-lg shadow-sm">
                            <p class="text-[8px] font-bold text-emerald-400 uppercase tracking-widest italic">Mulai dengan kode negara (misal: 62813...)</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">WA Welcome Message</label>
                            <input type="text" name="wa_message" value="{{ $general['wa_message'] ?? 'Halo Sujai Laketoba, saya ingin bertanya tentang...' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 text-xs">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-envelope text-slate-300"></i> Email Kontak
                            </label>
                            <input type="email" name="contact_email" value="{{ $general['contact_email'] ?? 'hello@sujailaketoba.com' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-location-dot text-slate-300"></i> Alamat Kantor Utama
                            </label>
                            <textarea name="office_address" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-600 text-xs leading-relaxed">{{ $general['office_address'] ?? 'Jl. Sipoholon No. 45, Balige, Toba, Sumatera Utara.' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Instagram</label>
                        <input type="text" name="social_instagram" value="{{ $general['social_instagram'] ?? '' }}" placeholder="@sujailaketoba" class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl font-bold text-[10px]">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Facebook</label>
                        <input type="text" name="social_facebook" value="{{ $general['social_facebook'] ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl font-bold text-[10px]">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">TikTok</label>
                        <input type="text" name="social_tiktok" value="{{ $general['social_tiktok'] ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl font-bold text-[10px]">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Youtube</label>
                        <input type="text" name="social_youtube" value="{{ $general['social_youtube'] ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl font-bold text-[10px]">
                    </div>
                </div>

                <!-- Rating & Ulasan (angka dari Google Maps, diisi manual) -->
                <div class="pt-8 border-t border-slate-100 space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="text-amber-400 text-lg">★</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Rating & Ulasan</span>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Rating <span class="text-slate-300 normal-case">(0–5)</span></label>
                            <input type="number" step="0.1" min="0" max="5" name="rating_override" value="{{ $general['rating_override'] ?? '' }}" placeholder="mis. 4.9" class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl font-bold text-[10px]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Jumlah Ulasan</label>
                            <input type="number" min="0" name="review_count_override" value="{{ $general['review_count_override'] ?? '' }}" placeholder="mis. 87" class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl font-bold text-[10px]">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Link Google Maps <span class="text-slate-300 normal-case">(opsional — jumlah ulasan jadi link ke listing)</span></label>
                        <input type="text" name="google_maps_url" value="{{ $general['google_maps_url'] ?? '' }}" placeholder="https://maps.app.goo.gl/..." class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl font-bold text-[10px]">
                    </div>

                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest italic leading-relaxed">
                        Isi sesuai angka yang tampil di Google Maps Anda. Kosongkan rating → badge disembunyikan (bukan angka palsu).
                    </p>
                </div>
            </div>
        </div>

        <!-- SEO Tab -->
        <div x-show="activeTab === 'seo'" x-transition class="space-y-8">
            <div class="bg-white rounded-[3.5rem] p-12 border border-slate-100 shadow-sm max-w-4xl mx-auto space-y-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        <span class="w-2 h-8 bg-indigo-500 rounded-full"></span> Global SEO & Indexing
                    </h3>
                    <div class="flex items-center gap-2 bg-indigo-50 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                        <span class="text-[8px] font-black text-indigo-600 uppercase tracking-widest">Live Optimization</span>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Meta Title Global (Brand)</label>
                        <input type="text" name="seo_meta_title" value="{{ $general['seo_meta_title'] ?? 'Sujai Laketoba - Tour & Travel Agency' }}" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-black text-slate-900 text-lg shadow-inner">
                        <p class="text-[9px] text-slate-400 font-bold italic">Rekomendasi: 50-60 karakter.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Meta Description Global</label>
                        <textarea name="seo_meta_desc" rows="4" class="w-full px-8 py-6 bg-slate-50 border-none rounded-3xl font-bold text-slate-600 text-sm leading-relaxed">{{ $general['seo_meta_desc'] ?? 'Agen perjalanan terpercaya untuk wisata Danau Toba, Samosir, dan Sumatera Utara. Paket tour lengkap dengan akomodasi terbaik.' }}</textarea>
                        <p class="text-[9px] text-slate-400 font-bold italic">Rekomendasi: 150-160 karakter.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Meta Keywords</label>
                            <textarea name="seo_meta_keywords" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-500 text-xs">{{ $general['seo_meta_keywords'] ?? 'danau toba, tour samosir, travel medan, paket wisata sumatera utara' }}</textarea>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Daftar Kota Target SEO (pSEO Origins)</label>
                            <textarea name="seo_pseo_origins" rows="3" placeholder="Jakarta, Surabaya, Malaysia, Singapore" class="w-full px-6 py-4 bg-indigo-50/50 border-none rounded-2xl font-bold text-indigo-900 text-xs">{{ $general['seo_pseo_origins'] ?? 'Jakarta, Surabaya, Bandung, Bali, Batam, Palembang, Makassar, Semarang, Yogyakarta, Kuala Lumpur, Singapore, Penang, Pekanbaru, Padang, Malaysia' }}</textarea>
                            <p class="text-[8px] font-bold text-slate-400 uppercase">Pisahkan dengan koma. Akan otomatis membuat halaman target SEO & masuk ke Sitemap.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Google Analytics / GTM ID</label>
                            <input type="text" name="seo_ga_id" value="{{ $general['seo_ga_id'] ?? '' }}" placeholder="G-XXXXXXXXXX" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-black text-slate-400 text-xs tracking-widest">
                            <p class="text-[8px] font-bold text-slate-400 uppercase">Input ID saja, skrip akan ditambahkan otomatis.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-slate-100">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fab fa-facebook-square text-blue-500"></i> Meta (Facebook) Pixel ID
                            </label>
                            <input type="text" name="seo_pixel_id" value="{{ $general['seo_pixel_id'] ?? '' }}" placeholder="123456789012345" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-black text-slate-400 text-xs tracking-widest">
                            <p class="text-[8px] font-bold text-slate-400 uppercase">Masukkan Pixel ID untuk retargeting Facebook/Instagram Ads.</p>
                        </div>
                        <div class="space-y-3 flex flex-col justify-center p-6 bg-blue-50 rounded-2xl">
                            <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest flex items-center gap-2"><i class="fas fa-lightbulb"></i> Tips Retargeting</p>
                            <p class="text-[10px] text-blue-400 leading-relaxed">Dengan Pixel aktif, Anda bisa menargetkan ulang pengunjung yang sudah melihat halaman paket wisata Anda di Facebook & Instagram Ads.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company & Invoice Tab -->
        @php $company = $company ?? []; @endphp
        <div x-show="activeTab === 'company'" x-transition class="space-y-8">
            <div class="bg-white rounded-[3.5rem] p-12 border border-slate-100 shadow-sm max-w-4xl mx-auto space-y-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        <span class="w-2 h-8 bg-amber-500 rounded-full"></span> Identitas Perusahaan & Invoice
                    </h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-3 ml-5">Data ini muncul di header & instruksi pembayaran pada PDF invoice.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-id-card-clip text-slate-300"></i> Nama Legal Perusahaan
                            </label>
                            <input type="text" name="company[legal_name]" value="{{ $company['legal_name'] ?? '' }}" placeholder="PT Sujai Laketoba Experience" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-receipt text-slate-300"></i> NPWP
                            </label>
                            <input type="text" name="company[tax_id]" value="{{ $company['tax_id'] ?? '' }}" placeholder="00.000.000.0-000.000" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-700">
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest italic">Kosongkan bila tidak ingin ditampilkan di invoice.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="p-8 bg-amber-50 rounded-[2.5rem] space-y-4">
                            <div class="flex items-center gap-4 text-amber-600">
                                <i class="fas fa-building-columns text-2xl"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Rekening Pembayaran</span>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-amber-500/70 uppercase tracking-widest">Bank & Nomor Rekening</label>
                                <input type="text" name="company[bank_account]" value="{{ $company['bank_account'] ?? '' }}" placeholder="BCA 1234567890" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-black text-slate-900 shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-amber-500/70 uppercase tracking-widest">Atas Nama</label>
                                <input type="text" name="company[bank_account_name]" value="{{ $company['bank_account_name'] ?? '' }}" placeholder="PT Sujai Laketoba Experience" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold text-slate-700 shadow-sm">
                            </div>
                            <p class="text-[8px] font-bold text-amber-400 uppercase tracking-widest italic">Instruksi transfer hanya muncul bila nomor rekening diisi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finance Tab -->
        @php $finance = $general['finance'] ?? []; @endphp
        <div x-show="activeTab === 'finance'" x-transition class="space-y-8">
            <div class="bg-white rounded-[3.5rem] p-12 border border-slate-100 shadow-sm max-w-4xl mx-auto space-y-10">
                <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                    <span class="w-2 h-8 bg-blue-500 rounded-full"></span> Pengaturan Keuangan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="p-8 bg-blue-50 rounded-[2.5rem] space-y-4">
                            <div class="flex items-center gap-4 text-blue-600">
                                <i class="fas fa-percent text-3xl"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Pajak & Layanan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" step="0.1" name="finance[tax_percentage]" value="{{ $finance['tax_percentage'] ?? 11 }}" class="w-32 px-4 py-4 bg-white border-none rounded-2xl font-black text-slate-900 text-lg shadow-sm text-center">
                                <span class="font-bold text-slate-500 text-xl">%</span>
                            </div>
                            <p class="text-[8px] font-bold text-blue-400 uppercase tracking-widest italic">Pajak akan ditambahkan di total akhir pemesanan paket.</p>
                        </div>
                    </div>

                        <div class="space-y-4">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-key text-slate-300"></i> API Key (ExchangeRate-API)
                            </label>
                            <input type="text" name="finance[exchange_rate_api_key]" id="api_key_input" value="{{ $finance['exchange_rate_api_key'] ?? 'b753386c73cbdf1122c8f917' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900">
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest italic">Ambil dari app.exchangerate-api.com.</p>
                                <button type="button" onclick="refreshRates()" class="text-[9px] font-bold px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                                    <i class="fas fa-sync-alt mr-1"></i> Refresh Kurs dari API
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4 mt-6">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-coins text-slate-300"></i> Nilai Kurs MYR (1 MYR =)
                            </label>
                            <div class="flex items-center mb-2">
                                <span class="px-4 py-4 bg-slate-100 rounded-l-2xl font-bold text-slate-500">Rp</span>
                                <input type="number" name="finance[exchange_rate_manual_myr]" id="rate_myr_input" value="{{ $finance['exchange_rate_manual_myr'] ?? 3500 }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-r-2xl font-bold text-slate-900">
                            </div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 mt-4">
                                <i class="fas fa-coins text-slate-300"></i> Nilai Kurs SGD (1 SGD =)
                            </label>
                            <div class="flex items-center">
                                <span class="px-4 py-4 bg-slate-100 rounded-l-2xl font-bold text-slate-500">Rp</span>
                                <input type="number" name="finance[exchange_rate_manual_sgd]" id="rate_sgd_input" value="{{ $finance['exchange_rate_manual_sgd'] ?? 11500 }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-r-2xl font-bold text-slate-900">
                            </div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-2 italic">Nilai ini yang akan digunakan di website. Bisa diedit manual atau klik Refresh Kurs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Save Button -->
        <div class="fixed bottom-10 right-10 z-[100]">
            <button type="submit" class="group flex items-center gap-4 bg-slate-900 text-white px-8 py-5 rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-toba-green hover:scale-105 transition-all duration-300">
                <i class="fas fa-save text-lg group-hover:rotate-12 transition-transform"></i>
                Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openMedia(target) {
        window.dispatchEvent(new CustomEvent('open-media-picker', { 
            detail: { 
                callback: (item) => {
                    const url = '/storage/' + item.path;
                    if (target === 'logo_light') {
                        document.getElementById('preview-logo-light').src = url;
                        document.querySelector('input[name="logo_light_url"]').value = url;
                    } else if (target === 'logo_dark') {
                        document.getElementById('preview-logo-dark').src = url;
                        document.querySelector('input[name="logo_dark_url"]').value = url;
                    } else if (target === 'favicon') {
                        document.getElementById('preview-favicon').src = url;
                        document.querySelector('input[name="icon_url"]').value = url;
                    }
                } 
            } 
        }));
    }
    function refreshRates() {
        Swal.fire({
            title: 'Refresh Kurs?',
            text: "Sistem akan mengambil kurs terbaru dari API. Pastikan Anda telah menyimpan API Key bila baru saja mengubahnya.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya, Refresh',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memuat...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                
                fetch('{{ route('admin.settings.refresh-rates') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Gagal!', data.error, 'error');
                    } else {
                        document.getElementById('rate_myr_input').value = data.MYR;
                        document.getElementById('rate_sgd_input').value = data.SGD;
                        Swal.fire('Berhasil!', 'Kurs MYR dan SGD telah diperbarui. Jangan lupa klik Simpan Pengaturan.', 'success');
                    }
                })
                .catch(error => {
                    Swal.fire('Gagal!', 'Terjadi kesalahan sistem saat mengambil data.', 'error');
                });
            }
        });
    }
</script>
@endpush
@endsection
