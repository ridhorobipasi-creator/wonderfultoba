@extends('admin.layout')

@section('title', 'Manajemen Armada Mobil')

@section('content')
<div class="space-y-8" x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '',
    confirmDelete(url) {
        this.deleteUrl = url;
        this.showDeleteModal = true;
    }
}">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Armada Mobil</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola unit rental mobil untuk layanan sewa.</p>
        </div>
        <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center justify-center bg-slate-900 text-white px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-toba-green transition-all shadow-xl shadow-slate-200 group">
            <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform"></i> Tambah Armada Baru
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                <i class="fas fa-car"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Unit</p>
                <p class="text-xl font-black text-slate-900">{{ \App\Models\Car::count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Aktif</p>
                <p class="text-xl font-black text-slate-900">{{ \App\Models\Car::where('status', 'active')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Featured</p>
                <p class="text-xl font-black text-slate-900">{{ \App\Models\Car::where('isFeatured', true)->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pesanan Baru</p>
                <p class="text-xl font-black text-slate-900">{{ \App\Models\Booking::where('type', 'car')->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Mobil</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Spesifikasi</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Harga Harian</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($cars as $car)
                    <tr class="group hover:bg-slate-50/30 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-20 h-14 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 group-hover:scale-105 transition-transform">
                                    <img src="{{ !empty($car->images) ? imageUrl($car->images[0]) : asset('images/placeholder-car.webp') }}" 
                                         class="w-full h-full object-cover" 
                                         alt="{{ $car->name }}">
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] px-2 py-0.5 bg-slate-100 rounded-md">{{ $car->type }}</span>
                                        @if($car->isFeatured)
                                            <span class="text-[8px] font-black text-amber-500 uppercase tracking-[0.2em] px-2 py-0.5 bg-amber-50 rounded-md flex items-center gap-1">
                                                <i class="fas fa-star text-[7px]"></i> Featured
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="text-sm font-black text-slate-900 tracking-tight">{{ $car->name }}</h4>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-wrap gap-3">
                                <div class="flex items-center gap-1.5 text-slate-500">
                                    <i class="fas fa-user-friends text-[10px] text-blue-500"></i>
                                    <span class="text-[11px] font-bold">{{ $car->capacity }} Seat</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-500">
                                    <i class="fas fa-cog text-[10px] text-indigo-500"></i>
                                    <span class="text-[11px] font-bold">{{ ucfirst($car->transmission) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-500">
                                    <i class="fas fa-gas-pump text-[10px] text-orange-500"></i>
                                    <span class="text-[11px] font-bold">{{ $car->fuel }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-xs font-black text-slate-900 tracking-tight">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Lapas Kunci</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                                {{ $car->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-400 border border-slate-100' }}">
                                {{ $car->status === 'active' ? 'Tersedia' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <a href="{{ route('admin.cars.edit', $car) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-900 hover:border-slate-900 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <button type="button" @click="confirmDelete('{{ route('admin.cars.destroy', $car) }}')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-300 hover:text-rose-500 hover:border-rose-500 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-200 flex items-center justify-center text-3xl mx-auto mb-4">
                                    <i class="fas fa-car-side"></i>
                                </div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada armada mobil</p>
                                <a href="{{ route('admin.cars.create') }}" class="text-toba-green text-[10px] font-black uppercase tracking-widest mt-4 inline-block hover:underline">
                                    + Tambah Unit Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($cars->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $cars->links() }}
        </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white rounded-[2.5rem] w-full max-w-sm overflow-hidden shadow-2xl"
             @click.away="showDeleteModal = false">
            <div class="p-10 text-center">
                <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-3xl mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2 tracking-tight">Hapus Armada?</h3>
                <p class="text-slate-500 text-xs font-medium leading-relaxed mb-8">Tindakan ini tidak dapat dibatalkan. Semua data unit ini akan dihapus permanen.</p>
                
                <div class="flex flex-col gap-3">
                    <form :action="deleteUrl" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-rose-500 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 transition-colors shadow-lg shadow-rose-200">
                            Ya, Hapus Unit
                        </button>
                    </form>
                    <button @click="showDeleteModal = false" class="w-full bg-slate-100 text-slate-500 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-colors">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
