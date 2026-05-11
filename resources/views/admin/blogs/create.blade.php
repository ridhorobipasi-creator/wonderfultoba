@extends('admin.layout')

@section('title', 'Editor Artikel Mandiri - Wonderful Toba')

@section('content')
<div class="w-full max-w-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-[1.25rem] bg-emerald-600 flex items-center justify-center text-white shadow-xl shadow-emerald-100">
                <i class="fas fa-feather-pointed text-2xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 leading-tight">Editor Mandiri v2.0</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Bebas TinyMCE • Performa Maksimal • Rapi</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('write_form').submit()" class="px-10 py-4 bg-slate-900 hover:bg-black text-white rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all shadow-2xl shadow-slate-200 flex items-center gap-2">
                Tayangkan Artikel
            </button>
        </div>
    </div>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="write_form">
        @csrf
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-10 items-start">
            
            <!-- Main Content Area -->
            <div class="xl:col-span-8">
                <!-- Title Section -->
                <div class="bg-white rounded-[3rem] p-12 shadow-sm border border-slate-100 mb-8">
                    <label class="block text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mb-4 text-center">Judul Utama</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Tuliskan Judul Yang Menarik..."
                        class="w-full text-4xl font-black text-slate-900 placeholder:text-slate-100 border-none focus:ring-0 p-0 text-center mb-4">
                    <div class="flex justify-center">
                        <div class="h-1.5 w-24 bg-emerald-500 rounded-full"></div>
                    </div>
                </div>

                <!-- The "Paper" Editor -->
                <div class="bg-slate-50 p-8 md:p-16 rounded-[3.5rem] border border-slate-100 min-h-[900px] flex justify-center relative">
                    <div class="w-full max-w-[800px] bg-white shadow-2xl rounded-sm p-16 md:p-24 min-h-[1000px] relative">
                        <!-- CKEditor Container -->
                        <div id="editor-container" class="prose prose-slate max-w-none">
                            <textarea name="content" id="editor" rows="20" class="w-full px-6 py-4 bg-slate-50 border-none rounded-[2rem] font-medium text-sm leading-relaxed focus:ring-2 focus:ring-toba-green transition-all">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="xl:col-span-4 space-y-8">
                
                <!-- Classification -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-slate-900 font-black text-[11px] uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-folder-open text-[12px]"></i>
                        </span>
                        Kategori & Penulis
                    </h3>
                    <div class="space-y-4">
                        <select name="category" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500/20 text-xs font-bold text-slate-600">
                            <option value="tour">Tour & Travel</option>
                            <option value="news">Berita</option>
                            <option value="event">Event</option>
                            <option value="tips">Tips</option>
                        </select>
                        <input type="text" name="author" value="{{ auth()->user()->name }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500/20 text-xs font-bold text-slate-600">
                    </div>
                </di                <!-- Cover Image -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100" x-data="blogCoverHandler()">
                    <h3 class="text-slate-900 font-black text-[11px] uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-camera text-[12px]"></i>
                        </span>
                        Foto Sampul
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="relative group h-20 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-slate-50 hover:border-emerald-600 transition-all cursor-pointer">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="previewLocalImage">
                            <i class="fas fa-cloud-arrow-up text-lg text-slate-300 group-hover:text-emerald-600 mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase">Lokal</span>
                        </div>
                        <div @click="openMediaPicker()" class="h-20 border-2 border-slate-200 rounded-2xl flex flex-col items-center justify-center bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all group cursor-pointer">
                            <i class="fas fa-images text-lg text-slate-300 group-hover:text-emerald-500 mb-1"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase group-hover:text-emerald-600">Media Pusat</span>
                        </div>
                    </div>

                    <div class="relative group rounded-3xl overflow-hidden bg-slate-50 border-2 border-slate-100 min-h-[160px] flex items-center justify-center">
                        <img :src="previewUrl" x-show="previewUrl" class="w-full h-full object-cover">
                        <div x-show="!previewUrl" class="text-center p-6">
                            <i class="fas fa-image text-slate-200 text-3xl mb-3"></i>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">Belum ada foto terpilih</p>
                        </div>
                        <input type="hidden" name="image_url" :value="imagePath">
                    </div>
                </div>div>

                <!-- Teaser -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-slate-900 font-black text-[11px] uppercase tracking-[0.2em] mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-paragraph text-[12px]"></i>
                        </span>
                        Ringkasan Singkat
                    </h3>
                    <textarea name="excerpt" rows="4" placeholder="Tuliskan teaser artikel..."
                        class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500/20 text-xs font-medium text-slate-500 leading-relaxed">{{ old('excerpt') }}</textarea>
                </div>

                <!-- Publish Hub -->
                <div class="bg-slate-900 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
                    <div class="relative space-y-6">
                        <div>
                            <label class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3">Jadwal Tayang</label>
                            <input type="datetime-local" name="published_at" value="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full bg-slate-800 border-none rounded-2xl text-white text-[11px] font-bold px-6 py-4 focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3">Status</label>
                                <select name="status" class="w-full bg-slate-800 border-none rounded-2xl text-white text-[10px] font-black uppercase tracking-widest px-6 py-4">
                                    <option value="published">Tayang</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Menggunakan CKEditor 5 (Versi Klasik) yang sepenuhnya mandiri dan gratis tanpa API Key --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<style>
    /* Styling agar CKEditor terlihat seperti kertas Word */
    .ck-editor__editable {
        min-height: 800px;
        border: none !important;
        padding: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: transparent !important;
    }
    .ck-toolbar {
        border: none !important;
        background: #f8fafc !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 1rem 2rem !important;
        position: sticky !important;
        top: 0;
        z-index: 100;
        border-radius: 2.5rem 2.5rem 0 0 !important;
    }
    .ck.ck-editor__top .ck-sticky-panel .ck-toolbar {
        border-radius: 0 !important;
    }
</style>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', 'undo', 'redo'
                ]
            },
            language: 'id',
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            }
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    function blogCoverHandler() {
        return {
            previewUrl: '',
            imagePath: '',
            previewLocalImage(e) {
                const file = e.target.files[0];
                if (file) {
                    this.previewUrl = URL.createObjectURL(file);
                    this.imagePath = '';
                }
            },
            openMediaPicker() {
                window.dispatchEvent(new CustomEvent('open-media-picker', { 
                    detail: { 
                        callback: (item) => {
                            // Unified Path Logic matching ImageHelper.php
                            let path = item.path;
                            if (path.startsWith('/storage/')) path = path.replace('/storage/', '');
                            if (path.startsWith('storage/')) path = path.replace('storage/', '');
                            
                            this.previewUrl = '/storage/' + path;
                            this.imagePath = path;
                        } 
                    } 
                }));
            }
        }
    }
</script>
@endpush
