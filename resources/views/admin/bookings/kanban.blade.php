@extends('admin.layout')

@section('title', 'Kanban Booking')
@section('page-title', 'Booking Kanban Board')

@php
    // Kelas warna statik agar tidak ke-purge Tailwind
    $accent = [
        'slate'   => ['head' => 'bg-slate-100 text-slate-600', 'bar' => 'bg-slate-400', 'ring' => 'ring-slate-200'],
        'amber'   => ['head' => 'bg-amber-100 text-amber-700', 'bar' => 'bg-amber-500', 'ring' => 'ring-amber-200'],
        'blue'    => ['head' => 'bg-blue-100 text-blue-700', 'bar' => 'bg-blue-500', 'ring' => 'ring-blue-200'],
        'violet'  => ['head' => 'bg-violet-100 text-violet-700', 'bar' => 'bg-violet-500', 'ring' => 'ring-violet-200'],
        'emerald' => ['head' => 'bg-emerald-100 text-emerald-700', 'bar' => 'bg-emerald-500', 'ring' => 'ring-emerald-200'],
        'rose'    => ['head' => 'bg-rose-100 text-rose-700', 'bar' => 'bg-rose-500', 'ring' => 'ring-rose-200'],
    ];
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Pipeline Reservasi</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Geser kartu untuk memperbarui status</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="bg-white border border-slate-200 text-slate-600 px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
            <i class="fas fa-list mr-2 text-slate-400"></i> Tampilan List
        </a>
    </div>

    <!-- Toast -->
    <div id="kanban-toast" class="fixed top-20 right-6 z-[120] translate-y-[-120%] opacity-0 transition-all duration-300 bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-bold">
        <i class="fas fa-check-circle text-emerald-400"></i>
        <span id="kanban-toast-msg">Status diperbarui</span>
    </div>

    <!-- Board -->
    <div class="flex gap-5 overflow-x-auto pb-6 -mx-1 px-1">
        @foreach($columns as $key => $col)
            @php $a = $accent[$col['color']] ?? $accent['slate']; $items = $bookings[$key] ?? collect(); @endphp
            <div class="shrink-0 w-[300px] flex flex-col bg-slate-50 rounded-3xl border border-slate-200/70 max-h-[calc(100vh-220px)]">
                <!-- Column header -->
                <div class="p-4 border-b border-slate-200/70 shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full {{ $a['bar'] }}"></span>
                            <h3 class="font-black text-slate-800 text-sm tracking-tight">{{ $col['label'] }}</h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black {{ $a['head'] }}" data-count="{{ $key }}">{{ $items->count() }}</span>
                    </div>
                </div>
                <!-- Cards -->
                <div class="kanban-col flex-grow overflow-y-auto p-3 space-y-3" data-status="{{ $key }}">
                    @foreach($items as $b)
                        @php
                            $phone = $b->customerPhone ?? '';
                            $waPhone = str_starts_with($phone, '0')
                                ? '62'.substr(preg_replace('/[^0-9]/', '', $phone), 1)
                                : preg_replace('/[^0-9]/', '', $phone);
                            $reviewMsg = 'Halo '.($b->customerName ?? '').', terima kasih telah berwisata bersama Wonderful Toba! Semoga perjalanan Anda berkesan. Boleh kami minta ulasan singkat pengalaman Anda? 🙏';
                        @endphp
                        <div class="kanban-card bg-white rounded-2xl border border-slate-200/70 shadow-sm p-4 cursor-grab active:cursor-grabbing ring-1 ring-transparent hover:{{ $a['ring'] }} transition"
                             data-id="{{ $b->id }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $b->bookingCode }}</span>
                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="text-slate-300 hover:text-slate-600 transition" title="Detail" onmousedown="event.stopPropagation()">
                                    <i class="fas fa-up-right-from-square text-xs"></i>
                                </a>
                            </div>
                            <p class="font-black text-slate-900 text-sm leading-tight mb-1">{{ $b->customerName }}</p>
                            <p class="text-xs text-slate-500 font-medium mb-3 line-clamp-1">{{ $b->package->name ?? 'Paket tidak ditemukan' }}</p>

                            <div class="flex items-center gap-3 text-[11px] text-slate-400 font-bold mb-3">
                                <span><i class="far fa-calendar mr-1"></i>{{ $b->startDate ? $b->startDate->format('d M Y') : '-' }}</span>
                                <span class="text-toba-green"><i class="fas fa-tag mr-1"></i>Rp {{ number_format($b->totalPrice ?? 0, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                                @if($waPhone)
                                <a href="https://wa.me/{{ $waPhone }}" target="_blank" onmousedown="event.stopPropagation()"
                                   class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition" title="Chat WhatsApp">
                                    <i class="fab fa-whatsapp text-sm"></i>
                                </a>
                                @endif

                                @if($key === 'completed' && $waPhone)
                                <a href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode($reviewMsg) }}" target="_blank" onmousedown="event.stopPropagation()"
                                   class="flex-grow text-center px-3 py-2 rounded-xl bg-amber-50 text-amber-700 text-[9px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white transition" title="Minta ulasan">
                                    <i class="fas fa-star mr-1"></i> Minta Ulasan
                                </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
    (function () {
        var token = '{{ csrf_token() }}';
        var endpointBase = '{{ url('admin/bookings') }}';

        function toast(msg, ok) {
            var t = document.getElementById('kanban-toast');
            var m = document.getElementById('kanban-toast-msg');
            var icon = t.querySelector('i');
            m.textContent = msg;
            icon.className = ok ? 'fas fa-check-circle text-emerald-400' : 'fas fa-triangle-exclamation text-rose-400';
            t.style.transform = 'translateY(0)';
            t.style.opacity = '1';
            clearTimeout(t._timer);
            t._timer = setTimeout(function () {
                t.style.transform = 'translateY(-120%)';
                t.style.opacity = '0';
            }, 2600);
        }

        function refreshCounts() {
            document.querySelectorAll('.kanban-col').forEach(function (col) {
                var status = col.getAttribute('data-status');
                var badge = document.querySelector('[data-count="' + status + '"]');
                if (badge) badge.textContent = col.querySelectorAll('.kanban-card').length;
            });
        }

        document.querySelectorAll('.kanban-col').forEach(function (col) {
            new Sortable(col, {
                group: 'bookings',
                animation: 150,
                ghostClass: 'opacity-40',
                dragClass: 'rotate-2',
                onEnd: function (evt) {
                    var newStatus = evt.to.getAttribute('data-status');
                    var oldStatus = evt.from.getAttribute('data-status');
                    refreshCounts();
                    if (newStatus === oldStatus && evt.to === evt.from) return;

                    var id = evt.item.getAttribute('data-id');
                    fetch(endpointBase + '/' + id + '/status', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(function (r) {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.json();
                    })
                    .then(function (data) {
                        toast('Dipindah ke "' + (data.status_label || newStatus) + '"', true);
                    })
                    .catch(function () {
                        // Kembalikan kartu jika gagal
                        evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] || null);
                        refreshCounts();
                        toast('Gagal memperbarui status', false);
                    });
                }
            });
        });
    })();
</script>
@endpush
