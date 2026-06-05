<footer class="bg-slate-950 pt-16 md:pt-20 pb-10 px-5 md:px-8 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-[1px] bg-white/10"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 mb-12 md:mb-16">

            <!-- ── Brand Column ── -->
            <div class="space-y-6 sm:col-span-2 lg:col-span-1">
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
                            <div class="w-9 h-9 bg-secondary rounded-lg flex items-center justify-center text-on-secondary font-bold text-lg">S</div>
                            <span class="text-lg font-bold font-headline-md text-on-primary tracking-tight uppercase">
                                Sujai <span class="text-secondary">Laketoba</span>
                            </span>
                        </div>
                    @endif
                </div>

                <p class="text-slate-400 font-body-md text-xs leading-relaxed">
                    {{ $siteSettings['general']['site_footer_desc'] ?? 'Penyedia layanan perjalanan wisata di Sumatera Utara. Fokus kami sederhana: perjalanan yang rapi, nyaman, dan mudah dipesan.' }}
                </p>

                <!-- Social links -->
                <div class="flex items-center space-x-3">
                    @if($siteSettings['general']['social_instagram'] ?? false)
                        <a href="https://instagram.com/{{ str_replace('@', '', $siteSettings['general']['social_instagram']) }}" 
                           target="_blank" 
                            class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition-all">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                    @endif
                    @if($siteSettings['general']['social_facebook'] ?? false)
                        <a href="{{ $siteSettings['general']['social_facebook'] }}" 
                           target="_blank"
                            class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition-all">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                    @endif
                    @if($siteSettings['general']['social_youtube'] ?? false)
                        <a href="{{ $siteSettings['general']['social_youtube'] }}" 
                           target="_blank"
                            class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition-all">
                            <i class="fab fa-youtube text-sm"></i>
                        </a>
                    @endif
                    @if($siteSettings['general']['social_tiktok'] ?? false)
                        <a href="{{ $siteSettings['general']['social_tiktok'] }}"
                           target="_blank"
                            class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition-all">
                            <i class="fab fa-tiktok text-sm"></i>
                        </a>
                    @endif
                    @if($siteSettings['general']['social_twitter'] ?? false)
                        <a href="{{ $siteSettings['general']['social_twitter'] }}"
                           target="_blank"
                            class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition-all">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                    @endif
                    @if($siteSettings['general']['social_linkedin'] ?? false)
                        <a href="{{ $siteSettings['general']['social_linkedin'] }}"
                           target="_blank"
                            class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition-all">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    @endif
                    @if($siteSettings['general']['social_telegram'] ?? false)
                        <a href="{{ $siteSettings['general']['social_telegram'] }}"
                           target="_blank"
                            class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition-all">
                            <i class="fab fa-telegram text-sm"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- ── Services ── -->
            <div>
                <h4 class="text-white font-label-caps text-[10px] uppercase tracking-[0.2em] mb-6">{{ __('Layanan Kami') }}</h4>
                <ul class="space-y-3">
                    <li><a href="/" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Beranda') }}</a></li>
                    <li><a href="/tour/packages" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Semua Destinasi') }}</a></li>
                    <li><a href="/tour/gallery" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Galeri Foto') }}</a></li>
                    <li><a href="/tour/blog" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Blog Perjalanan') }}</a></li>
                </ul>
            </div>

            <!-- ── Support ── -->
            <div>
                <h4 class="text-white font-label-caps text-[10px] uppercase tracking-[0.2em] mb-6">{{ __('Bantuan') }}</h4>
                <ul class="space-y-3">
                    <li><a href="/about" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Tentang Kami') }}</a></li>
                    <li><a href="/payment" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Cara Pembayaran') }}</a></li>
                    <li><a href="{{ route('booking.track.form') }}" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Lacak Pesanan') }}</a></li>
                    <li><a href="/terms" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Syarat & Ketentuan') }}</a></li>
                    <li><a href="/privacy" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Kebijakan Privasi') }}</a></li>
                    <li><a href="/tour/blog" class="text-slate-400 hover:text-white font-body-md text-xs transition-colors inline-block">{{ __('Pusat Artikel') }}</a></li>
                </ul>
            </div>

            <!-- ── Contact ── -->
            <div>
                <h4 class="text-white font-label-caps text-[10px] uppercase tracking-[0.2em] mb-6">{{ __('Alamat & Kontak') }}</h4>
                @php
                    $g = $siteSettings['general'] ?? [];
                    $addressLine = $g['office_address'] ?? 'Jl. Sisingamangaraja No. 1, Parapat, Sumatera Utara 21174';
                    $cityPostal = trim(($g['office_city'] ?? '').' '.($g['office_postal'] ?? ''));
                @endphp
                <div class="space-y-4 text-slate-400 font-body-md text-xs">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-secondary mt-0.5 shrink-0"></i>
                        <p>{{ $addressLine }}@if($cityPostal !== ''), {{ $cityPostal }}@endif</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fab fa-whatsapp text-secondary shrink-0"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $g['contact_whatsapp'] ?? '6282277848855') }}" target="_blank"
                           class="hover:text-secondary transition-colors">
                            {{ $g['contact_whatsapp'] ?? '+62 813-2388-8207' }}
                        </a>
                    </div>
                    @if($g['contact_whatsapp_2'] ?? false)
                    <div class="flex items-center space-x-3">
                        <i class="fab fa-whatsapp text-secondary shrink-0"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $g['contact_whatsapp_2']) }}" target="_blank"
                           class="hover:text-secondary transition-colors">
                            {{ $g['contact_whatsapp_2'] }} <span class="text-slate-500">(CS 2)</span>
                        </a>
                    </div>
                    @endif
                    @if($g['contact_phone'] ?? false)
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-secondary shrink-0"></i>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $g['contact_phone']) }}"
                           class="hover:text-secondary transition-colors">
                            {{ $g['contact_phone'] }}
                        </a>
                    </div>
                    @endif
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-secondary shrink-0"></i>
                        <a href="mailto:{{ $g['contact_email'] ?? 'info@sujailaketoba.com' }}"
                           class="hover:text-secondary transition-colors">
                            {{ $g['contact_email'] ?? 'info@sujailaketoba.com' }}
                        </a>
                    </div>
                    @if($g['operating_hours'] ?? false)
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-secondary shrink-0"></i>
                        <p>{{ $g['operating_hours'] }}</p>
                    </div>
                    @endif
                </div>
                @if($g['google_maps_embed'] ?? false)
                <div class="mt-5 rounded-xl overflow-hidden border border-white/10">
                    <iframe src="{{ $g['google_maps_embed'] }}" width="100%" height="140" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi {{ $brandName ?? 'Kantor' }}"></iframe>
                </div>
                @endif
            </div>
        </div>

        <!-- ── Bottom bar ── -->
        <div class="pt-6 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                <p class="text-slate-500 font-label-caps text-[10px] uppercase tracking-wider">
                &copy; {{ date('Y') }} <span class="text-white/80">{{ $siteSettings['general']['site_copyright'] ?? ($siteSettings['general']['site_name'] ?? 'Sujai Laketoba') }}</span>. {{ __('All rights reserved.') }}
            </p>
            <div class="flex items-center gap-4">
                @php
                    $partnerLogoUrl = $siteSettings['cms_landing']['brand_partner_logo_url'] ?? 'https://upload.wikimedia.org/wikipedia/commons/b/b1/Wonderful_Indonesia_logo.svg';
                @endphp
                <div class="flex items-center gap-3">
                    <x-premium-image :src="$partnerLogoUrl" alt="Wonderful Indonesia" class="h-6 opacity-30 grayscale hover:grayscale-0 hover:opacity-70 transition-all" />
                    <span class="text-slate-500 font-label-caps text-[8px] uppercase tracking-wider leading-tight">{{ __('Agen Resmi') }}<br>Wonderful Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</footer>
