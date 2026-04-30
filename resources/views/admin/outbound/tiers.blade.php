@extends('admin.layout')

@section('title', 'Tier Paket Outbound')
@section('page-title', 'Tier Paket Outbound')

@section('content')
<div x-data="{ addOpen: false, editOpen: false, editData: {} }" class="space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $tiers->count() }} Tier Terdaftar</p>
        <button @click="addOpen = true"
                class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Tambah Tier
        </button>
    </div>

    <!-- Tier Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @php
            $tierStyles = [
                0 => ['border' => 'border-slate-200', 'bg' => '', 'iconBg' => 'bg-slate-100 text-slate-500', 'icon' => 'fa-seedling', 'badge' => 'bg-slate-100 text-slate-600'],
                1 => ['border' => 'border-amber-200', 'bg' => 'bg-amber-50/30', 'iconBg' => 'bg-amber-100 text-amber-600', 'icon' => 'fa-crown', 'badge' => 'bg-amber-100 text-amber-700'],
                2 => ['border' => 'border-slate-900', 'bg' => 'bg-slate-900', 'iconBg' => 'bg-toba-green text-white', 'icon' => 'fa-gem', 'badge' => 'bg-toba-green/20 text-toba-green'],
            ];
        @endphp

        @forelse($tiers as $index => $tier)
        @php $style = $tierStyles[$index % 3]; @endphp
        <div class="p-10 rounded-[3rem] border-2 {{ $style['border'] }} {{ $style['bg'] }} transition-all hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-start justify-between mb-8">
                <div class="w-14 h-14 rounded-2xl {{ $style['iconBg'] }} flex items-center justify-center text-xl">
                    <i class="fas {{ $style['icon'] }}"></i>
                </div>
                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $style['badge'] }}">Tier {{ $index + 1 }}</span>
            </div>

            <h4 class="text-xl font-black {{ $index === 2 ? 'text-white' : 'text-slate-900' }} tracking-tight mb-3">{{ $tier->tierName }}</h4>
            <p class="text-xs font-bold {{ $index === 2 ? 'text-slate-400' : 'text-slate-500' }} mb-8 leading-relaxed">{{ $tier->tagline ?? 'Tidak ada deskripsi.' }}</p>

            <div class="flex items-center gap-3">
                <button @click="editData = {{ json_encode(['id' => $tier->id, 'tierName' => $tier->tierName, 'tagline' => $tier->tagline]) }}; editOpen = true"
                        class="flex-1 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border {{ $index === 2 ? 'border-slate-700 text-slate-400 hover:bg-slate-800' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }} transition text-center">
                    Edit
                </button>
                <form action="{{ route('admin.outbound.tiers.destroy', $tier->id) }}" method="POST"
                      onsubmit="return confirm('Hapus tier ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-100 transition flex items-center justify-center">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="md:col-span-3 py-20 text-center">
            <p class="text-slate-300 font-black uppercase tracking-widest text-sm">Belum ada tier. Tambahkan sekarang.</p>
        </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div x-show="addOpen" @click.self="addOpen = false" x-cloak
         class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-black text-slate-900 mb-8">Tambah Tier Baru</h3>
            <form action="{{ route('admin.outbound.tiers.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Tier</label>
                    <input type="text" name="tierName" required placeholder="Contoh: Standard" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi</label>
                    <textarea name="tagline" rows="3" placeholder="Jelaskan keunggulan tier ini..." class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold"></textarea>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="addOpen = false" class="flex-1 py-4 rounded-2xl border border-slate-200 text-slate-500 font-black text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editOpen" @click.self="editOpen = false" x-cloak
         class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-black text-slate-900 mb-8">Edit Tier</h3>
            <form :action="`/admin/outbound/tiers/${editData.id}`" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Tier</label>
                    <input type="text" name="tierName" :value="editData.tierName" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi</label>
                    <textarea name="tagline" rows="3" :value="editData.tagline" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold"></textarea>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="editOpen = false" class="flex-1 py-4 rounded-2xl border border-slate-200 text-slate-500 font-black text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Update</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
