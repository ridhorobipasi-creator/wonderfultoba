<footer class="bg-slate-950 pt-16 md:pt-20 pb-10 px-6 md:px-8 relative overflow-hidden">
    <!-- Decor -->
    <div class="absolute top-0 left-0 w-full h-[1px] bg-white/10"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 mb-12 md:mb-16">
            <!-- Brand -->
            <div class="space-y-6">
                <div class="flex items-center">
                    @php
                        $logoDark = $siteSettings['general']['logo_dark_url'] ?? ($siteSettings['general']['logo_light_url'] ?? null);
                        $brandName = $siteSettings['general']['site_name'] ?? 'Sujai Laketoba';
                    @endphp

                    @if($logoDark)
                        <img 
                            src="{{ $logoDark }}" 
                            alt="{{ $brandName }}"
                            class="h-8 w-auto object-contain brightness-0 invert opacity-90"
                        />
                    @else
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 bg-toba-green rounded-lg flex items-center justify-center text-white font-bold text-lg">S</div>
                            <span class="text-lg font-bold text-white tracking-tight uppercase">Sujai <span class="text-toba-green">Laketoba</span></span>
                        </div>
                    @endif
                </div>
                <p class="text-slate-400 text-xs leading-relaxed font-normal">
                    {{ $siteSettings['general']['site_footer_desc'] ?? 'Penyedia layanan perjalanan wisata premium terbaik di Sumatera Utara. Kami menghadirkan pengalaman tak terlupakan di setiap destinasi.' }}
                </p>
                <div class="flex items-center space-x-3">
                    @if($siteSettings['general']['social_instagram'] ?? false)
                        <a href="https://instagram.com/{{ str_replace('@', '', $siteSettings['general']['social_instagram']) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white transition-all"><i class="fab fa-instagram text-sm"></i></a>
                    @endif
                    @if($siteSettings['general']['social_facebook'] ?? false)
                        <a href="{{ $siteSettings['general']['social_facebook'] }}" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white transition-all"><i class="fab fa-facebook-f text-sm"></i></a>
                    @endif
                    @if($siteSettings['general']['social_youtube'] ?? false)
                        <a href="{{ $siteSettings['general']['social_youtube'] }}" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white transition-all"><i class="fab fa-youtube text-sm"></i></a>
                    @endif
                    @if($siteSettings['general']['social_tiktok'] ?? false)
                        <a href="{{ $siteSettings['general']['social_tiktok'] }}" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white transition-all"><i class="fab fa-tiktok text-sm"></i></a>
                    @endif
                </div>
            </div>

            <!-- Explore -->
            <div>
                <h4 class="text-white font-semibold text-[10px] uppercase tracking-[0.2em] mb-6">Layanan Kami</h4>
                <ul class="space-y-3">
                    <li><a href="/" class="text-slate-400 hover:text-white text-xs transition-colors">Paket Wisata</a></li>
                    <li><a href="/tour/packages" class="text-slate-400 hover:text-white text-xs transition-colors">Semua Destinasi</a></li>
                    <li><a href="/tour/gallery" class="text-slate-400 hover:text-white text-xs transition-colors">Galeri Foto</a></li>
                    <li><a href="/tour/blog" class="text-slate-400 hover:text-white text-xs transition-colors">Blog Perjalanan</a></li>
                    <li><a href="/sewa-mobil" class="text-slate-400 hover:text-white text-xs transition-colors">Sewa Mobil</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-semibold text-[10px] uppercase tracking-[0.2em] mb-6">Bantuan</h4>
                <ul class="space-y-3">
                    <li><a href="/about" class="text-slate-400 hover:text-white text-xs transition-colors">Tentang Kami</a></li>
                    <li><a href="/payment" class="text-slate-400 hover:text-white text-xs transition-colors">Cara Pembayaran</a></li>
                    <li><a href="/terms" class="text-slate-400 hover:text-white text-xs transition-colors">Syarat &amp; Ketentuan</a></li>
                    <li><a href="/privacy" class="text-slate-400 hover:text-white text-xs transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="/tour/blog" class="text-slate-400 hover:text-white text-xs transition-colors">Pusat Artikel</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-semibold text-[10px] uppercase tracking-[0.2em] mb-6">Alamat & Kontak</h4>
                <div class="space-y-3 text-slate-400 text-xs">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-map-marker-alt text-toba-green mt-0.5 shrink-0"></i>
                        <p class="font-normal">{{ $siteSettings['general']['office_address'] ?? 'Jl. Sisingamangaraja No. 1, Parapat, Sumatera Utara 21174' }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-phone text-toba-green shrink-0"></i>
                        <p class="font-normal">{{ $siteSettings['general']['wa_number'] ?? '+62 813-2388-8207' }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-toba-green shrink-0"></i>
                        <p class="font-normal">{{ $siteSettings['general']['contact_email'] ?? 'info@sujailaketoba.com' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <p class="text-slate-500 text-[10px] font-semibold uppercase tracking-wider">
                &copy; {{ date('Y') }} <span class="text-white">Sujai Laketoba</span>. Dikelola Secara Profesional.
            </p>
            <div class="flex items-center gap-4">
                @php
                    $partnerLogoUrl = $siteSettings['cms_landing']['brand_partner_logo_url'] ?? 'https://upload.wikimedia.org/wikipedia/commons/b/b1/Wonderful_Indonesia_logo.svg';
                @endphp
                <div class="flex items-center gap-3">
                    <x-premium-image :src="$partnerLogoUrl" alt="Wonderful Indonesia" class="h-6 opacity-40 grayscale hover:grayscale-0 transition-all" />
                    <span class="text-slate-600 text-[8px] font-semibold uppercase tracking-wider leading-tight">Agen Resmi<br>Wonderful Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</footer>
