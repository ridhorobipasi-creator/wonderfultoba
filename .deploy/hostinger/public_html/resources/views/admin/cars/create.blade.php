@extends('admin.layout')

@section('title', 'Tambah Armada Baru')

@section('content')
<div class="max-w-5xl" x-data="{ 
    images: [],
    openMediaPicker() {
        window.dispatchEvent(new CustomEvent('open-media-picker', { 
            detail: { 
                callback: (item) => {
                    let path = item.path;
                    if (path.startsWith('/storage/')) path = path.replace('/storage/', '');
                    if (path.startsWith('storage/')) path = path.replace('storage/', '');
                    this.images.push(path);
                }
            } 
        }));
    }
}">
    
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.cars.index') }}" class="group inline-flex items-center text-slate-400 hover:text-slate-900 transition-colors">
            <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center mr-4 group-hover:bg-slate-900 group-hover:text-white transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span class="text-xs font-black uppercase tracking-widest">Kembali ke Daftar</span>
        </a>
    </div>

    <form action="{{ route('admin.cars.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Main Info -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 md:p-10 space-y-8">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                            <i class="fas fa-car-side"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight">Detail Kendaraan</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi dasar armada mobil.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Nama Mobil / Unit</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-slate-900 placeholder:text-slate-300 transition-all"
                                   placeholder="Contoh: Toyota Avanza Veloz 2024">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Tipe Kendaraan</label>
                                <input type="text" name="type" value="{{ old('type') }}" required 
                                       class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-slate-900 placeholder:text-slate-300 transition-all"
                                       placeholder="Contoh: MPV, SUV, Luxury">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Status Ketersediaan</label>
                                <select name="status" required 
                                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-slate-900 transition-all">
                                    <option value="active">Tersedia (Aktif)</option>
                                    <option value="inactive">Tidak Tersedia (Maintenance)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Kapasitas (Seat)</label>
                                <div class="relative">
                                    <input type="number" name="capacity" value="{{ old('capacity', 7) }}" required 
                                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-slate-900 transition-all">
                                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest">Orang</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Transmisi</label>
                                <select name="transmission" required 
                                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-slate-900 transition-all">
                                    <option value="manual">Manual</option>
                                    <option value="automatic">Matic</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Bahan Bakar</label>
                                <input type="text" name="fuel" value="{{ old('fuel', 'Bensin') }}" required 
                                       class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-slate-900 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images Management -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 md:p-10 space-y-8">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl">
                                <i class="fas fa-images"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-900 tracking-tight">Foto Armada</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Gunakan foto berkualitas tinggi (16:9).</p>
                            </div>
                        </div>
                        <button type="button" @click="openMediaPicker()" 
                                class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-toba-green transition-all">
                            + Tambah Foto
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <template x-for="(path, index) in images" :key="index">
                            <div class="relative aspect-video rounded-2xl overflow-hidden border border-slate-100 group">
                                <img :src="path.startsWith('http') ? path : '/storage/' + path.replace(/^\/?storage\//, '')" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click="images.splice(index, 1)" class="w-8 h-8 rounded-full bg-white text-rose-500 flex items-center justify-center shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="images[]" :value="path">
                            </div>
                        </template>
                        <button type="button" @click="openMediaPicker()" x-show="images.length === 0"
                                class="aspect-video rounded-2xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-toba-green hover:bg-slate-50 transition-all group">
                            <i class="fas fa-cloud-arrow-up text-slate-200 group-hover:text-toba-green text-2xl transition-colors"></i>
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Pilih Foto</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Side: Pricing & Options -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 space-y-8 sticky top-24">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Harga Lepas Kunci (Harian)</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm font-black text-slate-400">Rp</span>
                                <input type="number" name="price" value="{{ old('price') }}" required 
                                       class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-black text-slate-900 transition-all text-xl"
                                       placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Harga Dengan Driver (Opsional)</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm font-black text-slate-400">Rp</span>
                                <input type="number" name="priceWithDriver" value="{{ old('priceWithDriver') }}"
                                       class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-slate-900 transition-all"
                                       placeholder="0">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="isFeatured" value="1" class="sr-only peer">
                                    <div class="w-10 h-5 bg-slate-100 rounded-full peer peer-checked:bg-toba-green transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5 shadow-inner"></div>
                                </div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-slate-900 transition-colors">Tampilkan di Unggulan</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-3 pt-6">
                        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-toba-green transition-all shadow-xl shadow-slate-200">
                            Simpan Armada
                        </button>
                        <a href="{{ route('admin.cars.index') }}" class="w-full inline-flex items-center justify-center py-4 rounded-2xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">
                            Batalkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
