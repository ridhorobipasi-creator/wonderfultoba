{{-- Mini kalkulator pax (dewasa/anak) + estimasi total + tombol booking.
     Butuh $xdata = string ekspresi Alpine
     "paxCalc(hargaDewasaMYR, hargaAnakMYR|null, slug, tiersHargaGrosir)".
     Dipakai oleh <x-package-card> (nilai PHP) dan kartu grid /tour/packages (nilai pkg.*). --}}
<div x-data="{{ $xdata }}" class="border-t border-slate-100 px-4 py-3 space-y-2">
    {{-- Baris Dewasa --}}
    <div class="flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[11px] font-semibold text-slate-700 leading-tight">{{ __('Dewasa') }}</p>
            <p class="text-[10px] text-slate-400 leading-tight" x-text="fmt(adultDisplay) + ' /{{ __('org') }}'"></p>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button" @click="decA()" aria-label="{{ __('Kurangi dewasa') }}" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-toba-green hover:text-white text-slate-600 flex items-center justify-center text-base font-bold leading-none transition select-none">&minus;</button>
            <input type="text" inputmode="numeric" x-model.number="adults" @change="normA()" @blur="normA()" aria-label="{{ __('Jumlah dewasa') }}" class="w-8 text-center text-[13px] font-bold text-slate-800 bg-transparent border-0 outline-none p-0">
            <button type="button" @click="incA()" aria-label="{{ __('Tambah dewasa') }}" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-toba-green hover:text-white text-slate-600 flex items-center justify-center text-base font-bold leading-none transition select-none">+</button>
        </div>
    </div>

    {{-- Baris Anak-anak --}}
    <div class="flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[11px] font-semibold text-slate-700 leading-tight">{{ __('Anak-anak') }}</p>
            <p class="text-[10px] text-slate-400 leading-tight" x-text="fmt(childDisplay) + ' /{{ __('org') }}'"></p>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button" @click="decC()" aria-label="{{ __('Kurangi anak') }}" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-toba-green hover:text-white text-slate-600 flex items-center justify-center text-base font-bold leading-none transition select-none">&minus;</button>
            <input type="text" inputmode="numeric" x-model.number="children" @change="normC()" @blur="normC()" aria-label="{{ __('Jumlah anak') }}" class="w-8 text-center text-[13px] font-bold text-slate-800 bg-transparent border-0 outline-none p-0">
            <button type="button" @click="incC()" aria-label="{{ __('Tambah anak') }}" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-toba-green hover:text-white text-slate-600 flex items-center justify-center text-base font-bold leading-none transition select-none">+</button>
        </div>
    </div>

    {{-- Estimasi total + tombol Booking & WhatsApp.
         Dua tombol tidak muat di sisa baris total pada lebar kartu (~300px),
         jadi totalnya dapat barisnya sendiri dan tombolnya turun jadi dua kolom
         sama lebar. --}}
    <div class="pt-2 border-t border-dashed border-slate-200 space-y-2">
        <div class="min-w-0">
            <p class="text-[8.5px] uppercase tracking-widest text-slate-400 leading-tight">{{ __('Estimasi Total') }}</p>
            <p class="text-[15px] font-extrabold text-toba-green leading-tight" x-text="fmt(total)"></p>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <a :href="bookingUrl" class="inline-flex items-center justify-center gap-1 bg-toba-green text-white text-[11px] font-bold uppercase tracking-wider px-3 py-2 rounded-lg hover:bg-primary-container active:scale-95 transition">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ __('Booking') }}
            </a>
            {{-- Langsung ke WhatsApp, pesannya sudah terisi jumlah pax & total
                 yang sedang tampil di kalkulator ini. --}}
            <a :href="waUrl" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center justify-center gap-1.5 border border-toba-green text-toba-green text-[11px] font-bold uppercase tracking-wider px-3 py-2 rounded-lg hover:bg-toba-green hover:text-white active:scale-95 transition">
                <x-icon name="whatsapp" class="w-3.5 h-3.5 shrink-0" />
                {{ __('WhatsApp') }}
            </a>
        </div>
    </div>
</div>
