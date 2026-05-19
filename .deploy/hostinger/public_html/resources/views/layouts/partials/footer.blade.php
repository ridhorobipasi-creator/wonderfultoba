<footer class="bg-slate-900 pt-20 md:pt-24 pb-12 px-6 md:px-8 relative overflow-hidden">
    <!-- Decor -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-lake-blue via-lake-mid to-lake-light"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-lake-blue/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 mb-16 md:mb-20">
            <!-- Brand -->
            <div class="space-y-6">
                <div class="flex items-center">
                    @php
                        $logoDark = $siteSettings['general']['logo_dark_url'] ?? ($siteSettings['general']['logo_light_url'] ?? null);
                        $brandName = $siteSettings['general']['site_name'] ?? 'Sujailake Toba';
                    @endphp

                    @if($logoDark)
                        <img 
                            src="{{ imageUrl($logoDark) }}" 
                            alt="{{ $brandName }}"
                            class="h-10 w-auto object-contain brightness-0 invert opacity-90"
                        />
                    @else
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-lake-blue rounded-lg flex items-center justify-center text-white font-black text-xl">S</div>
                            <span class="text-xl font-black text-white tracking-tighter uppercase">Sujailake <span class="text-lake-light">Toba</span></span>
                        </div>
                    @endif
                </div>
                <p class="text-slate-400 text-sm leading-relaxed font-medium">
                    {{ $siteSettings['general']['site_footer_desc'] ?? 'Penyedia layanan perjalanan wisata premium terbaik di Sumatera Utara. Kami menghadirkan pengalaman tak terlupakan di setiap destinasi Danau Toba.' }}
                </p>
                <div class="flex items-center space-x-4">
                    @if($siteSettings['general']['social_instagram'] ?? false)
                        <a href="https://instagram.com/{{ str_replace('@', '', $siteSettings['general']['social_instagram']) }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-lake-blue hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if($siteSettings['general']['social_facebook'] ?? false)
                        <a href="{{ $siteSettings['general']['social_facebook'] }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-lake-blue hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($siteSettings['general']['social_youtube'] ?? false)
                        <a href="{{ $siteSettings['general']['social_youtube'] }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-lake-blue hover:text-white transition-all"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if($siteSettings['general']['social_tiktok'] ?? false)
                        <a href="{{ $siteSettings['general']['social_tiktok'] }}" target="_blank" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-lake-blue hover:text-white transition-all"><i class="fab fa-tiktok"></i></a>
                    @endif
                </div>
            </div>

            <!-- Explore -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Layanan Kami</h4>
                <ul class="space-y-4">
                    <li><a href="/" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Beranda</a></li>
                    <li><a href="/packages" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Paket Wisata</a></li>
                    <li><a href="/gallery" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Galeri Foto</a></li>
                    <li><a href="/sewa-mobil" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Sewa Mobil</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Bantuan</h4>
                <ul class="space-y-4">
                    <li><a href="/about" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Tentang Kami</a></li>
                    <li><a href="/contact" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Hubungi Kami</a></li>
                    <li><a href="/terms" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="/privacy" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="/blog" class="text-slate-400 hover:text-lake-blue text-sm font-bold transition-colors">Pusat Artikel</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Berlangganan</h4>
                <p class="text-slate-400 text-xs font-medium mb-6 leading-relaxed">Dapatkan info paket promo dan destinasi terbaru langsung di email Anda.</p>
                <form onsubmit="event.preventDefault(); alert('Terima kasih! Anda telah terdaftar.')" class="relative mb-8">
                    <input type="email" placeholder="Email Anda" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-6 pr-14 text-white text-xs font-bold focus:outline-none focus:border-lake-blue transition-all">
                    <button type="submit" class="absolute right-2 top-2 bottom-2 w-10 bg-lake-blue text-white rounded-xl flex items-center justify-center hover:bg-lake-light transition-all">
                        <i class="fas fa-paper-plane text-[10px]"></i>
                    </button>
                </form>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3 text-slate-400">
                        <i class="fas fa-phone text-lake-light text-[10px]"></i>
                        <p class="text-xs font-bold">{{ $siteSettings['general']['wa_number'] ?? '+62 813-2388-8207' }}</p>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-400">
                        <i class="fas fa-envelope text-lake-light text-[10px]"></i>
                        <p class="text-xs font-bold">{{ $siteSettings['general']['contact_email'] ?? 'info@sujailaketoba.com' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} <span class="text-white">Sujailake Toba</span>. Dikelola Secara Profesional.
            </p>
            <div class="flex items-center space-x-6">
                @php
                    $partnerLogoUrl = $siteSettings['cms_landing']['brand_partner_logo_url'] ?? 'https://upload.wikimedia.org/wikipedia/commons/b/b1/Wonderful_Indonesia_logo.svg';
                @endphp
                <x-premium-image :src="$partnerLogoUrl" alt="Partner Logo" class="h-8 opacity-50 grayscale hover:grayscale-0 transition-all cursor-pointer" />
            </div>
        </div>
    </div>
</footer>
