@extends('admin.layout')

@section('title', 'CMS Beranda Tour')
@section('page-title', 'CMS Beranda Tour')

@section('content')
@php
    $resolve = function($path, $default = '') {
        return imageUrl($path, $default);
    };
@endphp

    @php
        $slides = $settings['homepage_slides'] ?? [];
        foreach ($slides as &$slide) {
            if (!empty($slide['image_url'])) {
                $slide['image_url'] = $resolve($slide['image_url']);
            }
        }
    @endphp

<div x-cloak x-data="cmsTourHandler" class="flex flex-col xl:flex-row gap-8 min-h-[85vh]">

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cmsTourHandler', () => ({
        activeTab: 'hero',
        heroTitle: @json($settings['hero_title'] ?? 'Jelajahi Keajaiban Alam Sumatera Utara.'),
        heroSubtitle: @json($settings['hero_subtitle'] ?? 'Dari birunya Danau Toba hingga sejuknya Berastagi, kami siap menemani perjalanan tak terlupakan Anda dengan layanan premium.'),
        heroImage: @json($resolve($settings['hero_image_url'] ?? '', 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1200')),
        ctaText: @json($settings['hero_cta_text'] ?? 'Lihat Paket Wisata'),
        
        slides: @json($slides),
        activeSlideIdx: 0,
        
        addSlide() {
            this.slides.push({
                type: 'manual',
                title: 'Slide Baru',
                subtitle: 'Deskripsi slide baru Anda di sini.',
                image_url: '',
                cta_text: 'Eksplor Sekarang',
                cta_link: '#',
                location: 'Sumatera Utara',
                price: '0',
                duration: '1 Hari'
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
            const currentSlide = this.slides[this.activeSlideIdx];
            this.slides[this.activeSlideIdx] = {
                ...currentSlide,
                type: 'package',
                id: pkg.id,
                title: pkg.name,
                subtitle: pkg.shortDescription,
                image_url: pkg.image_path || '',
                cta_link: '/tour/package/' + (pkg.slug || pkg.id),
                location: pkg.locationTag || 'Sumatera Utara',
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
                cta_link: '/tour/blog/' + (blog.slug || blog.id),
                location: 'Blog Post',
                price: '0',
                duration: ''
            };
        },

        importFromGallery(img) {
            this.slides[this.activeSlideIdx] = {
                ...this.slides[this.activeSlideIdx],
                type: 'gallery',
                id: img.id,
                title: img.title || 'Wonderful Toba Gallery',
                subtitle: img.description || '',
                image_url: img.image_path,
                cta_link: '#',
                location: 'Gallery',
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
        
        stat0: @json($settings['stat_value_0'] ?? '1.5K+'),
        label0: @json($settings['stat_label_0'] ?? 'Trip Selesai'),
        stat1: @json($settings['stat_value_1'] ?? '10K+'),
        label1: @json($settings['stat_label_1'] ?? 'Wisatawan'),
        stat2: @json($settings['stat_value_2'] ?? '50+'),
        label2: @json($settings['stat_label_2'] ?? 'Destinasi'),
        stat3: @json($settings['stat_value_3'] ?? '15+'),
        label3: @json($settings['stat_label_3'] ?? 'Penghargaan'),
        
        fixPath(path) {
            if (!path) return '';
            if (path.startsWith('http') || path.startsWith('blob:') || path.startsWith('data:')) return path;
            
            let clean = path.replace(/^\/?storage\//, '').replace(/^\//, '');
            
            // In this project, even 'assets/' is inside 'storage/'
            return '/storage/' + clean;
        },

        updatePreview(e) {
            const file = e.target.files[0];
            if (file) {
                this.heroImage = URL.createObjectURL(file);
            }
        },

        openMedia(target, idx = null) {
            window.dispatchEvent(new CustomEvent('open-media-picker', { 
                detail: { 
                    callback: (item) => {
                        this.setMedia(item, target, idx);
                    }
                } 
            }));
        },

        specialist_name: @json($settings['specialist_name'] ?? 'Sarah Anggraini'),
        specialist_title: @json($settings['specialist_title'] ?? 'Travel Specialist'),
        specialist_desc: @json($settings['specialist_desc'] ?? 'Punya pertanyaan khusus? Saya siap membantu merencanakan liburan impian Anda.'),
        specialist_image: @json($resolve($settings['specialist_image_url'] ?? '', 'https://i.pravatar.cc/100?u=staff1')),
        
        @php
            $defaultTestimonials = [
                ['name' => 'Andini Wijaya', 'location' => 'Jakarta, Indonesia', 'text' => 'Pelayanan sangat profesional. Tour guide ramah dan sangat menguasai medan. Itinerary juga tidak terlalu padat sehingga kami bisa benar-benar menikmati waktu.', 'image' => 'https://i.pravatar.cc/100?u=user1'],
                ['name' => 'Budi Santoso', 'location' => 'Surabaya, Indonesia', 'text' => 'Sangat puas dengan pilihan hotel dan restorannya. Wonderful Toba benar-benar kurasi yang terbaik untuk tamunya. Highly recommended!', 'image' => 'https://i.pravatar.cc/100?u=user2']
            ];
        @endphp
        testimonials: (() => {
            let t = @json($settings['testimonials'] ?? $defaultTestimonials);
            while(t.length < 4) t.push({name: '', location: '', text: '', image: ''});
            return t;
        })(),

        addTestimonial() {
            this.testimonials.push({
                name: 'Nama Pengunjung',
                location: 'Kota, Negara',
                text: 'Tulis ulasan pengunjung di sini...',
                image: 'https://i.pravatar.cc/100?u=' + Math.random()
            });
        },

        removeTestimonial(idx) {
            if (confirm('Hapus ulasan ini?')) {
                this.testimonials.splice(idx, 1);
            }
        },

        setMedia(item, target, idx = null) {
            let path = item.path;
            if (path.startsWith('/storage/')) path = path.replace('/storage/', '');
            if (path.startsWith('storage/')) path = path.replace('storage/', '');
            
            const finalUrl = '/storage/' + path;

            if (target === 'hero') {
                this.heroImage = finalUrl;
                if (this.$refs.heroUrl) this.$refs.heroUrl.value = path;
            } else if (target === 'slider') {
                this.slides[this.activeSlideIdx].image_url = path;
            } else if (target === 'specialist') {
                this.specialist_image = finalUrl;
                document.querySelector('input[name="specialist_image_url"]').value = path;
            } else if (target === 'why_image_1' || target === 'why_image_2' || target === 'why_image_3') {
                const preview = document.getElementById('preview_' + target);
                const empty = document.getElementById('empty_' + target);
                const input = document.getElementById('input_' + target);
                
                if (preview) {
                    preview.src = finalUrl;
                    preview.classList.remove('hidden');
                }
                if (empty) empty.classList.add('hidden');
                if (input) input.value = path;
            } else if (target === 'testimonial' && idx !== null) {
                this.testimonials[idx].image = path;
            }
        }
    }));
});
</script>
@endpush
    
    <!-- LEFT: CONTROL PANEL -->
    <div class="w-full xl:w-[450px] flex-shrink-0 space-y-6">
        <!-- Tab Navigation -->
        <div class="bg-white p-2 rounded-[2rem] shadow-sm border border-slate-100 flex items-center space-x-1 overflow-x-auto no-scrollbar">
            <button type="button" @click="activeTab = 'hero'" :class="activeTab === 'hero' ? 'bg-toba-green text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Hero</button>
            <button type="button" @click="activeTab = 'slider'" :class="activeTab === 'slider' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">📸 Slider</button>
            <button type="button" @click="activeTab = 'featured'" :class="activeTab === 'featured' ? 'bg-amber-500 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">⭐ Featured</button>
            <button type="button" @click="activeTab = 'about'" :class="activeTab === 'about' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Mengapa Kami</button>
            <button type="button" @click="activeTab = 'specialist'" :class="activeTab === 'specialist' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">👩‍💼 Specialist</button>
            <button type="button" @click="activeTab = 'testimonials'" :class="activeTab === 'testimonials' ? 'bg-amber-400 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">💬 Ulasan</button>
            <button @click="activeTab = 'stats'" :class="activeTab === 'stats' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">Statistik</button>
            <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 px-4 py-3 rounded-[1.2rem] font-black text-[8px] uppercase tracking-widest transition-all whitespace-nowrap">SEO</button>
        </div>

        <form action="{{ route('admin.cms.save', 'cms_tour') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm overflow-hidden">
                <!-- Hero Tab -->
                <div x-show="activeTab === 'hero'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-toba-green uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-toba-green"></span> Tampilan Hero Utama
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_hero" value="1" {{ ($settings['show_hero'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-toba-green transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Headline Utama</label>
                            <textarea name="hero_title" x-model="heroTitle" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-black text-sm text-slate-900"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sub-headline</label>
                            <textarea name="hero_subtitle" x-model="heroSubtitle" rows="4" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-600 text-xs leading-relaxed"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tombol Label</label>
                                <input type="text" name="hero_cta_text" x-model="ctaText" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 text-xs">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Target Link</label>
                                <input type="text" name="hero_cta_link" value="{{ $settings['hero_cta_link'] ?? '/packages' }}" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 text-xs">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Background Hero Utama</label>
                            <div class="relative group/hero overflow-hidden rounded-3xl bg-slate-900 aspect-video lg:aspect-[21/9] border-4 border-white shadow-2xl shadow-slate-200">
                                <img :src="heroImage" class="w-full h-full object-cover transition-transform duration-700 group-hover/hero:scale-110">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover/hero:opacity-100 transition-all flex flex-col items-center justify-center gap-3">
                                    <button type="button" @click="openMedia('hero')" class="px-6 py-3 bg-white text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl hover:scale-105 transition-transform flex items-center gap-2">
                                        <i class="fas fa-images"></i> Pilih dari Galeri
                                    </button>
                                    <div class="flex items-center gap-2">
                                        <input type="file" name="hero_image_file" id="hero_image_file" class="hidden" @change="updatePreview($event)">
                                        <button type="button" @click="document.getElementById('hero_image_file').click()" class="px-4 py-2 bg-slate-800 text-white rounded-xl font-black text-[8px] uppercase tracking-widest hover:bg-slate-700 transition-colors">
                                            <i class="fas fa-upload mr-1"></i> Upload File
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="hero_image_url" x-ref="heroUrl" :value="heroImage.replace('/storage/', '').replace(/^\//, '')">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Packages Tab -->
                <div x-show="activeTab === 'featured'" x-transition class="space-y-5">
                    <div>
                        <h4 class="text-sm font-black text-amber-600 uppercase tracking-widest flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Paket Unggulan Homepage
                        </h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pilih maks. 3 paket yang tampil di beranda Tour. Jika kosong, sistem pakai paket bertanda ⭐ Featured.</p>
                    </div>
                    @php
                        $allTourPackages = \App\Models\Package::with('packageImages')
                            ->where('status', 'active')
                            ->where('isOutbound', false)
                            ->orderBy('isFeatured', 'desc')
                            ->orderBy('createdAt', 'desc')
                            ->get();
                        $pinnedIds = $settings['featured_package_ids'] ?? [];
                    @endphp
                    <div class="space-y-3 max-h-[450px] overflow-y-auto pr-1 no-scrollbar">
                        @forelse($allTourPackages as $ap)
                        @php
                            $apImg = $ap->packageImages->first()?->image_url
                                   ?? $ap->resolveImageUrl($ap->images[0] ?? null);
                        @endphp
                        <label class="flex items-center gap-4 p-4 rounded-2xl cursor-pointer transition-all bg-slate-50 hover:bg-amber-50 hover:border hover:border-amber-200">
                            <input type="checkbox" id="pkg_{{ $ap->id }}" name="featured_package_ids[]" value="{{ $ap->id }}"
                                   {{ in_array($ap->id, (array)$pinnedIds) ? 'checked' : '' }}
                                   class="w-4 h-4 accent-amber-500 rounded shrink-0">
                            <img src="{{ imageUrl($apImg) }}" alt="{{ $ap->name }}" class="w-12 h-12 rounded-xl object-cover shrink-0" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="w-12 h-12 rounded-xl bg-slate-200 items-center justify-center shrink-0 hidden">
                                <i class="fas fa-image text-slate-400 text-xs"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-black text-slate-900 text-xs truncate">{{ $ap->name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $ap->locationTag ?? 'Sumatera Utara' }} · Rp {{ number_format($ap->price, 0, ',', '.') }}</p>
                            </div>
                            @if($ap->isFeatured)
                                <span class="text-amber-400 text-xs">⭐</span>
                            @endif
                        </label>
                        @empty
                        <div class="text-center py-8 text-slate-400">
                            <i class="fas fa-box-open text-3xl mb-2"></i>
                            <p class="text-[10px] font-bold uppercase tracking-widest">Belum ada paket aktif</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Why Us Tab -->
                <div x-show="activeTab === 'about'" x-transition class="space-y-6">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-900"></span> Kelebihan Wonderful Toba
                    </h4>
                    <div class="space-y-6 max-h-[500px] overflow-y-auto pr-2 no-scrollbar">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="p-5 bg-slate-50 rounded-3xl space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Kelebihan #{{ $i }}</span>
                                <i class="fas {{ $i == 1 ? 'fa-gem' : ($i == 2 ? 'fa-user-tie' : 'fa-hand-holding-heart') }} text-slate-300"></i>
                            </div>
                            <input type="text" name="about_title_{{ $i }}" value="{{ $settings['about_title_'.$i] ?? 'Layanan Exclusive' }}" class="w-full px-4 py-2.5 bg-white border-none rounded-xl font-black text-xs text-slate-900" placeholder="Judul...">
                            <textarea name="about_desc_{{ $i }}" rows="2" class="w-full px-4 py-2.5 bg-white border-none rounded-xl font-bold text-[10px] text-slate-500 leading-relaxed" placeholder="Deskripsi singkat...">{{ $settings['about_desc_'.$i] ?? 'Kami mengedepankan kenyamanan tamu dengan standar hotel dan armada terbaik.' }}</textarea>
                        </div>
                        @endfor

                        {{-- Gallery Images for "Why Us" section --}}
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">🖼️ Foto Galeri (Tampil di sisi kiri "Mengapa Kami")</p>
                            <div class="space-y-4">
                                @foreach([['why_image_1', 'Foto Utama (Besar, Kiri)'], ['why_image_2', 'Foto Kanan Atas'], ['why_image_3', 'Foto Kanan Bawah']] as [$fieldName, $label])
                                <div class="p-4 bg-slate-50 rounded-2xl space-y-2">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</label>
                                    <div class="relative group cursor-pointer aspect-video rounded-xl bg-white border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center hover:bg-slate-50 transition">
                                        <div class="absolute inset-0 z-10" @click="openMedia('{{ $fieldName }}')"></div>
                                        @if(!empty($settings[$fieldName.'_url']))
                                            <img src="{{ imageUrl($settings[$fieldName.'_url']) }}" class="w-full h-full object-cover" id="preview_{{ $fieldName }}">
                                        @else
                                            <div class="text-center" id="empty_{{ $fieldName }}">
                                                <i class="fas fa-cloud-arrow-up text-slate-300 text-xl mb-1"></i>
                                                <p class="text-[8px] font-bold text-slate-300 uppercase tracking-widest">Pilih dari Galeri</p>
                                            </div>
                                            <img src="" class="w-full h-full object-cover hidden" id="preview_{{ $fieldName }}">
                                        @endif
                                        <input type="hidden" name="{{ $fieldName }}_url" value="{{ $settings[$fieldName.'_url'] ?? '' }}" id="input_{{ $fieldName }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Tab -->
                <div x-show="activeTab === 'stats'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-900"></span> Statistik Wonderful Toba
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_stats" value="1" {{ ($settings['show_stats'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-slate-900 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach(range(0, 3) as $idx)
                        <div class="flex gap-4 items-end bg-slate-50 p-4 rounded-2xl">
                            <div class="flex-1 space-y-2">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Angka</label>
                                <input type="text" name="stat_value_{{ $idx }}" x-model="stat{{ $idx }}" class="w-full px-4 py-2 bg-white border-none rounded-xl font-black text-xs">
                            </div>
                            <div class="flex-[2] space-y-2">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Keterangan</label>
                                <input type="text" name="stat_label_{{ $idx }}" x-model="label{{ $idx }}" class="w-full px-4 py-2 bg-white border-none rounded-xl font-bold text-xs">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Specialist Tab -->
                <div x-show="activeTab === 'specialist'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-emerald-500 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Travel Specialist
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_specialist" value="1" {{ ($settings['show_specialist'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-6 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div class="relative group cursor-pointer w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-xl flex-shrink-0">
                                <img :src="fixPath(specialist_image)" class="w-full h-full object-cover">
                                <div @click="openMedia('specialist')" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="fas fa-camera text-white text-lg"></i>
                                </div>
                                <input type="hidden" name="specialist_image_url" :value="specialist_image">
                            </div>
                            <div class="flex-1 space-y-3">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                                    <input type="text" name="specialist_name" x-model="specialist_name" class="w-full px-4 py-2 bg-white border-none rounded-xl font-black text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Jabatan / Title</label>
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
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-amber-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> Ulasan Pengunjung
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_testimonials" value="1" {{ ($settings['show_testimonials'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-amber-400 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="addTestimonial()" class="px-4 py-2 bg-amber-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-amber-200">
                            + Tambah Ulasan
                        </button>
                    </div>
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 no-scrollbar">
                        <template x-for="(t, idx) in testimonials" :key="idx">
                            <div class="p-5 bg-slate-50 rounded-3xl space-y-4 relative group">
                                <button type="button" @click="removeTestimonial(idx)" class="absolute top-4 right-4 w-6 h-6 rounded-full bg-white text-rose-500 shadow-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-times text-[10px]"></i>
                                </button>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 overflow-hidden shrink-0 relative group/avatar cursor-pointer">
                                        <img :src="fixPath(t.image)" class="w-full h-full object-cover">
                                        <div @click="openMedia('testimonial', idx)" class="absolute inset-0 bg-black/40 opacity-0 group-hover/avatar:opacity-100 transition-opacity flex items-center justify-center">
                                            <i class="fas fa-camera text-white text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Nama</label>
                                            <input type="text" :name="'testimonials['+idx+'][name]'" x-model="t.name" class="w-full px-3 py-1.5 bg-white border-none rounded-lg font-black text-[10px]">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Lokasi</label>
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

                <!-- SEO Tab -->
                <div x-show="activeTab === 'seo'" x-transition class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Optimasi SEO Halaman</h4>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SEO Meta Title</label>
                            <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" placeholder="Wonderful Toba | Paket Wisata Terbaik" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 font-bold text-slate-900 text-xs shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SEO Meta Description</label>
                            <textarea name="meta_description" rows="4" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 font-bold text-slate-600 text-xs leading-relaxed shadow-inner" placeholder="Jelajahi keindahan Danau Toba dengan paket wisata premium kami...">{{ $settings['meta_description'] ?? '' }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SEO Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="wisata toba, tour samosir, travel medan" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 font-bold text-slate-900 text-xs shadow-inner">
                        </div>
                    </div>
                </div>

                <!-- Slider Tab (Now as the Editor) -->
                <div x-show="activeTab === 'slider'" x-transition class="space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-black text-indigo-600 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span> NIF Dynamic Slider
                        </h4>
                        <label class="flex items-center cursor-pointer gap-2">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tampilkan</span>
                            <div class="relative inline-block w-8 h-4">
                                <input type="checkbox" name="show_slider" value="1" {{ ($settings['show_slider'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-full h-full bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="addSlide()" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Slide
                        </button>
                    </div>

                    <div class="space-y-6 max-h-[60vh] overflow-y-auto pr-2 no-scrollbar">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div class="bg-slate-50 rounded-3xl p-5 border border-slate-100 relative group">
                                <button type="button" @click="removeSlide(index)" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-white text-slate-300 hover:text-rose-500 shadow-sm flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-10">
                                    <i class="fas fa-trash-can text-[10px]"></i>
                                </button>

                                <div class="space-y-5">
                                    <!-- Slide Header -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-black text-[10px]" x-text="index + 1"></div>
                                        <select x-model="slide.type" class="flex-1 bg-white border-none rounded-xl font-black text-[10px] uppercase tracking-widest p-2 text-slate-900">
                                            <option value="manual">Manual / Custom</option>
                                            <option value="package">Paket Tour</option>
                                            <option value="blog">Blog Post</option>
                                            <option value="gallery">Galeri Foto</option>
                                        </select>
                                    </div>

                                    <!-- Package Picker -->
                                    <div x-show="slide.type === 'package'" class="space-y-2">
                                        <p class="text-[8px] font-black text-indigo-600 uppercase tracking-widest">Pilih Paket Tour</p>
                                        <div class="grid grid-cols-1 gap-2 max-h-[150px] overflow-y-auto no-scrollbar bg-white p-2 rounded-2xl border border-slate-100">
                                            @foreach($packages as $p)
                                            <button type="button" 
                                                    @click="importFromPackage({
                                                        id: {{ $p->id }},
                                                        name: '{{ addslashes($p->name) }}',
                                                        shortDescription: '{{ addslashes($p->shortDescription) }}',
                                                        slug: '{{ $p->slug }}',
                                                        locationTag: '{{ addslashes($p->locationTag) }}',
                                                        price: {{ $p->price }},
                                                        duration: '{{ $p->duration }}',
                                                        image_path: '{{ $p->packageImages->first()?->image_path ?? ($p->images[0] ?? '') }}'
                                                    })"
                                                    class="flex items-center gap-2 p-2 rounded-xl hover:bg-indigo-50 transition text-left group/pkg">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 overflow-hidden shrink-0">
                                                    <img src="{{ imageUrl($p->packageImages->first()?->image_path ?? ($p->images[0] ?? '')) }}" class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-[9px] font-black text-slate-900 truncate">{{ $p->name }}</p>
                                                    <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                                                </div>
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Blog Picker -->
                                    <div x-show="slide.type === 'blog'" class="space-y-2">
                                        <p class="text-[8px] font-black text-orange-600 uppercase tracking-widest">Pilih Artikel Blog</p>
                                        <div class="grid grid-cols-1 gap-2 max-h-[150px] overflow-y-auto no-scrollbar bg-white p-2 rounded-2xl border border-slate-100">
                                            @foreach($blogs as $b)
                                            <button type="button" 
                                                    @click="importFromBlog({
                                                        id: {{ $b->id }},
                                                        title: '{{ addslashes($b->title) }}',
                                                        shortDescription: '{{ addslashes($b->shortDescription) }}',
                                                        slug: '{{ $b->slug }}',
                                                        thumbnail: '{{ $b->thumbnail }}'
                                                    })"
                                                    class="flex items-center gap-2 p-2 rounded-xl hover:bg-orange-50 transition text-left group/blog">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 overflow-hidden shrink-0">
                                                    <img src="{{ imageUrl($b->thumbnail) }}" class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-[9px] font-black text-slate-900 truncate">{{ $b->title }}</p>
                                                    <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">{{ $b->createdAt->format('d M Y') }}</p>
                                                </div>
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Gallery Picker -->
                                    <div x-show="slide.type === 'gallery'" class="space-y-2">
                                        <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">Pilih Foto Galeri</p>
                                        <div class="grid grid-cols-2 gap-2 max-h-[150px] overflow-y-auto no-scrollbar bg-white p-2 rounded-2xl border border-slate-100">
                                            @foreach($gallery as $g)
                                            <button type="button" 
                                                    @click="importFromGallery({
                                                        id: {{ $g->id }},
                                                        title: '{{ addslashes($g->title) }}',
                                                        description: '{{ addslashes($g->description) }}',
                                                        image_path: '{{ $g->image_path }}'
                                                    })"
                                                    class="flex flex-col gap-2 p-1 rounded-xl hover:bg-emerald-50 transition text-left group/gallery">
                                                <div class="w-full aspect-video rounded-lg bg-slate-100 overflow-hidden">
                                                    <img src="{{ imageUrl($g->image_path) }}" class="w-full h-full object-cover">
                                                </div>
                                                <p class="text-[8px] font-black text-slate-900 truncate px-1">{{ $g->title ?? 'Untitled' }}</p>
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="w-full h-32 rounded-2xl bg-white border border-slate-200 overflow-hidden relative group/img">
                                        <img :src="fixPath(slide.image_url) || 'https://via.placeholder.com/800x400?text=Pilih+Gambar'" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                            <button type="button" @click="openMedia('slider')" class="px-3 py-1.5 bg-white text-slate-900 rounded-lg font-black text-[8px] uppercase tracking-widest shadow-xl hover:scale-105 transition-transform">
                                                <i class="fas fa-images mr-1"></i> Pilih dari Galeri
                                            </button>
                                            <p class="text-[6px] font-black text-white/60 uppercase tracking-[0.2em]">Atau Upload File di Bawah</p>
                                        </div>
                                    </div>

                                    <!-- Slide Details -->
                                    <div class="space-y-3">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="space-y-1">
                                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Headline</label>
                                                <input type="text" x-model="slide.title" class="w-full px-3 py-2 bg-white rounded-xl border-none font-black text-[10px] text-slate-900">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Lokasi</label>
                                                <input type="text" x-model="slide.location" class="w-full px-3 py-2 bg-white rounded-xl border-none font-bold text-[10px] text-slate-900">
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Deskripsi</label>
                                            <textarea x-model="slide.subtitle" rows="2" class="w-full px-3 py-2 bg-white rounded-xl border-none font-medium text-[9px] text-slate-500 leading-relaxed"></textarea>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="space-y-1">
                                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Upload Image (WebP Auto)</label>
                                                <div class="relative group">
                                                    <input type="file" :name="'homepage_slides['+index+'][image_file]'" @change="handleSlideImage($event, index)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                                                    <div class="w-full px-3 py-2 bg-white rounded-xl border-none flex items-center gap-2 hover:bg-slate-100 transition">
                                                        <i class="fas fa-cloud-arrow-up text-slate-300 text-[10px]"></i>
                                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Pilih File...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-1" x-show="slide.type === 'package' || slide.type === 'manual'">
                                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Harga</label>
                                                <input type="text" x-model="slide.price" class="w-full px-3 py-2 bg-white rounded-xl border-none font-bold text-[9px] text-slate-900">
                                            </div>
                                            <div class="space-y-1" x-show="slide.type === 'package' || slide.type === 'manual'">
                                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Durasi</label>
                                                <input type="text" x-model="slide.duration" class="w-full px-3 py-2 bg-white rounded-xl border-none font-bold text-[9px] text-slate-900">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden Inputs for Form Submission -->
                                    <input type="hidden" :name="'homepage_slides['+index+'][title]'" :value="slide.title">
                                    <input type="hidden" :name="'homepage_slides['+index+'][subtitle]'" :value="slide.subtitle">
                                    <input type="hidden" :name="'homepage_slides['+index+'][image_url]'" :value="slide.image_url">
                                    <input type="hidden" :name="'homepage_slides['+index+'][location]'" :value="slide.location">
                                    <input type="hidden" :name="'homepage_slides['+index+'][duration]'" :value="slide.duration">
                                    <input type="hidden" :name="'homepage_slides['+index+'][price]'" :value="slide.price">
                                    <input type="hidden" :name="'homepage_slides['+index+'][cta_text]'" :value="slide.cta_text">
                                    <input type="hidden" :name="'homepage_slides['+index+'][cta_link]'" :value="slide.cta_link">
                                    <input type="hidden" :name="'homepage_slides['+index+'][type]'" :value="slide.type">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-toba-green transition-all flex items-center justify-center gap-3">
                <i class="fas fa-save"></i> Perbarui Halaman Tour
            </button>
        </form>
    </div>

    <!-- RIGHT: LIVE PREVIEW & SLIDER MANAGEMENT -->
    <div class="flex-1 bg-white rounded-[3.5rem] overflow-hidden relative shadow-[0_40px_100px_-20px_rgba(0,0,0,0.5)] border-8 border-slate-900/50 min-h-[600px] xl:h-[85vh] sticky top-8 overflow-y-auto no-scrollbar">
        
        <!-- Tab: SLIDER PREVIEW (Snippet B) -->
        <div x-show="activeTab === 'slider'" x-transition class="h-full bg-slate-900 relative overflow-hidden">
            <!-- Backgrounds -->
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlideIdx === index" x-transition.opacity.duration.1000ms class="absolute inset-0">
                    <img :src="fixPath(slide.image_url) || 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=1200'" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/40 to-transparent"></div>
                </div>
            </template>

            <!-- Slider Content UI -->
            <div class="relative z-10 h-full flex items-center p-12">
                <div class="w-full flex flex-col lg:flex-row items-center gap-8">
                    <!-- Left: Text -->
                    <div class="w-full lg:w-1/2 text-white">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlideIdx === index" x-transition class="space-y-6">
                                <span class="inline-flex items-center gap-1.5 text-[8px] font-black uppercase tracking-[0.3em] text-toba-accent" x-text="slide.location"></span>
                                <h2 class="text-4xl font-black leading-tight tracking-tighter" x-text="slide.title"></h2>
                                <p class="text-slate-300 text-[10px] leading-relaxed max-w-xs opacity-90" x-text="slide.subtitle"></p>
                                <div class="flex items-center gap-4 pt-4">
                                    <div class="px-6 py-3 bg-toba-green text-white rounded-2xl font-black text-[9px] uppercase tracking-widest shadow-xl" x-text="slide.cta_text"></div>
                                    <div x-show="slide.price > 0" class="flex flex-col">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Mulai Dari</span>
                                        <span class="text-lg font-black text-toba-accent" x-text="'Rp ' + parseInt(slide.price).toLocaleString('id-ID')"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Right: Thumbnails -->
                    <div class="w-full lg:w-1/2 flex justify-end gap-3 overflow-x-auto no-scrollbar pb-4">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button type="button" @click="activeSlideIdx = index" 
                                    :class="activeSlideIdx === index ? 'ring-2 ring-toba-accent ring-offset-2 ring-offset-slate-900 scale-105 opacity-100' : 'opacity-40 hover:opacity-100'"
                                    class="relative shrink-0 w-24 h-32 rounded-2xl overflow-hidden transition-all duration-500">
                                <img :src="fixPath(slide.image_url) || 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=400'" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40"></div>
                                <div class="absolute bottom-2 left-2 right-2 text-left">
                                    <p class="text-[6px] font-black text-white truncate uppercase" x-text="slide.title"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="absolute bottom-10 left-12 z-20 flex items-center gap-4">
                <div class="flex gap-1">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button type="button" @click="activeSlideIdx = index" 
                                :class="activeSlideIdx === index ? 'w-6 bg-toba-accent' : 'w-2 bg-white/40'"
                                class="h-1.5 rounded-full transition-all"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Tab: LIVE PREVIEW (Original Content) -->
        <div x-show="activeTab !== 'slider'" x-transition>
            <!-- Live Hero Section -->
            <section class="relative h-[500px] flex items-center px-12 md:px-20 overflow-hidden">
                <div class="absolute inset-0 bg-cover bg-center transition-all duration-700" :style="`background-image: url('${heroImage}')`"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                
                <div class="relative z-10 max-w-2xl space-y-8">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-px bg-emerald-500"></span>
                        <span class="text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em]">Wonderful Toba Tour</span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-white leading-[0.9] tracking-tighter" x-text="heroTitle"></h1>
                    <p class="text-slate-200 text-sm font-medium leading-relaxed max-w-lg opacity-80" x-text="heroSubtitle"></p>
                    <div class="pt-4">
                        <button type="button" class="px-8 py-4 bg-emerald-500 text-white rounded-full font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-500/20" x-text="ctaText"></button>
                    </div>
                </div>

                <!-- Scroll Indicator -->
                <div class="absolute bottom-10 left-12 md:left-20 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white text-xs animate-bounce">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <span class="text-white/40 text-[8px] font-black uppercase tracking-widest">Scroll Explorasi</span>
                </div>
            </section>

            <!-- Live Stats Section -->
            <section class="py-12 px-12 md:px-20 grid grid-cols-2 md:grid-cols-4 gap-8">
                <template x-for="i in [0,1,2,3]">
                    <div class="space-y-1">
                        <div class="text-3xl font-black text-slate-900 tracking-tighter" x-text="$data['stat'+i]"></div>
                        <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest" x-text="$data['label'+i]"></div>
                    </div>
                </template>
                <div class="space-y-1">
                    <div class="text-3xl font-black text-slate-900 tracking-tighter">4.9/5</div>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Rating</div>
                </div>
            </section>

            <!-- Dummy Gallery Section -->
            <section class="px-12 md:px-20 pb-20">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Galeri Perjalanan</h3>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">View All</span>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="aspect-square rounded-3xl bg-slate-100"></div>
                    <div class="aspect-square rounded-3xl bg-slate-100"></div>
                    <div class="aspect-square rounded-3xl bg-slate-100"></div>
                </div>
            </section>
        </div>

        <!-- Overlay Status -->
        <div class="absolute top-6 right-6 z-50 flex items-center gap-3">
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 text-white text-[8px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-emerald-500/20">
                <span class="w-1 h-1 rounded-full bg-white animate-pulse"></span> Tour Sync
            </div>
            <div class="px-3 py-1.5 bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest rounded-lg">Live Preview</div>
        </div>
    </div>
</div>

@endsection

<style>
    [x-cloak] { display: none !important; }
    textarea { resize: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cmsHandler', () => ({
            activeTab: 'hero',
            activeSlideIdx: 0,
            
            // Hero
            heroTitle: @json($settings['hero_title'] ?? 'Eksplorasi Keajaiban Danau Toba Bersama Kami.'),
            heroSubtitle: @json($settings['hero_subtitle'] ?? 'Nikmati perjalanan tak terlupakan dengan paket tour terbaik, akomodasi premium, dan pemandu lokal berpengalaman.'),
            heroImage: @json(imageUrl($settings['hero_image_url'] ?? '')),
            ctaText: @json($settings['cta_text'] ?? 'Mulai Petualangan'),

            // Stats
            @foreach(range(0, 3) as $i)
            stat{{ $i }}: @json($settings['stat_value_'.$i] ?? '0'),
            label{{ $i }}: @json($settings['stat_label_'.$i] ?? 'Keterangan'),
            @endforeach

            // Specialist
            specialist_name: @json($settings['specialist_name'] ?? 'Sarah Anggraini'),
            specialist_title: @json($settings['specialist_title'] ?? 'Travel Specialist'),
            specialist_desc: @json($settings['specialist_desc'] ?? ''),
            specialist_image: @json($settings['specialist_image_url'] ?? ''),

            // Why Us
            about_title_1: @json($settings['about_title_1'] ?? 'Mengapa Memilih Kami?'),
            about_desc_1: @json($settings['about_desc_1'] ?? ''),

            // Slider Management
            slides: @json($settings['homepage_slides'] ?? []),

            init() {
                if (this.slides.length === 0) {
                    this.addSlide();
                }
            },

            addSlide() {
                this.slides.push({
                    title: 'Destinasi Baru',
                    subtitle: 'Deskripsi perjalanan menarik di sini...',
                    image_url: '',
                    location: 'Sumatera Utara',
                    duration: '3 Hari 2 Malam',
                    price: '1.500.000',
                    cta_text: 'Lihat Detail',
                    cta_link: '#',
                    type: 'manual'
                });
                this.activeSlideIdx = this.slides.length - 1;
            },

            removeSlide(index) {
                if (confirm('Hapus slide ini?')) {
                    this.slides.splice(index, 1);
                    if (this.activeSlideIdx >= this.slides.length) {
                        this.activeSlideIdx = Math.max(0, this.slides.length - 1);
                    }
                }
            },

            importFromPackage(pkg) {
                const slide = this.slides[this.activeSlideIdx];
                slide.title = pkg.name;
                slide.subtitle = pkg.shortDescription;
                slide.location = pkg.locationTag;
                slide.price = pkg.price;
                slide.duration = pkg.duration;
                slide.image_url = pkg.image_path;
                slide.cta_link = '/tour/package/' + pkg.slug;
                slide.cta_text = 'Booking Sekarang';
            },

            importFromBlog(post) {
                const slide = this.slides[this.activeSlideIdx];
                slide.title = post.title;
                slide.subtitle = post.shortDescription;
                slide.image_url = post.thumbnail;
                slide.cta_link = '/tour/blog/' + post.slug;
                slide.cta_text = 'Baca Artikel';
            },

            importFromGallery(img) {
                const slide = this.slides[this.activeSlideIdx];
                slide.title = img.title || 'Wonderful Toba';
                slide.subtitle = img.description;
                slide.image_url = img.image_path;
            },

            openMediaPicker(target, index = null) {
                window.dispatchEvent(new CustomEvent('open-media-picker', {
                    detail: {
                        callback: (item) => {
                            const path = item.path;
                            if (target === 'hero') {
                                this.heroImage = '/storage/' + path;
                                document.getElementById('input_hero_image_url').value = path;
                            } else if (target === 'specialist') {
                                this.specialist_image = path;
                            } else if (target === 'slide' && index !== null) {
                                this.slides[index].image_url = path;
                            } else if (target.startsWith('why_image_')) {
                                // For Why Us images which are individual fields
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
                if (path.startsWith('http')) return path;
                return '/storage/' + path.replace('/storage/', '').replace(/^\//, '');
            },

            handleSlideImage(e, index) {
                const file = e.target.files[0];
                if (file) {
                    this.slides[index].image_url = URL.createObjectURL(file);
                }
            }
        }));
    });
</script>
@endpush
