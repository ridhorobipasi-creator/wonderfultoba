<footer class="bg-slate-900 pt-24 pb-12 px-4 relative overflow-hidden">
    <!-- Decor -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-toba-green via-emerald-400 to-toba-accent"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-toba-green/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
            <!-- Brand -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-toba-green rounded-lg flex items-center justify-center text-white font-black text-xl">W</div>
                    <span class="text-xl font-black text-white tracking-tighter uppercase">Wonderful <span class="text-toba-green">Toba</span></span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed font-medium">
                    Penyedia layanan perjalanan wisata premium dan outbound korporat terbaik di Sumatera Utara. Kami menghadirkan pengalaman tak terlupakan di setiap destinasi.
                </p>
                <div class="flex items-center space-x-4">
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-slate-300 hover:bg-toba-green hover:text-white transition-all"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Explore -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Layanan Kami</h4>
                <ul class="space-y-4">
                    <li><a href="/tour" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Tour & Wisata</a></li>
                    <li><a href="/outbound" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Corporate Outbound</a></li>
                    <li><a href="/cars" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Rental Mobil Premium</a></li>
                    <li><a href="/tour/packages" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Paket Hemat</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-8">Bantuan</h4>
                <ul class="space-y-4">
                    <li><a href="/about" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Tentang Kami</a></li>
                    <li><a href="/terms" class="text-slate-400 hover:text-toba-green text-sm font-bold transition-colors">Syarat & Ketentuan</a></li>
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
                        <p class="text-sm font-medium">Jl. Jamin Ginting No. 123, Medan, Sumatera Utara</p>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-400">
                        <i class="fas fa-phone text-toba-green"></i>
                        <p class="text-sm font-medium">+62 813-2388-8207</p>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-400">
                        <i class="fas fa-envelope text-toba-green"></i>
                        <p class="text-sm font-medium">info@wonderfultoba.com</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} <span class="text-white">Wonderful Toba</span>. Dikelola Secara Profesional.
            </p>
            <div class="flex items-center space-x-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Wonderful_Indonesia_logo.svg" alt="Wonderful Indonesia" class="h-8 opacity-50 grayscale hover:grayscale-0 transition-all cursor-pointer">
            </div>
        </div>
    </div>
</footer>
