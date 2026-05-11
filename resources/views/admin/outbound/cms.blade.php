@extends('admin.layout')

@section('title', 'CMS Master Outbound')
@section('page-title', 'CMS Master Outbound')

@section('content')
<div x-cloak x-data="cmsOutboundHandler" class="flex flex-col xl:flex-row gap-8 min-h-[85vh]">

    
    <!-- LEFT: CONTROL PANEL -->
    <div class="w-full xl:w-[450px] flex-shrink-0 space-y-6">
        <!-- Tab Navigation -->
        <div class="bg-white p-2 rounded-[2rem] shadow-sm border border-slate-100 flex items-center space-x-1 overflow-x-auto no-scrollbar">
            <button @click="activeTab = 'hero'" :class="activeTab === 'hero' ? 'bg-orange-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Hero</button>
            <button @click="activeTab = 'featured'" :class="activeTab === 'featured' ? 'bg-amber-500 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Pinned</button>
            <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Filosofi</button>
            <button @click="activeTab = 'specialist'" :class="activeTab === 'specialist' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">👩‍💼 Specialist</button>
            <button @click="activeTab = 'testimonials'" :class="activeTab === 'testimonials' ? 'bg-amber-400 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">💬 Ulasan</button>
            <button @click="activeTab = 'services'" :class="activeTab === 'services' ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Layanan</button>
            <button @click="activeTab = 'values'" :class="activeTab === 'values' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Values</button>
            <button @click="activeTab = 'sosmed'" :class="activeTab === 'sosmed' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Sosmed</button>
            <button @click="activeTab = 'cta'" :class="activeTab === 'cta' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">CTA</button>
            <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-indigo-500 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap"><i class="fas fa-search mr-1"></i> SEO</button>
        </div>

        <form action="{{ route('admin.cms.save', 'cms_outbound') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm overflow-hidden">
                <!-- Hero Tab -->
                <div x-show="activeTab === 'hero'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-orange-600 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-orange-600"></span> Visual & Headline Outbound
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_hero" value="1" x-model="showHero" class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-orange-600 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Label Atas</label>
                                <input type="text" name="hero_label" x-model="heroLabel" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-black text-orange-600 text-[10px] tracking-widest uppercase">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">WA Admin</label>
                                <input type="text" name="cta_whatsapp_number" value="{{ $settings['cta_whatsapp_number'] ?? '6281323888207' }}" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 text-xs">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Headline Corporate</label>
                            <textarea name="hero_title" x-model="heroTitle" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-600 font-black text-sm text-slate-900"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sub-headline</label>
                            <textarea name="hero_subtitle" x-model="heroSubtitle" rows="4" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-600 font-bold text-slate-600 text-xs leading-relaxed"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Background Hero</label>
                            <div class="relative group h-32 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden group-hover:bg-slate-100 transition">
                                <img :src="heroImage" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                    <button type="button" @click="$el.nextElementSibling.click()" class="w-8 h-8 rounded-full bg-white/20 text-white hover:bg-white/40 flex items-center justify-center transition-colors">
                                        <i class="fas fa-camera text-[10px]"></i>
                                    </button>
                                    <button type="button" @click="openMedia('hero_image_url')" class="w-8 h-8 rounded-full bg-orange-500 text-white hover:bg-orange-600 flex items-center justify-center transition-colors">
                                        <i class="fas fa-images text-[10px]"></i>
                                    </button>
                                    <input type="file" name="hero_image_file" @change="updatePreview($event, 'heroImage')" class="hidden">
                                    <input type="hidden" name="hero_image_url" :value="heroImage">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Tab -->
                <div x-show="activeTab === 'featured'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-amber-500 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Paket Pilihan Outbound
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_featured" value="1" x-model="showFeatured" class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-amber-500 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>
                    @php
                        $outboundPackages = \App\Models\Package::where('isOutbound', true)->where('status', 'active')->get();
                        $pinnedIds = $settings['featured_package_ids'] ?? [];
                    @endphp
                    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 no-scrollbar">
                        @foreach($outboundPackages as $pkg)
                        <label class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-amber-50 transition">
                            <input type="checkbox" name="featured_package_ids[]" value="{{ $pkg->id }}" 
                                   {{ in_array($pkg->id, (array)$pinnedIds) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500">
                            <div class="flex-1">
                                <p class="text-xs font-black text-slate-900">{{ $pkg->name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $pkg->duration }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- About Tab -->
                <div x-show="activeTab === 'about'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-900"></span> Filosofi Layanan
                    </h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Judul Section</label>
                            <input type="text" name="about_hero_title" x-model="aboutTitle" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-black text-slate-900 text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Deskripsi Filosofi</label>
                            <textarea name="about_hero_desc" x-model="aboutDesc" rows="6" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-500 text-xs leading-relaxed"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Image About 1</label>
                                <div class="relative group h-24 rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden">
                                    <img :src="aboutImage1 || 'https://images.unsplash.com/photo-1551818255-e6e10975bc17?w=400'" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1.5">
                                        <button type="button" @click="$el.nextElementSibling.click()" class="w-6 h-6 rounded-full bg-white/20 text-white hover:bg-white/40 flex items-center justify-center transition-colors">
                                            <i class="fas fa-camera text-[8px]"></i>
                                        </button>
                                        <button type="button" @click="openMedia('about_image_1_url')" class="w-6 h-6 rounded-full bg-slate-900 text-white hover:bg-slate-700 flex items-center justify-center transition-colors">
                                            <i class="fas fa-images text-[8px]"></i>
                                        </button>
                                        <input type="file" name="about_image_1_file" @change="updatePreview($event, 'aboutImage1')" class="hidden">
                                        <input type="hidden" name="about_image_1_url" :value="aboutImage1">
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Image About 2</label>
                                <div class="relative group h-24 rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden">
                                    <img :src="aboutImage2 || 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400'" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1.5">
                                        <button type="button" @click="$el.nextElementSibling.click()" class="w-6 h-6 rounded-full bg-white/20 text-white hover:bg-white/40 flex items-center justify-center transition-colors">
                                            <i class="fas fa-camera text-[8px]"></i>
                                        </button>
                                        <button type="button" @click="openMedia('about_image_2_url')" class="w-6 h-6 rounded-full bg-slate-900 text-white hover:bg-slate-700 flex items-center justify-center transition-colors">
                                            <i class="fas fa-images text-[8px]"></i>
                                        </button>
                                        <input type="file" name="about_image_2_file" @change="updatePreview($event, 'aboutImage2')" class="hidden">
                                        <input type="hidden" name="about_image_2_url" :value="aboutImage2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Bg Tab -->
                <div x-show="activeTab === 'services'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Latar Belakang Layanan
                    </h4>
                    <div class="space-y-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Gunakan gambar resolusi tinggi untuk latar belakang bagian "Layanan Kami".</p>
                        <div class="relative group h-48 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden group-hover:bg-slate-100 transition">
                            <template x-if="servicesBg">
                                <img :src="servicesBg" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!servicesBg">
                                <div class="text-center">
                                    <i class="fas fa-image text-slate-300 text-3xl mb-2"></i>
                                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Pilih Gambar</div>
                                </div>
                            </template>
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3">
                                <button type="button" @click="$el.nextElementSibling.click()" class="px-6 py-2 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full font-black text-[9px] uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-all">
                                    <i class="fas fa-camera mr-2"></i> Upload Baru
                                </button>
                                <button type="button" @click="openMedia('services_bg_url')" class="px-6 py-2 bg-emerald-600 text-white rounded-full font-black text-[9px] uppercase tracking-widest shadow-xl shadow-emerald-900/20 hover:bg-emerald-700 transition-all">
                                    <i class="fas fa-images mr-2"></i> Galeri Pusat
                                </button>
                                <input type="file" name="services_bg_file" @change="updatePreview($event, 'servicesBg')" class="hidden">
                                <input type="hidden" name="services_bg_url" :value="servicesBg">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Values Tab -->
                <div x-show="activeTab === 'values'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-900"></span> Core Values & USP
                    </h4>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 no-scrollbar">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="p-5 bg-slate-50 rounded-3xl space-y-3 border border-slate-100">
                            <input type="text" name="about_title_{{ $i }}" value="{{ $settings['about_title_'.$i] ?? 'Kualitas Terjamin' }}" class="w-full px-4 py-2 bg-white border-none rounded-xl font-black text-xs text-slate-900" placeholder="Judul Poin...">
                            <div class="flex gap-2">
                                <input type="text" name="about_icon_{{ $i }}" value="{{ $settings['about_icon_'.$i] ?? 'fa-check' }}" class="w-1/3 px-4 py-2 bg-white border-none rounded-xl font-bold text-[10px] text-orange-600" placeholder="FontAwesome Icon...">
                                <textarea name="about_desc_{{ $i }}" rows="1" class="w-2/3 px-4 py-2 bg-white border-none rounded-xl font-bold text-[10px] text-slate-500" placeholder="Deskripsi...">{{ $settings['about_desc_'.$i] ?? 'Kami menyediakan instruktur bersertifikasi.' }}</textarea>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Sosmed Tab -->
                <div x-show="activeTab === 'sosmed'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span> Kontak & Sosial Media
                    </h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Alamat Kantor</label>
                            <textarea name="office_address" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 text-xs">{{ $settings['office_address'] ?? "Jl. Ringroad No. 123, Medan, Sumatera Utara." }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email Official</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'hello@wonderfultoba.id' }}" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Instagram</label>
                                <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? '@wonderful.outbound' }}" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 text-xs">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Facebook Link</label>
                                <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? '#' }}" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 text-xs">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Tab -->
                <div x-show="activeTab === 'cta'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-900"></span> Call to Action
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_cta" value="1" x-model="showCta" class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-slate-900 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Headline Penutup</label>
                            <textarea name="cta_footer_title" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-black text-slate-900 text-sm">{{ $settings['cta_footer_title'] ?? "Siap Memberikan Pengalaman Terbaik Untuk Tim Anda?" }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Label Tombol</label>
                            <input type="text" name="cta_footer_btn" value="{{ $settings['cta_footer_btn'] ?? 'Konsultasi Sekarang' }}" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 text-xs">
                        </div>
                    </div>
                </div>

                <!-- SEO Tab -->
                <div x-show="activeTab === 'seo'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-indigo-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Optimization (SEO)
                    </h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Meta Title (Custom Page)</label>
                            <input type="text" name="seo_title" value="{{ $settings['seo_title'] ?? '' }}" placeholder="Default: Outbound Corporate..." class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-black text-slate-900 text-xs">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Meta Description</label>
                            <textarea name="seo_description" rows="4" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-500 text-[10px] leading-relaxed">{{ $settings['seo_description'] ?? '' }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Meta Keywords</label>
                            <input type="text" name="seo_keywords" value="{{ $settings['seo_keywords'] ?? '' }}" placeholder="outbound medan, team building toba..." class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-500 text-[10px]">
                        </div>
                    </div>
                </div>

                <!-- Specialist Tab -->
                <div x-show="activeTab === 'specialist'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Outbound Expert Specialist
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-center gap-6 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div class="relative group cursor-pointer w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-xl flex-shrink-0">
                                <img :src="specialist_image" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                    <button type="button" @click="$el.nextElementSibling.click()" class="w-8 h-8 rounded-full bg-white/20 text-white hover:bg-white/40 flex items-center justify-center transition-colors">
                                        <i class="fas fa-camera text-[10px]"></i>
                                    </button>
                                    <button type="button" @click="openMedia('specialist_image')" class="w-8 h-8 rounded-full bg-emerald-500 text-white hover:bg-emerald-600 flex items-center justify-center transition-colors">
                                        <i class="fas fa-images text-[10px]"></i>
                                    </button>
                                    <input type="file" name="specialist_image_file" class="hidden" @change="updatePreview($event, 'specialist_image')">
                                    <input type="hidden" name="specialist_image_url" :value="specialist_image">
                                </div>
                            </div>
                            <div class="flex-1 space-y-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                                    <input type="text" name="specialist_name" x-model="specialist_name" class="w-full px-4 py-2 bg-white border-none rounded-xl font-black text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Jabatan</label>
                                    <input type="text" name="specialist_title" x-model="specialist_title" class="w-full px-4 py-2 bg-white border-none rounded-xl font-bold text-[10px] text-slate-500">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pesan Sapaan (Deskripsi)</label>
                            <textarea name="specialist_desc" x-model="specialist_desc" rows="4" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 font-medium text-slate-600 text-xs leading-relaxed"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Testimonials Tab -->
                <div x-show="activeTab === 'testimonials'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-black text-amber-500 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Testimoni Corporate
                        </h4>
                        <button type="button" @click="addTestimonial()" class="px-4 py-2 bg-amber-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-amber-200">
                            + Tambah Klien
                        </button>
                    </div>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 no-scrollbar">
                        <template x-for="(t, idx) in testimonials" :key="idx">
                            <div class="p-5 bg-slate-50 rounded-3xl space-y-4 relative group border border-slate-100">
                                <button type="button" @click="removeTestimonial(idx)" class="absolute top-4 right-4 w-6 h-6 rounded-full bg-white text-rose-500 shadow-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-times text-[10px]"></i>
                                </button>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 overflow-hidden shrink-0 relative group/avatar">
                                        <img :src="t.image" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/avatar:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1.5">
                                            <button type="button" @click="$el.nextElementSibling.nextElementSibling.click()" class="w-6 h-6 rounded-full bg-white/20 text-white hover:bg-white/40 flex items-center justify-center transition-colors">
                                                <i class="fas fa-camera text-[8px]"></i>
                                            </button>
                                            <button type="button" @click="openMedia('testimonial', idx)" class="w-6 h-6 rounded-full bg-amber-500 text-white hover:bg-amber-600 flex items-center justify-center transition-colors">
                                                <i class="fas fa-images text-[8px]"></i>
                                            </button>
                                            <input type="file" @change="const file = $event.target.files[0]; if(file) t.image = URL.createObjectURL(file);" class="hidden">
                                        </div>
                                    </div>
                                    <div class="flex-1 grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Nama</label>
                                            <input type="text" :name="'testimonials['+idx+'][name]'" x-model="t.name" class="w-full px-3 py-1.5 bg-white border-none rounded-lg font-black text-[10px]">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Perusahaan</label>
                                            <input type="text" :name="'testimonials['+idx+'][location]'" x-model="t.location" class="w-full px-3 py-1.5 bg-white border-none rounded-lg font-bold text-[9px]">
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Ulasan</label>
                                    <textarea :name="'testimonials['+idx+'][text]'" x-model="t.text" rows="2" class="w-full px-3 py-2 bg-white border-none rounded-xl font-medium text-[9px] leading-relaxed"></textarea>
                                </div>
                                <input type="hidden" :name="'testimonials['+idx+'][image]'" :value="t.image">
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-orange-600 transition-all flex items-center justify-center gap-3">
                <i class="fas fa-rocket"></i> Simpan Publikasi Outbound
            </button>
        </form>
    </div>

    <!-- RIGHT: LIVE PREVIEW -->
    <div class="flex-1 bg-white rounded-[3.5rem] overflow-hidden relative shadow-[0_40px_100px_-20px_rgba(0,0,0,0.5)] border-8 border-slate-900/50 min-h-[600px] xl:h-[85vh] sticky top-8 overflow-y-auto no-scrollbar">
        
                <!-- Live Hero Section -->
                <section x-show="showHero" class="relative h-[550px] flex items-center px-12 md:px-20 overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center transition-all duration-700" :style="`background-image: url('${heroImage}')`"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/60 to-transparent"></div>
                    
                    <div class="relative z-10 max-w-2xl space-y-8">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-px bg-orange-500"></span>
                            <span class="text-orange-400 text-[10px] font-black uppercase tracking-[0.3em]" x-text="heroLabel"></span>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-black text-white leading-[0.95] tracking-tighter" x-text="heroTitle"></h1>
                        <p class="text-slate-300 text-sm font-medium leading-relaxed max-w-lg opacity-90" x-text="heroSubtitle"></p>
                        <div class="pt-4 flex gap-4">
                            <button type="button" class="px-8 py-4 bg-orange-600 text-white rounded-full font-black text-[10px] uppercase tracking-widest shadow-xl shadow-orange-600/20">Our Services</button>
                            <button type="button" class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full font-black text-[10px] uppercase tracking-widest">Contact Expert</button>
                        </div>
                    </div>
                </section>

                <!-- Live Featured Section (Pinned) -->
                <section x-show="showFeatured" class="py-20 px-12 md:px-20 bg-white">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
                        <div class="space-y-4">
                            <div class="text-orange-600 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                                <span class="w-4 h-px bg-orange-600"></span> Pilihan Populer
                            </div>
                            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Program Paling Diminati.</h2>
                        </div>
                        <div class="flex gap-2">
                            <div class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400"><i class="fas fa-chevron-left text-[10px]"></i></div>
                            <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center shadow-lg"><i class="fas fa-chevron-right text-[10px]"></i></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group relative aspect-[4/5] rounded-[2.5rem] bg-slate-100 overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1526772662000-3f88f10405ff?w=600" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                            <div class="absolute bottom-8 left-8 right-8 text-white">
                                <div class="px-3 py-1 bg-orange-600 rounded-lg text-[8px] font-black uppercase tracking-widest inline-block mb-3">2D1N Package</div>
                                <h3 class="text-xl font-black mb-2">Team Building Excellence</h3>
                                <p class="text-slate-300 text-[10px] font-medium opacity-80">Pelatihan intensif di tepi Danau Toba untuk meningkatkan sinergi tim.</p>
                            </div>
                        </div>
                        <div class="group relative aspect-[4/5] rounded-[2.5rem] bg-slate-100 overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1473448912268-2022ce9509d8?w=600" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                            <div class="absolute bottom-8 left-8 right-8 text-white">
                                <div class="px-3 py-1 bg-amber-500 rounded-lg text-[8px] font-black uppercase tracking-widest inline-block mb-3">Full Day</div>
                                <h3 class="text-xl font-black mb-2">Adventure Survival</h3>
                                <p class="text-slate-300 text-[10px] font-medium opacity-80">Tantangan luar ruang yang memicu kreativitas dan pengambilan keputusan.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Live About Section -->
                <section x-show="showAbout" class="py-20 px-12 md:px-20 bg-slate-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                        <div class="space-y-6">
                            <div class="text-orange-600 text-[10px] font-black uppercase tracking-widest">Core Concept</div>
                            <h2 class="text-3xl font-black text-slate-900 tracking-tight" x-text="aboutTitle"></h2>
                            <p class="text-slate-500 text-sm font-medium leading-relaxed" x-text="aboutDesc"></p>
                            
                            <div class="grid grid-cols-2 gap-6 pt-4">
                                <div class="p-5 bg-white rounded-3xl shadow-sm border border-slate-100">
                                    <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center mb-4">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <div class="text-[10px] font-black text-slate-900 uppercase mb-1">Target Oriented</div>
                                    <div class="text-[9px] font-bold text-slate-400 leading-tight">Program terukur untuk hasil nyata tim.</div>
                                </div>
                                <div class="p-5 bg-white rounded-3xl shadow-sm border border-slate-100">
                                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                                        <i class="fas fa-shield-halved"></i>
                                    </div>
                                    <div class="text-[10px] font-black text-slate-900 uppercase mb-1">Safety First</div>
                                    <div class="text-[9px] font-bold text-slate-400 leading-tight">Protokol keamanan standar internasional.</div>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="aspect-square rounded-[3rem] bg-slate-200 shadow-2xl overflow-hidden relative group">
                                <img :src="aboutImage1" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 bg-white rounded-full flex items-center justify-center text-orange-600 shadow-2xl">
                                    <i class="fas fa-play ml-1"></i>
                                </div>
                            </div>
                            <div class="absolute -bottom-8 -left-8 w-48 h-48 rounded-[2.5rem] border-8 border-slate-50 bg-slate-100 overflow-hidden shadow-2xl">
                                <img :src="aboutImage2" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Live Services Preview -->
                <section x-show="showServices" class="relative py-24 px-12 md:px-20 overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center" :style="`background-image: url('${servicesBg}')`"></div>
                    <div class="absolute inset-0 bg-emerald-950/80 backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center max-w-2xl mx-auto space-y-6">
                        <div class="text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em]">Comprehensive Solutions</div>
                        <h2 class="text-3xl font-black text-white">Layanan Corporate Outbound Lengkap</h2>
                        <p class="text-emerald-100/60 text-sm font-medium">Dari gathering perusahaan hingga pelatihan kepemimpinan tingkat lanjut.</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-8">
                            <div class="p-4 bg-white/10 border border-white/10 rounded-2xl backdrop-blur-md">
                                <i class="fas fa-users-gear text-emerald-400 mb-3"></i>
                                <div class="text-[9px] font-black text-white uppercase">Team Bonding</div>
                            </div>
                            <div class="p-4 bg-white/10 border border-white/10 rounded-2xl backdrop-blur-md">
                                <i class="fas fa-mountain text-emerald-400 mb-3"></i>
                                <div class="text-[9px] font-black text-white uppercase">Adventure</div>
                            </div>
                            <div class="p-4 bg-white/10 border border-white/10 rounded-2xl backdrop-blur-md">
                                <i class="fas fa-graduation-cap text-emerald-400 mb-3"></i>
                                <div class="text-[9px] font-black text-white uppercase">Workshop</div>
                            </div>
                            <div class="p-4 bg-white/10 border border-white/10 rounded-2xl backdrop-blur-md">
                                <i class="fas fa-handshake text-emerald-400 mb-3"></i>
                                <div class="text-[9px] font-black text-white uppercase">Coaching</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Live Specialist Preview -->
                <section x-show="showSpecialist" class="py-20 px-12 md:px-20 bg-white">
                    <div class="flex flex-col md:flex-row items-center gap-12 p-10 bg-slate-900 rounded-[3rem] overflow-hidden relative group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
                        <div class="relative w-40 h-40 rounded-full border-4 border-white/10 overflow-hidden flex-shrink-0">
                            <img :src="specialist_image" class="w-full h-full object-cover">
                        </div>
                        <div class="relative space-y-4">
                            <div class="text-orange-500 text-[10px] font-black uppercase tracking-widest">Connect with our Expert</div>
                            <h2 class="text-2xl font-black text-white" x-text="`Halo, Saya ${specialist_name}`"></h2>
                            <p class="text-slate-400 text-sm font-medium leading-relaxed" x-text="specialist_desc"></p>
                            <div class="flex items-center gap-4 pt-4">
                                <button type="button" class="px-6 py-3 bg-orange-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest">Chat on WhatsApp</button>
                                <div class="text-[10px] font-bold text-slate-500" x-text="specialist_title"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Live Testimonials Preview -->
                <section x-show="showTestimonials" class="py-20 px-12 md:px-20 bg-slate-50">
                    <div class="text-center space-y-4 mb-16">
                        <div class="text-orange-600 text-[10px] font-black uppercase tracking-widest">Client Stories</div>
                        <h2 class="text-3xl font-black text-slate-900">Apa Kata Mereka?</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <template x-for="(t, idx) in testimonials.slice(0, 2)" :key="idx">
                            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6 relative">
                                <i class="fas fa-quote-right absolute top-10 right-10 text-slate-50 text-5xl"></i>
                                <p class="text-slate-600 text-sm font-medium leading-relaxed relative z-10" x-text="t.text"></p>
                                <div class="flex items-center gap-4 pt-6 border-t border-slate-50">
                                    <img :src="t.image" class="w-12 h-12 rounded-2xl object-cover bg-slate-100">
                                    <div>
                                        <div class="text-[11px] font-black text-slate-900" x-text="t.name"></div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase" x-text="t.location"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                <!-- Live CTA Section -->
                <section x-show="showCta" class="py-24 px-12 md:px-20 text-center space-y-10">
                    <div class="max-w-3xl mx-auto space-y-6">
                        <h2 class="text-4xl font-black text-slate-900 leading-[1.1]" x-text="$settings['cta_footer_title'] || 'Siap Memberikan Pengalaman Terbaik Untuk Tim Anda?'"></h2>
                        <p class="text-slate-500 text-sm font-medium">Hubungi konsultan outbound kami sekarang untuk mendapatkan penawaran spesial sesuai kebutuhan perusahaan Anda.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <button type="button" class="px-10 py-5 bg-slate-900 text-white rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl" x-text="$settings['cta_footer_btn'] || 'Konsultasi Sekarang'"></button>
                        <button type="button" class="px-10 py-5 bg-white border border-slate-200 text-slate-900 rounded-full font-black text-[10px] uppercase tracking-widest">Download Company Profile</button>
                    </div>
                </section>

        <!-- Overlay Status -->
        <div class="absolute top-6 right-6 z-50 flex items-center gap-3">
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-600 text-white text-[8px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-orange-600/20">
                <span class="w-1 h-1 rounded-full bg-white animate-pulse"></span> Outbound Sync
            </div>
            <div class="px-3 py-1.5 bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest rounded-lg">B2B Preview</div>
        </div>
    </div>
</div>
<style>
    [x-cloak] { display: none !important; }
    textarea { resize: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endsection

@php
    $testimonialsFallback = [
        ['name' => 'Bambang Heru', 'location' => 'HRD PT. Maju Bersama', 'text' => 'Program outbound yang luar biasa! Tim kami jadi lebih solid dan semangat bekerja meningkat drastis.', 'image' => imageUrl('https://i.pravatar.cc/100?u=user3')],
        ['name' => 'Siska Amelia', 'location' => 'Manager Bank Mandiri', 'text' => 'Instruktur sangat profesional dan materi team building dikemas dengan sangat menarik.', 'image' => imageUrl('https://i.pravatar.cc/100?u=user4')]
    ];
    
    // Ensure all testimonial images are resolved
    $testimonials = $settings['testimonials'] ?? $testimonialsFallback;
    foreach ($testimonials as &$t) {
        $t['image'] = imageUrl($t['image']);
    }
    
    $specialistImage = imageUrl($settings['specialist_image_url'] ?? 'https://i.pravatar.cc/100?u=staff1');
@endphp

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cmsOutboundHandler', () => ({
            activeTab: 'hero',
            heroTitle: @json($settings['hero_title'] ?? 'Transformasikan Tim Anda Melalui Pengalaman Luar Ruang Terbaik.'),
            heroSubtitle: @json($settings['hero_subtitle'] ?? 'Solusi Corporate Outbound terpercaya di Sumatera Utara. Kami menghadirkan program yang dirancang khusus untuk meningkatkan sinergi, kepemimpinan, dan produktivitas karyawan Anda.'),
            heroImage: @json(imageUrl($settings['hero_image_url'] ?? 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=1200')),
            heroLabel: @json($settings['hero_label'] ?? 'OUTBOUND & EVENT'),
            servicesBg: @json(imageUrl($settings['services_bg_url'] ?? 'https://images.unsplash.com/photo-1551818255-e6e10975bc17?w=1200')),
            
            // About Section Preview
            aboutTitle: @json($settings['about_hero_title'] ?? 'Apa itu Outbound?'),
            aboutDesc: @json($settings['about_hero_desc'] ?? 'Outbound adalah metode pembelajaran berbasis pengalaman di alam terbuka yang dirancang untuk membangun karakter, kepemimpinan, dan kerjasama tim secara efektif.'),
            aboutImage1: @json(imageUrl($settings['about_image_1_url'] ?? 'https://images.unsplash.com/photo-1551818255-e6e10975bc17?w=600')),
            aboutImage2: @json(imageUrl($settings['about_image_2_url'] ?? 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600')),
            
            specialist_name: @json($settings['specialist_name'] ?? 'Sarah Anggraini'),
            specialist_title: @json($settings['specialist_title'] ?? 'Corporate Account Manager'),
            specialist_desc: @json($settings['specialist_desc'] ?? 'Ingin konsultasi program outbound custom untuk perusahaan Anda? Saya siap membantu.'),
            specialist_image: @json($specialistImage),
            
            testimonials: @json($testimonials),

            // Visibility Toggles
            showHero: @json($settings['show_hero'] ?? true),
            showFeatured: @json($settings['show_featured'] ?? true),
            showAbout: @json($settings['show_about'] ?? true),
            showSpecialist: @json($settings['show_specialist'] ?? true),
            showTestimonials: @json($settings['show_testimonials'] ?? true),
            showServices: @json($settings['show_services'] ?? true),
            showValues: @json($settings['show_values'] ?? true),
            showCta: @json($settings['show_cta'] ?? true),

            addTestimonial() {
                this.testimonials.push({
                    name: 'Nama Klien',
                    location: 'Perusahaan/Kota',
                    text: 'Tulis ulasan klien di sini...',
                    image: 'https://i.pravatar.cc/100?u=' + Math.random()
                });
            },

            removeTestimonial(idx) {
                if (confirm('Hapus ulasan ini?')) {
                    this.testimonials.splice(idx, 1);
                }
            },

            updatePreview(e, target) {
                const file = e.target.files[0];
                if (file) {
                    this[target] = URL.createObjectURL(file);
                }
            },

            openMedia(target, idx = null) {
                window.dispatchEvent(new CustomEvent('open-media-picker', {
                    detail: {
                        callback: (item) => {
                            // Unified Path Logic matching ImageHelper.php
                            let path = item.path;
                            if (path.startsWith('/storage/')) path = path.replace('/storage/', '');
                            if (path.startsWith('storage/')) path = path.replace('storage/', '');
                            
                            const finalUrl = '/storage/' + path;

                            if (target === 'specialist_image') {
                                this.specialist_image = finalUrl;
                            } else if (target === 'hero_image_url') {
                                this.heroImage = finalUrl;
                            } else if (target === 'services_bg_url') {
                                this.servicesBg = finalUrl;
                            } else if (target === 'about_image_1_url') {
                                this.aboutImage1 = finalUrl;
                            } else if (target === 'about_image_2_url') {
                                this.aboutImage2 = finalUrl;
                            } else if (target === 'testimonial' && idx !== null) {
                                this.testimonials[idx].image = finalUrl;
                            }
                        }
                    }
                }));
            }
        }));
    });
</script>
@endpush
