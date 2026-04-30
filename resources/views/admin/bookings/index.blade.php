@extends('admin.layout')

@section('title', 'Bookings')
@section('page-title', 'Booking Management')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Reservations List</h1>
        <a href="{{ route('admin.bookings.create') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
            <i class="fas fa-plus mr-2"></i> New Booking
        </a>
    </div>

    <!-- Ultra-Compact Horizontal Filter Bar -->
    <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[200px] relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer or code..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition">
            </div>

            <!-- Vertical Divider -->
            <div class="hidden lg:block w-px h-8 bg-slate-100"></div>

            <!-- Date Filter -->
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Date:</span>
                <input type="date" name="date" value="{{ request('date') }}" 
                    class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition w-36">
            </div>

            <!-- Month Filter -->
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Month:</span>
                <select name="month" class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition appearance-none min-w-[100px]">
                    <option value="">All</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('M', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Filter -->
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Year:</span>
                <select name="year" class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition appearance-none">
                    <option value="">All</option>
                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status:</span>
                <select name="status" class="px-3 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition appearance-none">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 ml-auto">
                <button type="submit" class="bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition">
                    Apply
                </button>
                @if(request()->anyFilled(['search', 'date', 'month', 'year', 'status']))
                    <a href="{{ route('admin.bookings.index') }}" class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition">
                        <i class="fas fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grouped Table Content -->
    <div class="bg-white rounded-[2.5rem] border border-slate-50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <tbody class="divide-y divide-slate-50">
                    @php 
                        $groupedBookings = $bookings->groupBy(fn($b) => $b->startDate ? $b->startDate->format('Y-m-d') : 'No Date');
                    @endphp

                    @forelse($groupedBookings as $date => $group)
                        <tr class="bg-slate-50/40">
                            <td colspan="5" class="px-8 py-3.5 flex items-center gap-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-toba-green"></span>
                                <span class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]">
                                    @if($date === 'No Date') Undated @else {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }} @endif
                                </span>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest ml-auto">{{ $group->count() }} items</span>
                            </td>
                        </tr>

                        @foreach($group as $booking)
                            <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-5">
                                        <div class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all">
                                            <i class="fas {{ $booking->type === 'package' ? 'fa-box-archive' : 'fa-car-side' }} text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">{{ $booking->bookingCode }}</p>
                                            <h4 class="text-sm font-black text-slate-900 tracking-tight">{{ $booking->customerName }}</h4>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-8 py-5">
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Item</p>
                                    <p class="text-xs font-bold text-slate-700 truncate max-w-[200px]">{{ $booking->package->name ?? $booking->car->name ?? 'N/A' }}</p>
                                </td>

                                <td class="px-8 py-5 text-center">
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Total</p>
                                    <p class="text-xs font-black text-slate-900">Rp {{ number_format($booking->totalPrice / 1000, 0) }}K</p>
                                </td>

                                <td class="px-8 py-5">
                                    <div class="flex justify-center">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-amber-50 text-amber-600',
                                                'confirmed' => 'bg-emerald-50 text-emerald-600',
                                                'completed' => 'bg-blue-50 text-blue-600',
                                                'cancelled' => 'bg-rose-50 text-rose-600',
                                            ];
                                            $color = $colors[$booking->status] ?? 'bg-slate-50 text-slate-500';
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $color }}">
                                            {{ $booking->status }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->customerPhone ?? '') }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg transition transform hover:-translate-y-0.5" title="Follow up via WA">
                                            <i class="fab fa-whatsapp text-[12px]"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg transition transform hover:-translate-y-0.5" title="View Detail">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-slate-900 transition shadow-sm" title="Edit Status">
                                            <i class="fas fa-pencil text-[10px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">Empty Dataset</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/20">
                {{ $bookings->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
