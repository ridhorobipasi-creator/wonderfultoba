@extends('admin.layout')

@section('title', 'Layanan Outbound')
@section('page-title', 'Layanan Outbound')

@section('content')
<div x-data="serviceHandler" class="space-y-8">

    <div class="flex items-center justify-between">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $services->count() }} Layanan Aktif</p>
        <button @click="addOpen = true" class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Tambah Layanan
        </button>
    </div>

    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-sm overflow-hidden p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $service)
            <div class="group relative bg-slate-50 rounded-[2.5rem] overflow-hidden border border-slate-100 hover:border-toba-green/30 transition-all duration-500 hover:shadow-2xl hover:shadow-toba-green/10 flex flex-col h-full">
                <!-- Image Header -->
                <div class="relative h-48 overflow-hidden bg-slate-200">
                    @if($service->image)
                        <img src="{{ imageUrl($service->image) }}" class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                            <i class="fas {{ $service->icon ?? 'fa-cube' }} text-4xl text-slate-300"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                        <div class="flex gap-2 w-full">
                            <button @click="editData = {{ json_encode($service) }}; editOpen = true" class="flex-1 py-3 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-all">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <form action="{{ route('admin.outbound.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')" class="flex-shrink-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 bg-rose-500/20 backdrop-blur-md border border-rose-500/30 text-white rounded-xl flex items-center justify-center hover:bg-rose-500 transition-all">
                                    <i class="fas fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <!-- Icon Overlay (If image exists) -->
                    @if($service->image)
                    <div class="absolute top-4 left-4 w-10 h-10 rounded-xl bg-white/90 backdrop-blur-md shadow-lg flex items-center justify-center text-toba-green text-sm">
                        <i class="fas {{ $service->icon ?? 'fa-star' }}"></i>
                    </div>
                    @endif
                </div>

                <div class="p-8 flex-1 flex flex-col">
                    <h4 class="text-xl font-black text-slate-900 tracking-tight mb-2 group-hover:text-toba-green transition-colors">{{ $service->title }}</h4>
                    <p class="text-xs font-bold text-slate-500 leading-relaxed line-clamp-2 mb-6">{{ $service->shortDesc }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-toba-green"></span> Outbound Service
                        </span>
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-check text-[8px] text-toba-green"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-100">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-6 text-slate-200">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
                <p class="text-slate-400 font-black uppercase tracking-widest text-sm italic">Belum ada layanan outbound terdaftar.</p>
                <button @click="addOpen = true" class="mt-6 text-[10px] font-black text-toba-green uppercase tracking-widest hover:underline">+ Tambah Layanan Pertama</button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="addOpen" x-cloak class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6" @click.self="addOpen = false">
        <div class="bg-white rounded-[3rem] p-12 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-black text-slate-900 mb-8">Tambah Layanan</h3>
            <form action="{{ route('admin.outbound.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Layanan</label>
                    <input type="text" name="title" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi Singkat</label>
                    <input type="text" name="shortDesc" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Icon (FontAwesome Class)</label>
                    <input type="text" name="icon" placeholder="Contoh: fa-users" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gambar Layanan</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group h-20 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-slate-50 hover:border-toba-green transition-all cursor-pointer">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="previewImage">
                            <i class="fas fa-cloud-arrow-up text-lg text-slate-300 group-hover:text-toba-green mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase">Lokal</span>
                        </div>
                        <div @click="openMediaPicker()" class="h-20 border-2 border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-white hover:border-indigo-500 hover:bg-indigo-50 transition-all group cursor-pointer">
                            <i class="fas fa-images text-lg text-slate-300 group-hover:text-indigo-500 mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase group-hover:text-indigo-600">Media Pusat</span>
                        </div>
                    </div>

                    <!-- Preview -->
                    <template x-if="previewUrl || selectedMedia">
                        <div class="relative aspect-video rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm group mt-4">
                            <img :src="previewUrl || '/storage/' + selectedMedia.path" class="w-full h-full object-cover">
                            <button type="button" @click="clearForm()" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                            <input type="hidden" name="media_id" :value="selectedMedia ? selectedMedia.id : ''">
                        </div>
                    </template>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="addOpen = false" class="flex-1 py-4 rounded-2xl border border-slate-200 text-slate-500 font-black text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editOpen" x-cloak class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6" @click.self="editOpen = false">
        <div class="bg-white rounded-[3rem] p-12 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-black text-slate-900 mb-8">Edit Layanan</h3>
            <form :action="`/admin/outbound/services/${editData.id}`" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Layanan</label>
                    <input type="text" name="title" :value="editData.title" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi Singkat</label>
                    <input type="text" name="shortDesc" :value="editData.shortDesc" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Icon (FontAwesome Class)</label>
                    <input type="text" name="icon" :value="editData.icon" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gambar Layanan</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group h-20 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-slate-50 hover:border-toba-green transition-all cursor-pointer">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="previewImage">
                            <i class="fas fa-cloud-arrow-up text-lg text-slate-300 group-hover:text-toba-green mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase">Lokal</span>
                        </div>
                        <div @click="openMediaPicker()" class="h-20 border-2 border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-white hover:border-indigo-500 hover:bg-indigo-50 transition-all group cursor-pointer">
                            <i class="fas fa-images text-lg text-slate-300 group-hover:text-indigo-500 mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase group-hover:text-indigo-600">Media Pusat</span>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="relative aspect-video rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm group mt-4">
                        <img :src="previewUrl || (selectedMedia ? '/storage/' + selectedMedia.path : editData.image)" class="w-full h-full object-cover">
                        <button type="button" @click="clearForm()" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                        <input type="hidden" name="media_id" :value="selectedMedia ? selectedMedia.id : ''">
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="editOpen = false" class="flex-1 py-4 rounded-2xl border border-slate-200 text-slate-500 font-black text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('serviceHandler', () => ({
                addOpen: false, 
                editOpen: false, 
                editData: {},
                previewUrl: null,
                selectedMedia: null,

                init() {
                    // Clean up on close
                    this.$watch('addOpen', val => { if(!val) this.clearForm(); });
                    this.$watch('editOpen', val => { if(!val) this.clearForm(); });
                },

                previewImage(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                        this.selectedMedia = null;
                    }
                },

                openMediaPicker() {
                    window.dispatchEvent(new CustomEvent('open-media-picker', { 
                        detail: { 
                            callback: (item) => {
                                this.selectedMedia = item;
                                this.previewUrl = null;
                            } 
                        } 
                    }));
                },

                clearForm() {
                    this.previewUrl = null;
                    this.selectedMedia = null;
                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    fileInputs.forEach(input => input.value = '');
                }
            }));
        });
    </script>
    @endpush
</div>
@endsection
