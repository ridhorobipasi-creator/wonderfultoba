@extends('admin.layout')

@section('title', 'Fleet')
@section('page-title', 'Vehicle Management')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Rental Fleet</h1>
        <a href="{{ route('admin.cars.create') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
            <i class="fas fa-plus mr-2"></i> New Vehicle
        </a>
    </div>

    <!-- Compact Filter Bar -->
    <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px] relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vehicle name..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition">
            </div>

            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Type:</span>
                <input type="text" name="type" value="{{ request('type') }}" placeholder="SUV, MPV..." 
                    class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition w-32">
            </div>

            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status:</span>
                <select name="status" class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition appearance-none">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                <button type="submit" class="bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'type', 'status']))
                    <a href="{{ route('admin.cars.index') }}" class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition">
                        <i class="fas fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Clean Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <tbody class="divide-y divide-slate-50">
                    @forelse($cars as $car)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform overflow-hidden">
                                        @if($car->image)
                                            <img src="{{ $car->image }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-car-side text-slate-300 text-sm"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $car->type }}</p>
                                            @if($car->isFeatured)
                                                <span class="w-1 h-1 rounded-full bg-amber-400"></span>
                                                <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Featured</span>
                                            @endif
                                        </div>
                                        <h4 class="text-sm font-black text-slate-900 tracking-tight leading-tight">{{ $car->name }}</h4>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-8 py-6">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Specifications</p>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-users text-slate-300 text-[10px]"></i>
                                        <span class="text-xs font-bold text-slate-700">{{ $car->capacity }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-gears text-slate-300 text-[10px]"></i>
                                        <span class="text-xs font-bold text-slate-700">{{ ucfirst($car->transmission) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-gas-pump text-slate-300 text-[10px]"></i>
                                        <span class="text-xs font-bold text-slate-700">{{ $car->fuel }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-center">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Daily Price</p>
                                <p class="text-xs font-black text-slate-900">Rp {{ number_format($car->price / 1000, 0) }}K</p>
                            </td>

                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $car->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $car->status }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.cars.show', $car) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg transition transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye text-[10px]"></i>
                                    </a>
                                    <a href="{{ route('admin.cars.edit', $car) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-slate-900 transition shadow-sm">
                                        <i class="fas fa-pencil text-[10px]"></i>
                                    </a>
                                    <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-300 hover:text-rose-500 transition shadow-sm">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">No Vehicles Found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cars->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/20">
                {{ $cars->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
