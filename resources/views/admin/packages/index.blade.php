@extends('admin.layout')

@section('title', 'Packages')
@section('page-title', 'Tour & Outbound Packages')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Available Packages</h1>
        <a href="{{ route('admin.packages.create') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
            <i class="fas fa-plus mr-2"></i> New Package
        </a>
    </div>

    <!-- Compact Horizontal Filter Bar -->
    <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[200px] relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search package name..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition">
            </div>

            <!-- Type Filter -->
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Type:</span>
                <select name="type" class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition appearance-none">
                    <option value="">All Types</option>
                    <option value="tour" {{ request('type') == 'tour' ? 'selected' : '' }}>Tour</option>
                    <option value="outbound" {{ request('type') == 'outbound' ? 'selected' : '' }}>Outbound</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status:</span>
                <select name="status" class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition appearance-none">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Featured -->
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Featured:</span>
                <select name="featured" class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition appearance-none">
                    <option value="">All</option>
                    <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                <button type="submit" class="bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'type', 'status', 'featured']))
                    <a href="{{ route('admin.packages.index') }}" class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition">
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
                    @forelse($packages as $package)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform overflow-hidden">
                                        @if($package->images && count($package->images) > 0)
                                            <img src="{{ $package->images[0] }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-{{ $package->isOutbound ? 'users' : 'map-marked-alt' }} text-slate-300 text-xs"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $package->isOutbound ? 'Outbound' : 'Tour' }}</p>
                                            @if($package->isFeatured)
                                                <span class="w-1 h-1 rounded-full bg-amber-400"></span>
                                                <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Featured</span>
                                            @endif
                                        </div>
                                        <h4 class="text-sm font-black text-slate-900 tracking-tight">{{ $package->name }}</h4>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-8 py-6">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Location / Duration</p>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold text-slate-700"><i class="fas fa-location-dot mr-1 text-slate-300"></i>{{ $package->locationTag ?? 'N/A' }}</span>
                                    <span class="text-xs font-bold text-slate-700"><i class="fas fa-clock mr-1 text-slate-300"></i>{{ $package->duration }}</span>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-center">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Starting From</p>
                                <p class="text-xs font-black text-slate-900">Rp {{ number_format($package->price / 1000, 0) }}K</p>
                            </td>

                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $package->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $package->status }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.packages.show', $package) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg transition transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye text-[10px]"></i>
                                    </a>
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-slate-900 transition shadow-sm">
                                        <i class="fas fa-pencil text-[10px]"></i>
                                    </a>
                                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">No Packages Available</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($packages->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $packages->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
