<footer class="bg-slate-900 pt-20 md:pt-24 pb-12 px-6 md:px-8 relative overflow-hidden">
    <!-- Decor -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-toba-green via-emerald-400 to-toba-accent"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-toba-green/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 mb-16 md:mb-20">
            <!-- Brand -->
            <div class="space-y-6">
                <div class="flex items-center">
                    @php
                        $logoDark = $siteSettings['general']['logo_dark_url'] ?? ($siteSettings['general']['logo_light_url'] ?? null);
                        $brandName = $siteSettings['general']['site_name'] ?? 'Wonderful Toba';
                    @endphp

                    @if($logoDark)
                        <img 
                            src="{{ $logoDark }}" 
                            alt="{{ $brandName }}"
                            class="h-10 w-auto object-contain brightness-0 invert opacity-90"
                        />
                    @else
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-toba-green rounded-lg flex items-center justify-center text-white font-black text-xl">W</div>
                            <span class="text-xl font-black text-white tracking-tighter uppercase">Wonderful <span class="text-toba-green">Toba</span></span>
                        </div>
                    @endif
                </div>
                <p class="text-slate-400 text-sm leading-relaxed font-medium">
                    {{ $siteSettings['general']['site_footer_desc'] ?? 'Penyedia layanan perjalanan wisata premium dan outbound korporat terbaik di Sumatera Utara. Kami menghadirkan pengalaman tak terlupakan di setiap destinasi.' }}
                </p>
                <div class="flex items-center space-x-4">
                    @if($siteSettings['general']['social_instagram'] ?? false)
                        <a href="https://instagram.com/{{ str_replace('@', '', $siteSettings['general']['social_instagram']) }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if($siteSettings['general']['social_facebook'] ?? false)
                        <a href="{{ $siteSettings['general']['social_facebook'] }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($siteSettings['general']['social_youtube'] ?? false)
                        <a href="{{ $siteSettings['general']['social_youtube'] }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if($siteSettings['general']['social_tiktok'] ?? false)
                        <a href="{{ $siteSettings['general']['social_tiktok'] }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-tiktok"></i></a>
                    @endif
                </div>
            </div>

            <!-- Explore -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Layanan Kami</h4>
                <ul class="space-y-4">
                    <li><a href="/" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Paket Wisata</a></li>
                    <li><a href="/tour/packages" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Semua Destinasi</a></li>
                    <li><a href="/tour/gallery" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Galeri Foto</a></li>
                    <li><a href="/tour/blog" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Blog Perjalanan</a></li>
                    <li><a href="/sewa-mobil" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Sewa Mobil</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Bantuan</h4>
                <ul class="space-y-4">
                    <li><a href="/about" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Tentang Kami</a></li>
                    <li><a href="/payment" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Cara Pembayaran</a></li>
                    <li><a href="/terms" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Syarat &amp; Ketentuan</a></li>
                    <li><a href="/privacy" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="/tour/blog" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Pusat Artikel</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Alamat & Kontak</h4>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3 text-slate-400">
                        <i class="fas fa-map-marker-alt text-toba-green mt-1"></i>
                        <p class="text-sm font-medium">{{ $siteSettings['general']['office_address'] ?? 'Jl. Sisingamangaraja No. 1, Parapat, Sumatera Utara 21174' }}</p>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-400">
                        <i class="fas fa-phone text-toba-green"></i>
                        <p class="text-sm font-medium">{{ $siteSettings['general']['wa_number'] ?? '+62 813-2388-8207' }}</p>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-400">
                        <i class="fas fa-envelope text-toba-green"></i>
                        <p class="text-sm font-medium">{{ $siteSettings['general']['contact_email'] ?? 'info@sujailaketoba.com' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} <span class="text-white">Wonderful Toba</span>. Dikelola Secara Profesional.
            </p>
            <div class="flex items-center gap-4">
                @php
                    $partnerLogoUrl = $siteSettings['cms_landing']['brand_partner_logo_url'] ?? 'https://upload.wikimedia.org/wikipedia/commons/b/b1/Wonderful_Indonesia_logo.svg';
                @endphp
                <div class="flex items-center gap-3">
                    <x-premium-image :src="$partnerLogoUrl" alt="Wonderful Indonesia" class="h-8 opacity-60 grayscale hover:grayscale-0 transition-all" />
                    <span class="text-slate-600 text-[9px] font-bold uppercase tracking-widest leading-tight">Agen Resmi<br>Wonderful Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</footer>
