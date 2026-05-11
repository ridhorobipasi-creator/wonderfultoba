@extends('admin.layout')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@section('content')
<div class="w-full max-w-full" x-data="packageEditForm">
    <div class="mb-6">
        <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Packages
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Edit Package: {{ $package->name }}</h2>

        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package Name *</label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type & Status Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Type *</label>
                        <select name="isOutbound" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                            <option value="0" {{ old('isOutbound', $package->isOutbound) == '0' ? 'selected' : '' }}>Tour Package</option>
                            <option value="1" {{ old('isOutbound', $package->isOutbound) == '1' ? 'selected' : '' }}>Outbound Package</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status *</label>
                        <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                            <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Current Images & Upload -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package Images</label>
                    
                    @if($package->images && count($package->images) > 0)
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-4 mb-4">
                            @foreach($package->images as $image)
                                <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm group transition-all"
                                     :class="isRemoving('{{ $image }}') ? 'ring-4 ring-red-500 opacity-50 scale-95' : ''">
                                    <img src="{{ $package->resolveImageUrl($image) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <label class="cursor-pointer bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 transition">
                                            <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="hidden" @change="toggleRemove('{{ $image }}')">
                                            <i class="fas" :class="isRemoving('{{ $image }}') ? 'fa-undo' : 'fa-trash-alt'"></i>
                                        </label>
                                    </div>
                                    <div class="absolute top-1 right-1 bg-red-600 text-[10px] text-white px-1 rounded font-black" x-show="isRemoving('{{ $image }}')">TO DELETE</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <div class="flex-1 border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-toba-green transition group bg-gray-50/50 cursor-pointer relative">
                            <input type="file" name="images[]" multiple id="images" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*" @change="previewImages">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-toba-green transition mb-3"></i>
                            <p class="text-sm font-bold text-gray-700">Upload dari Perangkat</p>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest font-black">Seret file atau klik</p>
                        </div>
                        
                        <button type="button" @click="openPackageMediaPicker()" class="flex-1 border-2 border-gray-200 rounded-2xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition group bg-white flex flex-col items-center justify-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-images text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-700 uppercase tracking-tight">Pilih dari Galeri Pusat</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Gunakan Aset yang Sudah Ada</p>
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
                    <div id="image-preview" class="grid grid-cols-4 sm:grid-cols-6 gap-4 mt-4">
                        <template x-for="url in previews" :key="url">
                            <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <img :src="url" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 italic">* Hover over existing images to delete them.</p>
                </div>

                <!-- Short Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Short Description</label>
                    <input type="text" name="shortDescription" value="{{ old('shortDescription', $package->shortDescription) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Description</label>
                    <textarea name="description" id="editor" rows="15"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">{{ old('description', $package->description) }}</textarea>
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
                        <input type="text" name="locationTag" value="{{ old('locationTag', $package->locationTag) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Duration</label>
                        <input type="text" name="duration" value="{{ old('duration', $package->duration) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                    </div>
                </div>

                <!-- Price & Child Price Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (Adult) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="price" value="{{ old('price', $package->price) }}" required min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition @error('price') border-red-500 @enderror">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (Child)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="childPrice" value="{{ old('childPrice', $package->childPrice) }}" min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Harga Modal (Internal)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="cost_price" value="{{ old('cost_price', $package->cost_price) }}" min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" placeholder="Opsional">
                        </div>
                        <p class="mt-1 text-[10px] text-gray-400 font-bold uppercase tracking-widest">Digunakan untuk menghitung laba bersih</p>
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
                            <option value="{{ $city->id }}" {{ old('cityId', $package->cityId) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Featured Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="isFeatured" value="1" id="isFeatured" {{ old('isFeatured', $package->isFeatured) ? 'checked' : '' }}
                        class="w-5 h-5 text-toba-green border-gray-300 rounded focus:ring-toba-green">
                    <label for="isFeatured" class="ml-3 text-sm font-bold text-gray-700 cursor-pointer">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>Mark as Featured Package
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" :disabled="isSubmitting" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md disabled:opacity-50">
                        <template x-if="!isSubmitting">
                            <div class="flex items-center">
                                <i class="fas fa-save mr-2"></i> Update Package
                            </div>
                        </template>
                        <template x-if="isSubmitting">
                            <div class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Updating...
                            </div>
                        </template>
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
        Alpine.data('packageEditForm', () => ({
            previews: [],
            removeImages: [],
            itinerary: @js($package->itinerary ?? []),
            includes: @js(is_array($package->includes) ? $package->includes : ($package->includes ? array_values((array)$package->includes) : [])),
            excludes: @js(is_array($package->excludes) ? $package->excludes : ($package->excludes ? array_values((array)$package->excludes) : [])),
            isSubmitting: false,

            previewImages(e) {
                const files = e.target.files;
                this.previews = [];
                if (files) {
                    Array.from(files).forEach(file => {
                        this.previews.push(URL.createObjectURL(file));
                    });
                }
            },

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

            toggleRemove(imageUrl) {
                if (this.removeImages.includes(imageUrl)) {
                    this.removeImages = this.removeImages.filter(i => i !== imageUrl);
                } else {
                    this.removeImages.push(imageUrl);
                }
            },

            isRemoving(imageUrl) {
                return this.removeImages.includes(imageUrl);
            }
        }))
    });
</script>
@endpush
