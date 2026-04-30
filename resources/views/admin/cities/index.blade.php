@extends('admin.layout')

@section('title', 'Destinations')
@section('page-title', 'City Management')

@section('content')
<div class="space-y-10">
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Manage Destinations</h1>
        <a href="{{ route('admin.cities.create') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
            <i class="fas fa-plus mr-2"></i> Add City
        </a>
    </div>

    <!-- Cities Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($cities as $city)
            <div class="group bg-white rounded-[2.5rem] border border-slate-50 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                <div class="h-48 bg-slate-50 relative overflow-hidden">
                    @if($city->image)
                        <img src="{{ $city->image }}" alt="{{ $city->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-200">
                            <i class="fas fa-city text-4xl"></i>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                        <a href="{{ route('admin.cities.edit', $city) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/20 text-white hover:bg-white transition backdrop-blur-md">
                            <i class="fas fa-pencil text-xs"></i>
                        </a>
                        <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-rose-500/80 text-white hover:bg-rose-50 transition backdrop-blur-md">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-base font-black text-slate-900 tracking-tight mb-2">{{ $city->name }}</h3>
                    <p class="text-xs font-bold text-slate-400 line-clamp-2 leading-relaxed mb-6">{{ $city->description ?? 'Explore this beautiful destination.' }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest"><i class="fas fa-box mr-2"></i> {{ $city->packages_count ?? 0 }} Packages</span>
                        <span class="w-2 h-2 rounded-full bg-toba-green"></span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                <i class="fas fa-map-location-dot text-3xl text-slate-100 mb-4"></i>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">No Destinations Added</p>
            </div>
        @endforelse
    </div>

    @if($cities->hasPages())
        <div class="pt-8">
            {{ $cities->links() }}
        </div>
    @endif
</div>
@endsection
