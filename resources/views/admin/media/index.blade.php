@extends('admin.layout')

@section('title', 'Media Library')
@section('page-title', 'Media Library')

@section('content')
<div x-data="{
    dragging: false,
    preview: null,
    previewUrl: '',
    showPreview(url) { this.previewUrl = url; this.preview = true; },
    closePreview() { this.preview = false; this.previewUrl = ''; },
    async copyUrl(url) {
        await navigator.clipboard.writeText(url);
        alert('URL disalin ke clipboard!');
    }
}" class="space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pusat Aset Visual</h2>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $images->count() }} file tersimpan</p>
        </div>
    </div>

    <!-- Upload Zone -->
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10">
        <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $el.closest('form').submit()"
                :class="dragging ? 'border-toba-green bg-toba-green/5' : 'border-slate-200 bg-slate-50 hover:border-toba-green hover:bg-toba-green/5'"
                class="border-2 border-dashed rounded-3xl p-16 text-center transition-all cursor-pointer"
                @click="$refs.fileInput.click()"
            >
                <div class="w-16 h-16 rounded-2xl bg-toba-green/10 text-toba-green flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-cloud-arrow-up text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-2">Drag & Drop atau Klik untuk Upload</h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">JPG, PNG, WEBP • Maks 5MB per file</p>

                <input type="file" name="files[]" multiple accept="image/*" x-ref="fileInput" class="hidden"
                       @change="$el.closest('form').submit()">
            </div>
        </form>
    </div>

    <!-- Image Grid -->
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10">
        <h3 class="text-base font-black text-slate-900 tracking-tight mb-8">Semua Gambar</h3>

        @if($images->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                    <i class="fas fa-images text-3xl"></i>
                </div>
                <p class="text-slate-400 font-bold">Belum ada gambar. Upload gambar pertama Anda!</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($images as $image)
                <div class="group relative aspect-square bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <img src="{{ $image->imageUrl }}" alt="{{ $image->caption }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         loading="lazy">

                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-between p-3">
                        <div class="flex justify-end">
                            <form action="{{ route('admin.media.destroy', $image->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus gambar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-7 h-7 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg hover:bg-rose-600 transition">
                                    <i class="fas fa-trash text-[9px]"></i>
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="showPreview('{{ $image->imageUrl }}')"
                                    class="flex-1 py-1.5 bg-white/90 text-slate-900 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-white transition">
                                Lihat
                            </button>
                            <button @click="copyUrl('{{ $image->imageUrl }}')"
                                    class="flex-1 py-1.5 bg-toba-green text-white rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-toba-green/80 transition">
                                Salin URL
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Lightbox Preview -->
    <div x-show="preview"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="closePreview()"
         @keydown.escape.window="closePreview()"
         class="fixed inset-0 z-[200] bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-8"
         x-cloak>
        <div class="relative max-w-4xl max-h-[90vh]">
            <img :src="previewUrl" class="max-h-[85vh] max-w-full rounded-3xl shadow-2xl object-contain">
            <button @click="closePreview()"
                    class="absolute -top-4 -right-4 w-10 h-10 bg-white rounded-full shadow-xl flex items-center justify-center text-slate-900 hover:bg-slate-100 transition">
                <i class="fas fa-times text-xs"></i>
            </button>
            <button @click="copyUrl(previewUrl)"
                    class="absolute bottom-4 left-1/2 -translate-x-1/2 px-8 py-3 bg-toba-green text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:-translate-y-1 transition">
                Salin URL Gambar
            </button>
        </div>
    </div>

</div>
@endsection
