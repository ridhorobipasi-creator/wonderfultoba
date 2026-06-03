@extends('admin.layout')

@section('title', 'Pengaturan Umum')
@section('page-title', 'Pengaturan Umum')

@section('content')
<div x-data="{ activeTab: 'branding' }" class="space-y-8">

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
</script>
@endpush
@endsection
