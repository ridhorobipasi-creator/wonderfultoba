@extends('admin.layout')

@section('title', 'Buat Paket Baru')
@section('page-title', 'Buat Paket Baru')

@section('breadcrumbs')
    <i class="fas fa-chevron-right text-[6px] opacity-40"></i>
    <a href="{{ route('admin.packages.index') }}" class="hover:text-toba-green transition">Daftar Paket</a>
    <i class="fas fa-chevron-right text-[6px] opacity-40"></i>
    <span class="text-slate-400">Buat Baru</span>
@endsection

@section('content')
<div class="w-full max-w-full" x-data="packageForm">
    <div class="mb-6">
        <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Packages
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Create New Package</h2>

        <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status *</label>
                    <select name="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Images Upload -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package Images</label>
                    <div class="flex flex-col gap-4 mb-4">
                        <button type="button" @click="openPackageMediaPicker()" class="w-full border-2 border-dashed border-gray-300 rounded-2xl p-10 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition group bg-gray-50/50 flex flex-col items-center justify-center gap-3">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <i class="fas fa-images text-3xl"></i>
                            </div>
                            <p class="text-lg font-black text-slate-800 tracking-tight mt-2">Pilih dari Galeri Pusat</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Pilih satu atau lebih aset untuk paket ini</p>
                        </button>
                    </div>

                    <div id="selected-media-container" class="grid grid-cols-4 sm:grid-cols-6 gap-4 mb-4" x-show="selectedMedia.length > 0">
                        <template x-for="(item, idx) in selectedMedia" :key="'media'+item.id">
                            <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-indigo-500 shadow-lg group">
                                <img :src="'/storage/' + (item.path.replace(/^\/?storage\//, ''))" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button type="button" @click="selectedMedia.splice(idx, 1)" class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="media_ids[]" :value="item.id">
                                <div class="absolute top-1 right-1 bg-indigo-600 text-[7px] text-white px-1.5 py-0.5 rounded-full font-black tracking-widest">GALLERY</div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4 flex flex-col gap-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Atau Upload Gambar Baru dari Perangkat (Lokal)</label>
                        <input type="file" name="images[]" multiple @change="handleLocalFiles($event)" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition cursor-pointer">
                    </div>

                    <!-- Local Images Preview -->
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-4 mt-4" x-show="localPreviews.length > 0">
                        <template x-for="(preview, idx) in localPreviews" :key="'local'+idx">
                            <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-emerald-500 shadow-lg group">
                                <img :src="preview.url" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button type="button" @click="removeLocalFile(idx)" class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                <div class="absolute top-1 right-1 bg-emerald-600 text-[7px] text-white px-1.5 py-0.5 rounded-full font-black tracking-widest">LOCAL</div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Short Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Short Description</label>
                    <input type="text" name="shortDescription" value="{{ old('shortDescription') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Description</label>
                    <textarea name="description" id="editor" rows="15"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">{{ old('description') }}</textarea>
                </div>

                <!-- Dynamic Itinerary Editor -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Itinerary (Rencana Perjalanan)</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Susun jadwal perjalanan per hari</p>
                        </div>
                        <button type="button" @click="addDay()" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                            <i class="fas fa-plus mr-2"></i> Tambah Hari
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in itinerary" :key="index">
                            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative group animate-in fade-in slide-in-from-top-2">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-8 h-8 rounded-lg bg-toba-green text-white flex items-center justify-center font-black text-xs shadow-sm">
                                        <span x-text="index + 1"></span>
                                    </div>
                                    <input type="text" :name="'itinerary['+index+'][title]'" x-model="item.title" placeholder="Judul Hari (misal: Penjemputan & City Tour)"
                                        class="flex-1 px-4 py-2 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-sm">
                                    <button type="button" @click="removeDay(index)" class="text-gray-300 hover:text-red-500 transition px-2">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </div>
                                <textarea :name="'itinerary['+index+'][description]'" x-model="item.description" rows="3" placeholder="Detail kegiatan hari ini..."
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 text-sm font-medium"></textarea>
                            </div>
                        </template>

                        <div x-show="itinerary.length === 0" class="py-8 text-center border-2 border-dashed border-gray-200 rounded-2xl">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada jadwal perjalanan</p>
                            <button type="button" @click="addDay()" class="mt-3 text-toba-green text-[10px] font-black uppercase tracking-widest hover:underline">
                                Mulai susun sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Location & Duration Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Location Tag</label>
                        <input type="text" name="locationTag" value="{{ old('locationTag') }}" placeholder="e.g., Danau Toba"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Duration</label>
                        <input type="text" name="duration" value="{{ old('duration') }}" placeholder="e.g., 3 Days 2 Nights"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                    </div>
                </div>

                <!-- Price & Child Price Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (Adult) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (Child)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="childPrice" value="{{ old('childPrice') }}" min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Harga Modal (Internal)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="cost_price" value="{{ old('cost_price') }}" min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" placeholder="Opsional">
                        </div>
                        <p class="mt-1 text-[10px] text-gray-400 font-bold uppercase tracking-widest">Digunakan untuk menghitung laba bersih</p>
                    </div>
                </div>

                <!-- Layanan Tambahan (Additional Services) -->
                <div class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100">
                    <h3 class="text-sm font-black text-indigo-950 mb-4 flex items-center gap-2">
                        <i class="fas fa-hand-holding-usd text-indigo-600"></i> Layanan Tambahan (Additional Services)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Private Jet Charter Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                                <input type="number" name="pricingDetails[private_jet_price]" value="{{ old('pricingDetails.private_jet_price', 120000000) }}" min="0" step="1000"
                                    class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pemandu Antropologi Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                                <input type="number" name="pricingDetails[guide_price]" value="{{ old('pricingDetails.guide_price', 5500000) }}" min="0" step="1000"
                                    class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Includes & Excludes Editor -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Includes -->
                    <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-black text-emerald-900">✅ Yang Termasuk</h3>
                                <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest mt-0.5">Fasilitas yang didapat</p>
                            </div>
                            <button type="button" @click="addInclude()" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-emerald-700 transition">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(item, index) in includes" :key="'inc'+index">
                                <div class="flex items-center gap-2">
                                    <input type="text" :name="'includes['+index+']'" x-model="includes[index]" placeholder="contoh: Tiket Masuk"
                                        class="flex-1 px-3 py-2 bg-white border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-300">
                                    <button type="button" @click="includes.splice(index, 1)" class="text-emerald-300 hover:text-red-500 transition">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </template>
                            <div x-show="includes.length === 0" class="text-center py-4 text-emerald-400 text-[10px] font-bold uppercase tracking-widest">Belum ada item</div>
                        </div>
                    </div>

                    <!-- Excludes -->
                    <div class="bg-red-50 rounded-2xl p-5 border border-red-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-black text-red-900">❌ Tidak Termasuk</h3>
                                <p class="text-[9px] font-bold text-red-500 uppercase tracking-widest mt-0.5">Fasilitas di luar paket</p>
                            </div>
                            <button type="button" @click="addExclude()" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-red-600 transition">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(item, index) in excludes" :key="'exc'+index">
                                <div class="flex items-center gap-2">
                                    <input type="text" :name="'excludes['+index+']'" x-model="excludes[index]" placeholder="contoh: Biaya penginapan"
                                        class="flex-1 px-3 py-2 bg-white border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-red-200">
                                    <button type="button" @click="excludes.splice(index, 1)" class="text-red-300 hover:text-red-600 transition">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </template>
                            <div x-show="excludes.length === 0" class="text-center py-4 text-red-400 text-[10px] font-bold uppercase tracking-widest">Belum ada item</div>
                        </div>
                    </div>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">City</label>
                    <select name="cityId"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        <option value="">Select City (Optional)</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('cityId') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Featured Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="isFeatured" value="1" id="isFeatured" {{ old('isFeatured') ? 'checked' : '' }}
                        class="w-5 h-5 text-toba-green border-gray-300 rounded focus:ring-toba-green">
                    <label for="isFeatured" class="ml-3 text-sm font-bold text-gray-700 cursor-pointer">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>Mark as Featured Package
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
                        <i class="fas fa-save mr-2"></i> Create Package
                    </button>
                    <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'lists link table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat',
        height: 500,
        branding: false,
        promotion: false
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('packageForm', () => ({
            previews: [],
            itinerary: [],
            includes: [],
            excludes: [],
            localFiles: [],
            localPreviews: [],

            addDay() { this.itinerary.push({ title: '', description: '' }); },
            removeDay(index) { this.itinerary.splice(index, 1); },
            addInclude() { this.includes.push(''); },
            addExclude() { this.excludes.push(''); },

            selectedMedia: [],
            openPackageMediaPicker() {
                window.dispatchEvent(new CustomEvent('open-media-picker', { 
                    detail: { 
                        callback: (item) => {
                            let path = item.path;
                            if (path.startsWith('/storage/')) path = path.replace('/storage/', '');
                            if (path.startsWith('storage/')) path = path.replace('storage/', '');

                            if (!this.selectedMedia.some(m => m.id === item.id)) {
                                this.selectedMedia.push({ ...item, path: path });
                            }
                        } 
                    } 
                }));
            },

            handleLocalFiles(e) {
                const files = Array.from(e.target.files);
                files.forEach(file => {
                    this.localFiles.push(file);
                    this.localPreviews.push({
                        url: URL.createObjectURL(file),
                        name: file.name
                    });
                });
                this.updateFileInput();
            },

            removeLocalFile(idx) {
                this.localFiles.splice(idx, 1);
                this.localPreviews.splice(idx, 1);
                this.updateFileInput();
            },

            updateFileInput() {
                const fileInput = document.querySelector('input[type="file"][name="images[]"]');
                if (!fileInput) return;
                const dataTransfer = new DataTransfer();
                this.localFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }
        }));
    });
</script>
@endpush
