@extends('admin.layout')

@section('title', 'Lokasi Venue Outbound')
@section('page-title', 'Lokasi Venue Outbound')

@section('content')
<div x-data="locationHandler" class="space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $locations->count() }} Lokasi Terdaftar</p>
        <button @click="addOpen = true" class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Tambah Lokasi
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($locations as $location)
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden group hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                <div class="aspect-square bg-slate-100 relative overflow-hidden">
                    <img src="{{ imageUrl($location->image) }}" alt="{{ $location->name }}" class="w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-110">
                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-4">
                        <button @click="editData = {{ json_encode($location) }}; editOpen = true" class="w-10 h-10 rounded-xl bg-white text-slate-900 shadow-xl hover:bg-toba-green hover:text-white transition-all flex items-center justify-center scale-75 group-hover:scale-100 duration-500">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <form action="{{ route('admin.outbound.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-xl bg-rose-500 text-white shadow-xl hover:bg-rose-600 transition-all flex items-center justify-center scale-75 group-hover:scale-100 duration-500">
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <span class="font-black text-[11px] text-slate-900 tracking-tight uppercase leading-tight">{{ $location->name }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fas fa-map-location-dot text-2xl"></i>
                </div>
                <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">Belum ada lokasi venue terdaftar.</p>
            </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div x-show="addOpen" x-cloak class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6" @click.self="addOpen = false">
        <div class="bg-white rounded-[3rem] p-12 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-black text-slate-900 mb-8">Tambah Lokasi Venue</h3>
            <form action="{{ route('admin.outbound.locations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lokasi / Hotel</label>
                    <input type="text" name="name" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gambar Cover</label>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group h-24 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-slate-50 hover:border-toba-green transition-all cursor-pointer">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="previewImage">
                            <i class="fas fa-cloud-arrow-up text-xl text-slate-300 group-hover:text-toba-green mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase">Lokal</span>
                        </div>
                        <div @click="openMediaPicker()" class="h-24 border-2 border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-white hover:border-indigo-500 hover:bg-indigo-50 transition-all group cursor-pointer">
                            <i class="fas fa-images text-xl text-slate-300 group-hover:text-indigo-500 mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase group-hover:text-indigo-600">Media Pusat</span>
                        </div>
                    </div>

                    <!-- Preview -->
                    <template x-if="previewUrl || selectedMedia">
                        <div class="relative aspect-video rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm group mt-4">
                            <img :src="previewUrl || (selectedMedia ? '/storage/' + selectedMedia.path.replace(/^\/?storage\//, '') : '')" class="w-full h-full object-cover">
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
            <h3 class="text-xl font-black text-slate-900 mb-8">Edit Lokasi Venue</h3>
            <form :action="`/admin/outbound/locations/${editData.id}`" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lokasi / Hotel</label>
                    <input type="text" name="name" :value="editData.name" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gambar Cover</label>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group h-24 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-slate-50 hover:border-toba-green transition-all cursor-pointer">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="previewImage">
                            <i class="fas fa-cloud-arrow-up text-xl text-slate-300 group-hover:text-toba-green mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase">Lokal</span>
                        </div>
                        <div @click="openMediaPicker()" class="h-24 border-2 border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-white hover:border-indigo-500 hover:bg-indigo-50 transition-all group cursor-pointer">
                            <i class="fas fa-images text-xl text-slate-300 group-hover:text-indigo-500 mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase group-hover:text-indigo-600">Media Pusat</span>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="relative aspect-video rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm group mt-4">
                        <img :src="previewUrl || (selectedMedia ? '/storage/' + selectedMedia.path.replace(/^\/?storage\//, '') : (editData.image ? (editData.image.startsWith('http') ? editData.image : '/storage/' + editData.image.replace(/^\/?storage\//, '')) : ''))" class="w-full h-full object-cover">
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
            Alpine.data('locationHandler', () => ({
                addOpen: false, 
                editOpen: false, 
                editData: {},
                previewUrl: null,
                selectedMedia: null,

                init() {
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
                                // Unified Path Logic matching ImageHelper.php
                                let path = item.path;
                                if (path.startsWith('/storage/')) path = path.replace('/storage/', '');
                                if (path.startsWith('storage/')) path = path.replace('storage/', '');
                                
                                this.selectedMedia = { ...item, path: path };
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
