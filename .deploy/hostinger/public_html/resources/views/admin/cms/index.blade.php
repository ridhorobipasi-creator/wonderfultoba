@extends('admin.layout')

@section('title', 'CMS Halaman Utama')
@section('page-title', 'CMS Halaman Utama')

@section('content')
@php
    $resolve = function($path, $default = '') {
        return imageUrl($path, $default);
    };
    $slides = $settings['homepage_slides'] ?? [];
    foreach ($slides as &$slide) {
        if (!empty($slide['image_url'])) {
            $slide['image_url'] = $resolve($slide['image_url']);
        }
    }
    
    $testimonials = $settings['testimonials'] ?? [];
@endphp

<div x-cloak x-data="cmsLandingHandler()" class="flex flex-col xl:flex-row gap-8 min-h-[85vh]">

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cmsLandingHandler', () => ({
        activeTab: 'slider',
        slides: @json($slides),
        activeSlideIdx: 0,
        
        testimonials: @json($testimonials),
        
        metaTitle: @json($settings['meta_title'] ?? 'Sujailake Toba | Paket Wisata Danau Toba Premium'),
        metaDescription: @json($settings['meta_description'] ?? 'Agen perjalanan wisata terpercaya untuk eksplorasi Danau Toba, Samosir, dan Sumatera Utara.'),
        
        specialist_image: @json($settings['specialist_image_url'] ?? ''),

        addSlide() {
            this.slides.push({
                type: 'manual',
                title: 'Slide Baru',
                subtitle: 'Deskripsi slide baru Anda di sini.',
                image_url: '',
                cta_text: 'Explore Now',
                cta_link: '/packages',
                location: 'Danau Toba',
                price: '0',
                duration: '3 Hari'
            });
            this.activeSlideIdx = this.slides.length - 1;
        },
        
        removeSlide(idx) {
            if (confirm('Hapus slide ini?')) {
                this.slides.splice(idx, 1);
                if (this.activeSlideIdx >= this.slides.length) {
                    this.activeSlideIdx = Math.max(0, this.slides.length - 1);
                }
            }
        },

        importFromPackage(pkg) {
            this.slides[this.activeSlideIdx] = {
                ...this.slides[this.activeSlideIdx],
                type: 'package',
                id: pkg.id,
                title: pkg.name,
                subtitle: pkg.shortDescription,
                image_url: pkg.image_path || '',
                cta_link: '/package/' + (pkg.slug || pkg.id),
                location: pkg.locationTag || 'Danau Toba',
                price: pkg.price ? pkg.price.toString() : '0',
                duration: pkg.duration || ''
            };
        },

        importFromBlog(blog) {
            this.slides[this.activeSlideIdx] = {
                ...this.slides[this.activeSlideIdx],
                type: 'blog',
                id: blog.id,
                title: blog.title,
                subtitle: blog.shortDescription,
                image_url: blog.thumbnail,
                cta_link: '/blog/' + (blog.slug || blog.id),
                location: 'Blog Post',
                price: '0',
                duration: ''
            };
        },

        handleSlideImage(e, index) {
            const file = e.target.files[0];
            if (file) {
                this.slides[index].image_url = URL.createObjectURL(file);
            }
        },

        addTestimonial() {
            this.testimonials.push({
                name: 'Nama Pelanggan',
                location: 'Jakarta, Indonesia',
                text: 'Ulasan pelanggan...',
                image: ''
            });
        },

        removeTestimonial(idx) {
            if (confirm('Hapus ulasan ini?')) {
                this.testimonials.splice(idx, 1);
            }
        },

        openMedia(target, idx = null) {
            window.dispatchEvent(new CustomEvent('open-media-picker', { 
                detail: { 
                    callback: (item) => {
                        let path = item.path;
                        if (path.startsWith('/storage/')) path = path.replace('/storage/', '');
                        if (path.startsWith('storage/')) path = path.replace('storage/', '');
                        
                        if (target === 'slider') {
                            this.slides[this.activeSlideIdx].image_url = path;
                        } else if (target === 'specialist') {
                            this.specialist_image = path;
                        } else if (target === 'testimonial' && idx !== null) {
                            this.testimonials[idx].image = path;
                        } else if (target.startsWith('why_image_')) {
                            const preview = document.getElementById('preview_' + target);
                            const empty = document.getElementById('empty_' + target);
                            const input = document.getElementById('input_' + target);
                            if (preview) {
                                preview.src = '/storage/' + path;
                                preview.classList.remove('hidden');
                            }
                            if (empty) empty.classList.add('hidden');
                            if (input) input.value = path;
                        }
                    } 
                } 
            }));
        },

        fixPath(path) {
            if (!path) return '';
            if (path.startsWith('http') || path.startsWith('blob:') || path.startsWith('data:')) return path;
            return '/storage/' + path.replace(/^\/?storage\//, '').replace(/^\//, '');
        }
    }));
});
</script>
@endpush
    
    <!-- LEFT: CONTROL PANEL (Form) -->
    <div class="w-full xl:w-[450px] flex-shrink-0 space-y-6">
        <div class="bg-white p-2 rounded-[2rem] shadow-sm border border-slate-100 flex items-center overflow-x-auto no-scrollbar">
            <button @click="activeTab = 'slider'" :class="activeTab === 'slider' ? 'bg-lake-blue text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-6 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">📸 Slider</button>
            <button @click="activeTab = 'stats'" :class="activeTab === 'stats' ? 'bg-lake-blue text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-6 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">📊 Stats</button>
            <button @click="activeTab = 'specialist'" :class="activeTab === 'specialist' ? 'bg-lake-blue text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-6 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">👤 Specialist</button>
            <button @click="activeTab = 'testimonials'" :class="activeTab === 'testimonials' ? 'bg-lake-blue text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-6 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">💬 Ulasan</button>
            <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="px-6 py-3 rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">SEO</button>
        </div>

        <form action="{{ route('admin.cms.save', 'cms_landing') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm overflow-hidden">
                
                <!-- Slider Tab -->
                <div x-show="activeTab === 'slider'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-lake-blue uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-lake-blue"></span> Hero Slider Utama
                        </h4>
                        <button type="button" @click="addSlide()" class="px-4 py-2 bg-lake-blue text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-lake-blue/20 hover:scale-105 transition-all">
                            + Tambah Slide
                        </button>
                    </div>

                    <div class="space-y-6 max-h-[60vh] overflow-y-auto pr-2 no-scrollbar">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div class="bg-slate-50 rounded-3xl p-5 border border-slate-100 relative group">
                                <button type="button" @click="removeSlide(index)" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-white text-rose-500 shadow-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                    <i class="fas fa-trash-can text-[10px]"></i>
                                </button>

                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-black text-[10px]" x-text="index + 1"></div>
                                        <select x-model="slide.type" class="flex-1 bg-white border-none rounded-xl font-black text-[10px] uppercase tracking-widest p-2">
                                            <option value="manual">Manual / Custom</option>
                                            <option value="package">Paket Tour</option>
                                            <option value="blog">Artikel Blog</option>
                                        </select>
                                    </div>

                                    <!-- Pickers -->
                                    <div x-show="slide.type === 'package'" class="bg-white p-3 rounded-2xl space-y-2 border border-slate-100">
                                        <label class="text-[8px] font-black text-lake-blue uppercase tracking-widest">Pilih Paket</label>
                                        <div class="max-h-[150px] overflow-y-auto no-scrollbar grid grid-cols-1 gap-1">
                                            @foreach($packages as $p)
                                            <button type="button" @click="importFromPackage({
                                                id: {{ $p->id }},
                                                name: '{{ addslashes($p->name) }}',
                                                shortDescription: '{{ addslashes($p->shortDescription) }}',
                                                slug: '{{ $p->slug }}',
                                                locationTag: '{{ addslashes($p->locationTag) }}',
                                                price: {{ $p->price }},
                                                duration: '{{ $p->duration }}',
                                                image_path: '{{ $p->packageImages->first()?->image_path ?? ($p->images[0] ?? '') }}'
                                            })" class="flex items-center gap-2 p-2 rounded-xl hover:bg-lake-blue/5 transition text-left">
                                                <img src="{{ imageUrl($p->packageImages->first()?->image_path ?? ($p->images[0] ?? '')) }}" class="w-8 h-8 rounded-lg object-cover">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-[9px] font-black truncate">{{ $p->name }}</p>
                                                    <p class="text-[7px] font-bold text-slate-400">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                                                </div>
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="w-full h-32 rounded-2xl bg-white border border-slate-200 overflow-hidden relative group/img">
                                        <img :src="fixPath(slide.image_url) || 'https://via.placeholder.com/800x400?text=Pilih+Gambar'" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                            <button type="button" @click="activeSlideIdx = index; openMedia('slider')" class="px-3 py-1.5 bg-white text-slate-900 rounded-lg font-black text-[8px] uppercase tracking-widest">
                                                Pilih dari Galeri
                                            </button>
                                            <div class="relative">
                                                <input type="file" :name="'homepage_slides['+index+'][image_file]'" @change="handleSlideImage($event, index)" class="absolute inset-0 opacity-0 cursor-pointer">
                                                <button type="button" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg font-black text-[8px] uppercase tracking-widest">Upload</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Judul</label>
                                            <input type="text" x-model="slide.title" class="w-full px-3 py-2 bg-white rounded-xl border-none font-black text-[10px]">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Lokasi</label>
                                            <input type="text" x-model="slide.location" class="w-full px-3 py-2 bg-white rounded-xl border-none font-bold text-[10px]">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Sub-Judul</label>
                                        <textarea x-model="slide.subtitle" rows="2" class="w-full px-3 py-2 bg-white rounded-xl border-none font-medium text-[9px] text-slate-500"></textarea>
                                    </div>

                                    <!-- Hidden Inputs -->
                                    <input type="hidden" :name="'homepage_slides['+index+'][title]'" :value="slide.title">
                                    <input type="hidden" :name="'homepage_slides['+index+'][subtitle]'" :value="slide.subtitle">
                                    <input type="hidden" :name="'homepage_slides['+index+'][image_url]'" :value="slide.image_url">
                                    <input type="hidden" :name="'homepage_slides['+index+'][location]'" :value="slide.location">
                                    <input type="hidden" :name="'homepage_slides['+index+'][price]'" :value="slide.price">
                                    <input type="hidden" :name="'homepage_slides['+index+'][duration]'" :value="slide.duration">
                                    <input type="hidden" :name="'homepage_slides['+index+'][cta_link]'" :value="slide.cta_link">
                                    <input type="hidden" :name="'homepage_slides['+index+'][cta_text]'" :value="slide.cta_text">
                                    <input type="hidden" :name="'homepage_slides['+index+'][type]'" :value="slide.type">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>


                <!-- Stats Tab -->
                <div x-show="activeTab === 'stats'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-lake-blue uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-lake-blue"></span> Statistik Counter
                    </h4>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach(range(0, 3) as $idx)
                        <div class="bg-slate-50 p-4 rounded-2xl space-y-2">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Angka</label>
                                    <input type="text" name="stat_value_{{ $idx }}" value="{{ $settings['stat_value_'.$idx] ?? '0' }}" class="w-full px-4 py-2 bg-white border-none rounded-xl font-black text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Label</label>
                                    <input type="text" name="stat_label_{{ $idx }}" value="{{ $settings['stat_label_'.$idx] ?? 'Item' }}" class="w-full px-4 py-2 bg-white border-none rounded-xl font-bold text-xs">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Specialist Tab -->
                <div x-show="activeTab === 'specialist'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-lake-blue uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-lake-blue"></span> Travel Specialist
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-5 bg-slate-50 rounded-3xl">
                            <div class="relative group cursor-pointer w-20 h-20 rounded-full overflow-hidden border-2 border-white shadow-lg shrink-0">
                                <img :src="fixPath(specialist_image) || 'https://via.placeholder.com/100'" class="w-full h-full object-cover">
                                <div @click="openMedia('specialist')" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <i class="fas fa-camera text-white text-xs"></i>
                                </div>
                                <input type="hidden" name="specialist_image_url" :value="specialist_image">
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="text" name="specialist_name" value="{{ $settings['specialist_name'] ?? '' }}" placeholder="Nama Specialist..." class="w-full px-4 py-2 bg-white border-none rounded-xl font-black text-xs">
                                <input type="text" name="specialist_title" value="{{ $settings['specialist_title'] ?? '' }}" placeholder="Jabatan..." class="w-full px-4 py-2 bg-white border-none rounded-xl font-bold text-[10px] text-slate-500">
                            </div>
                        </div>
                        <textarea name="specialist_desc" rows="4" placeholder="Pesan sapaan..." class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl font-medium text-xs leading-relaxed">{{ $settings['specialist_desc'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Testimonials Tab -->
                <div x-show="activeTab === 'testimonials'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-lake-blue uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-lake-blue"></span> Ulasan Pengunjung
                        </h4>
                        <button type="button" @click="addTestimonial()" class="px-4 py-2 bg-lake-blue text-white rounded-xl font-black text-[9px] uppercase tracking-widest">
                            + Tambah
                        </button>
                    </div>
                    <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-2 no-scrollbar">
                        <template x-for="(t, idx) in testimonials" :key="idx">
                            <div class="p-5 bg-slate-50 rounded-3xl space-y-4 relative group">
                                <button type="button" @click="removeTestimonial(idx)" class="absolute top-4 right-4 text-rose-500 opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-trash-can text-xs"></i>
                                </button>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-100 overflow-hidden shrink-0 relative group/av cursor-pointer">
                                        <img :src="fixPath(t.image) || 'https://via.placeholder.com/50'" class="w-full h-full object-cover">
                                        <div @click="openMedia('testimonial', idx)" class="absolute inset-0 bg-black/40 opacity-0 group-hover/av:opacity-100 transition flex items-center justify-center">
                                            <i class="fas fa-camera text-white text-[8px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <input type="text" :name="'testimonials['+idx+'][name]'" x-model="t.name" class="w-full px-3 py-1.5 bg-white border-none rounded-lg font-black text-[10px]">
                                        <input type="text" :name="'testimonials['+idx+'][location]'" x-model="t.location" class="w-full px-3 py-1.5 bg-white border-none rounded-lg font-bold text-[9px] text-slate-400">
                                    </div>
                                </div>
                                <textarea :name="'testimonials['+idx+'][text]'" x-model="t.text" rows="2" class="w-full px-3 py-2 bg-white border-none rounded-xl font-medium text-[9px] leading-relaxed"></textarea>
                                <input type="hidden" :name="'testimonials['+idx+'][image]'" :value="t.image">
                            </div>
                        </template>
                    </div>
                </div>

                <!-- SEO Tab -->
                <div x-show="activeTab === 'seo'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-indigo-600 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span> SEO Meta Tag
                    </h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Meta Title</label>
                            <input type="text" name="meta_title" x-model="metaTitle" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Meta Description</label>
                            <textarea name="meta_description" x-model="metaDescription" rows="5" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-medium text-xs leading-relaxed"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-lake-blue transition-all flex items-center justify-center gap-3">
                <i class="fas fa-cloud-arrow-up"></i> Simpan Publikasi
            </button>
        </form>
    </div>

    <!-- RIGHT: PREVIEW -->
    <div class="flex-1 bg-white rounded-[3.5rem] overflow-hidden relative shadow-[0_40px_100px_-20px_rgba(0,0,0,0.5)] border-8 border-slate-900/50 min-h-[600px] xl:h-[85vh] sticky top-8 overflow-y-auto no-scrollbar pb-20">
        
        <!-- Live Slider Preview -->
        <div class="bg-slate-950 h-[500px] relative overflow-hidden">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlideIdx === index" x-transition.opacity.duration.800ms class="absolute inset-0">
                    <img :src="fixPath(slide.image_url) || 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=1200'" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/20 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-center p-16">
                        <span class="text-lake-light text-[10px] font-black uppercase tracking-widest mb-4 block" x-text="slide.location"></span>
                        <h2 class="text-4xl md:text-5xl font-black text-white leading-none tracking-tighter uppercase mb-6" x-text="slide.title"></h2>
                        <p class="text-slate-300 text-xs max-w-sm opacity-80 mb-8" x-text="slide.subtitle"></p>
                        <div class="flex items-center gap-6">
                            <div class="px-8 py-4 bg-lake-blue text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl" x-text="slide.cta_text || 'Explore Now'"></div>
                        </div>
                    </div>
                </div>
            </template>
            
            <div class="absolute bottom-10 left-16 flex gap-2">
                <template x-for="(slide, index) in slides" :key="'dot-'+index">
                    <button @click="activeSlideIdx = index" :class="activeSlideIdx === index ? 'w-8 bg-lake-blue' : 'w-2 bg-white/20'" class="h-1.5 rounded-full transition-all"></button>
                </template>
            </div>
        </div>

        <!-- Section Info -->
        <div class="p-12 space-y-20">
            <div class="text-center">
                <p class="text-lake-blue font-black text-[10px] uppercase tracking-widest mb-2">Live Sections Preview</p>
                <h3 class="text-2xl font-black text-slate-900">Konten Halaman Utama</h3>
            </div>

            <!-- Stats Preview -->
            <div class="grid grid-cols-4 gap-8">
                @foreach(range(0, 3) as $i)
                <div class="text-center">
                    <p class="text-3xl font-black text-slate-900">{{ $settings['stat_value_'.$i] ?? '0' }}</p>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $settings['stat_label_'.$i] ?? 'Label' }}</p>
                </div>
                @endforeach
            </div>

            <!-- Specialist Preview -->
            <div class="bg-slate-50 rounded-[3rem] p-10 flex flex-col md:flex-row items-center gap-10">
                <img :src="fixPath(specialist_image) || 'https://via.placeholder.com/300'" class="w-48 h-48 rounded-[2rem] object-cover shadow-2xl">
                <div class="flex-1 space-y-4">
                    <div class="space-y-1">
                        <p class="text-xs font-black text-slate-900">{{ $settings['specialist_name'] ?? 'Sarah' }}</p>
                        <p class="text-[9px] font-bold text-lake-blue uppercase tracking-widest">{{ $settings['specialist_title'] ?? 'Travel Specialist' }}</p>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed italic">"{{ $settings['specialist_desc'] ?? '...' }}"</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    textarea { resize: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endsection
