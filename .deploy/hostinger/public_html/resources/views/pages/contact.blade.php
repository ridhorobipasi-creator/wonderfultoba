@extends('layouts.app')

@section('title', 'Hubungi Kami – Sujailake Toba')

@section('content')
<div class="min-h-screen bg-white pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <!-- Header -->
        <div class="mb-20 text-center max-w-3xl mx-auto">
            <span class="text-lake-blue text-[11px] font-black uppercase tracking-[0.4em] mb-4 block animate-in fade-in slide-in-from-bottom-4 duration-700">Hubungi Kami</span>
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-[0.9] mb-8 animate-in fade-in slide-in-from-bottom-6 duration-700">Kami Siap <span class="text-lake-blue">Membantu Perjalanan Anda.</span></h1>
            <p class="text-slate-500 font-medium text-lg animate-in fade-in slide-in-from-bottom-8 duration-700">Punya pertanyaan tentang paket wisata, reservasi grup, atau permintaan khusus? Jangan ragu untuk menghubungi tim ahli kami.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24">
            <!-- Left: Contact Info -->
            <div class="lg:col-span-5 space-y-12">
                <div class="space-y-10">
                    <!-- Location -->
                    <div class="flex gap-6 group">
                        <div class="w-16 h-16 rounded-3xl bg-lake-blue/5 text-lake-blue flex items-center justify-center text-2xl shadow-sm group-hover:bg-lake-blue group-hover:text-white transition-all duration-500 shrink-0">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kantor Pusat</h4>
                            <p class="text-lg font-bold text-slate-900 leading-relaxed">{{ $siteSettings['general']['office_address'] ?? 'Jl. Jamin Ginting No. 123, Balige, Toba, Sumatera Utara' }}</p>
                        </div>
                    </div>

                    <!-- Phone/WA -->
                    <div class="flex gap-6 group">
                        <div class="w-16 h-16 rounded-3xl bg-lake-blue/5 text-lake-blue flex items-center justify-center text-2xl shadow-sm group-hover:bg-lake-blue group-hover:text-white transition-all duration-500 shrink-0">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">WhatsApp & Telepon</h4>
                            <p class="text-lg font-bold text-slate-900">{{ $siteSettings['general']['wa_number'] ?? '+62 813-2388-8207' }}</p>
                            <p class="text-xs text-slate-400 font-medium mt-1">Tersedia 24/7 untuk keadaan darurat.</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex gap-6 group">
                        <div class="w-16 h-16 rounded-3xl bg-lake-blue/5 text-lake-blue flex items-center justify-center text-2xl shadow-sm group-hover:bg-lake-blue group-hover:text-white transition-all duration-500 shrink-0">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Korespondensi</h4>
                            <p class="text-lg font-bold text-slate-900">{{ $siteSettings['general']['contact_email'] ?? 'hello@sujailaketoba.com' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Social Proof / Trust -->
                <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100">
                    <h5 class="text-sm font-black text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-shield-halved text-lake-blue"></i> Layanan Terpercaya
                    </h5>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-xs font-bold text-slate-600">
                            <div class="w-1.5 h-1.5 rounded-full bg-lake-blue"></div> Respon cepat dalam < 15 menit
                        </li>
                        <li class="flex items-center gap-3 text-xs font-bold text-slate-600">
                            <div class="w-1.5 h-1.5 rounded-full bg-lake-blue"></div> Konsultasi rencana perjalanan gratis
                        </li>
                        <li class="flex items-center gap-3 text-xs font-bold text-slate-600">
                            <div class="w-1.5 h-1.5 rounded-full bg-lake-blue"></div> Dukungan darurat saat berwisata
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-[4rem] p-8 md:p-14 shadow-[0_50px_100px_-20px_rgba(15,23,42,0.08)] border border-slate-50 relative overflow-hidden group">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-lake-blue/5 rounded-full blur-3xl group-hover:bg-lake-blue/10 transition-all duration-1000"></div>
                    
                    <form onsubmit="event.preventDefault(); alert('Pesan Anda telah terkirim. Tim kami akan menghubungi Anda segera.'); this.reset();" class="relative z-10 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                                <input type="text" required class="w-full px-8 py-5 bg-slate-50 border-2 border-transparent focus:border-lake-blue focus:bg-white rounded-3xl outline-none font-bold text-slate-900 transition-all" placeholder="John Doe">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Email</label>
                                <input type="email" required class="w-full px-8 py-5 bg-slate-50 border-2 border-transparent focus:border-lake-blue focus:bg-white rounded-3xl outline-none font-bold text-slate-900 transition-all" placeholder="john@example.com">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Subjek Pesan</label>
                            <select class="w-full px-8 py-5 bg-slate-50 border-2 border-transparent focus:border-lake-blue focus:bg-white rounded-3xl outline-none font-bold text-slate-900 transition-all appearance-none">
                                <option>Pertanyaan Paket Wisata</option>
                                <option>Reservasi Grup / Corporate</option>
                                <option>Kemitraan & Kerjasama</option>
                                <option>Lainnya</option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pesan Anda</label>
                            <textarea rows="6" required class="w-full px-8 py-6 bg-slate-50 border-2 border-transparent focus:border-lake-blue focus:bg-white rounded-3xl outline-none font-bold text-slate-900 transition-all resize-none" placeholder="Tuliskan detail pertanyaan atau permintaan Anda di sini..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.4em] hover:bg-lake-blue hover:-translate-y-1 transition-all duration-500 shadow-xl shadow-slate-900/10 hover:shadow-lake-blue/20">
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="mt-32 rounded-[4rem] overflow-hidden shadow-2xl h-[500px] relative border-8 border-white group">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15945.748301548232!2d99.0601614!3d2.3312384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x302e057f9273c52b%3A0x6b49e5d424b91f0e!2sBalige%2C%20Toba%20Regency%2C%20North%20Sumatra!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                class="grayscale group-hover:grayscale-0 transition-all duration-1000">
            </iframe>
            <div class="absolute bottom-10 left-10 p-8 bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border border-white max-w-sm">
                <p class="text-[10px] font-black text-lake-blue uppercase tracking-widest mb-2">Visit Our Office</p>
                <p class="text-sm font-bold text-slate-900 leading-relaxed">Silakan mampir untuk konsultasi perjalanan tatap muka dengan tim kami di Balige.</p>
            </div>
        </div>
    </div>
</div>
@endsection
