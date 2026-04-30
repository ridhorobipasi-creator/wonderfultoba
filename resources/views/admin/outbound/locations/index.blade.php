@extends('admin.layout')

@section('title', 'Lokasi Venue Outbound')
@section('page-title', 'Lokasi Venue Outbound')

@section('content')
<div x-data="{ addOpen: false }" class="space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $locations->count() }} Lokasi Terdaftar</p>
        <button @click="addOpen = true" class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Tambah Lokasi
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($locations as $location)
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden group">
                <div class="aspect-square bg-slate-100 relative overflow-hidden">
                    <img src="{{ $location->image ?? '/placeholder.jpg' }}" alt="{{ $location->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <form action="{{ route('admin.outbound.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-xl bg-rose-500 text-white shadow-xl hover:bg-rose-600 transition flex items-center justify-center">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-5 text-center">
                    <span class="font-black text-xs text-slate-900 tracking-tight">{{ $location->name }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-slate-100">
                <p class="text-slate-400 font-black uppercase tracking-widest text-sm">Belum ada lokasi venue terdaftar.</p>
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
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gambar Cover (Max 2MB)</label>
                    <input type="file" name="image" accept="image/*" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 text-xs">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="addOpen = false" class="flex-1 py-4 rounded-2xl border border-slate-200 text-slate-500 font-black text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
