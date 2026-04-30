@extends('admin.layout')

@section('title', 'Layanan Outbound')
@section('page-title', 'Layanan Outbound')

@section('content')
<div x-data="{ addOpen: false, editOpen: false, editData: {} }" class="space-y-8">

    <div class="flex items-center justify-between">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $services->count() }} Layanan Aktif</p>
        <button @click="addOpen = true" class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Tambah Layanan
        </button>
    </div>

    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-sm overflow-hidden p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
            <div class="p-8 rounded-[2rem] border-2 border-slate-100 hover:border-toba-green/30 transition-all group relative bg-slate-50/50">
                <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-toba-green text-2xl mb-6">
                    <i class="fas {{ $service->icon ?? 'fa-star' }}"></i>
                </div>
                <h4 class="text-lg font-black text-slate-900 tracking-tight mb-2">{{ $service->title }}</h4>
                <p class="text-xs font-bold text-slate-500 mb-6">{{ $service->shortDesc }}</p>

                <div class="flex items-center gap-2">
                    <button @click="editData = {{ json_encode($service) }}; editOpen = true" class="flex-1 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:text-slate-900 transition">
                        Edit
                    </button>
                    <form action="{{ route('admin.outbound.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-100 transition">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center">
                <p class="text-slate-400 font-black uppercase tracking-widest text-sm">Belum ada layanan outbound terdaftar.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="addOpen" x-cloak class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6" @click.self="addOpen = false">
        <div class="bg-white rounded-[3rem] p-12 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-black text-slate-900 mb-8">Tambah Layanan</h3>
            <form action="{{ route('admin.outbound.services.store') }}" method="POST" class="space-y-6">
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
            <form :action="`/admin/outbound/services/${editData.id}`" method="POST" class="space-y-6">
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
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="editOpen = false" class="flex-1 py-4 rounded-2xl border border-slate-200 text-slate-500 font-black text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
