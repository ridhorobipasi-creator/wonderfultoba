@extends('admin.layout')

@section('title', 'Media Library')
@section('page-title', 'Pusat Galeri Media')

@section('content')
<div x-data="mediaManager()" 
     @dragover.prevent="isDragging = true" 
     @dragleave.prevent="isDragging = false" 
     @drop.prevent="handleDrop($event)"
     class="flex flex-col lg:flex-row gap-8 min-h-[80vh] relative">
    
    <!-- Left Sidebar: Folder Navigation -->
    <aside class="w-full lg:w-80 shrink-0 space-y-6">
        <!-- Quick Stats -->
        <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-100 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-200">Library Health</p>
                <h4 class="text-3xl font-black mt-2" x-text="stats.orphans">0</h4>
                <p class="text-[9px] font-bold text-indigo-100/60 uppercase tracking-widest mt-1">Unused Assets found</p>
            </div>
        </div>

        <!-- Folder List -->
        <nav class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm space-y-2">
            <div class="flex items-center justify-between px-4 mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Master Folders</p>
                <button @click="createNewFolder()" class="w-6 h-6 rounded-lg bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-plus text-[10px]"></i>
                </button>
            </div>
            
            <button @click="setCategory('')" 
                    :class="filters.category === '' ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
                    class="w-full flex items-center justify-between px-6 py-4 rounded-2xl transition-all group">
                <div class="flex items-center gap-4">
                    <i class="fas fa-layer-group text-sm group-hover:scale-110 transition-transform"></i>
                    <span class="text-[11px] font-black uppercase tracking-widest">All Assets</span>
                </div>
                <span class="text-[9px] font-bold opacity-40" x-text="stats.total"></span>
            </button>

            <template x-for="cat in categories" :key="cat.name">
                <div class="group/folder relative">
                    <button @click="setCategory(cat.name)" 
                            :class="filters.category === cat.name ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
                            class="w-full flex items-center justify-between px-6 py-4 rounded-2xl transition-all group">
                        <div class="flex items-center gap-4">
                            <i class="fas text-sm group-hover:scale-110 transition-transform" :class="cat.icon"></i>
                            <span class="text-[11px] font-black uppercase tracking-widest" x-text="cat.name"></span>
                        </div>
                        <span class="text-[9px] font-bold opacity-40 group-hover:hidden" x-text="cat.count"></span>
                        
                        <!-- Rename Folder Trigger -->
                        <div @click.stop="openRenameFolder(cat.name)" class="hidden group-hover:flex w-6 h-6 bg-white rounded-lg shadow-sm items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors">
                            <i class="fas fa-pen text-[9px]"></i>
                        </div>
                    </button>
                </div>
            </template>
        </nav>

        <!-- Status Filter -->
        <div class="bg-slate-900 rounded-[2.5rem] p-6 text-white space-y-2">
            <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.3em] mb-4">Quick Filters</p>
            <button @click="setUsage('all')" 
                    :class="filters.usage === 'all' ? 'bg-white/10 text-white' : 'text-white/40 hover:text-white'"
                    class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl transition-all group">
                <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-circle-nodes text-[10px]"></i>
                </div>
                <span class="text-[11px] font-black uppercase tracking-widest">All Status</span>
            </button>
            <button @click="setUsage('orphan')" 
                    :class="filters.usage === 'orphan' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/20' : 'text-white/40 hover:text-white'"
                    class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl transition-all group">
                <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-broom text-[10px]"></i>
                </div>
                <span class="text-[11px] font-black uppercase tracking-widest">Orphan Items</span>
            </button>
        </div>
    </aside>

    <!-- Main Content: The Grid -->
    <main class="flex-1 space-y-8">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">
                    <a href="/admin/media" class="hover:text-indigo-600">Library</a>
                    <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
                    <span class="text-indigo-600" x-text="filters.category || 'Home'"></span>
                </div>
                <h2 class="text-5xl font-black text-slate-900 tracking-tighter leading-none">
                    <span x-text="filters.category ? filters.category.toUpperCase() : 'GALLERY HUB'"></span>
                </h2>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Select All Toggle -->
                <button @click="toggleSelectAll()" 
                        class="px-6 py-5 bg-white border border-slate-200 text-slate-900 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm flex items-center gap-3">
                    <div class="w-4 h-4 border-2 border-slate-300 rounded flex items-center justify-center transition-colors"
                         :class="isAllSelected ? 'bg-indigo-600 border-indigo-600' : ''">
                        <i class="fas fa-check text-[8px] text-white" x-show="isAllSelected"></i>
                    </div>
                    <span x-text="isAllSelected ? 'Deselect All' : 'Select All'"></span>
                </button>

                <button @click="syncMedia()" class="px-8 py-5 bg-white border border-slate-200 text-slate-900 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm flex items-center gap-3">
                    <i class="fas fa-rotate" :class="syncing ? 'animate-spin' : ''"></i> Sync Disk
                </button>
                <input type="file" id="mediaUpload" multiple class="hidden" @change="uploadFiles($event)">
                <label for="mediaUpload" class="px-10 py-5 bg-indigo-600 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all cursor-pointer flex items-center gap-3 group">
                    <i class="fas fa-upload group-hover:-translate-y-1 transition-transform"></i> Upload New
                </label>
            </div>
        </div>

        <!-- Search Bar & Per Page -->
        <div class="flex flex-col md:flex-row gap-6">
            <div class="relative group flex-1">
                <i class="fas fa-search absolute left-7 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                <input type="text" x-model="filters.search" @input.debounce.500ms="fetchMedia()" placeholder="Cari aset dalam folder ini..." 
                       class="w-full pl-16 pr-8 py-6 bg-white border border-slate-100 rounded-[2rem] font-bold text-sm text-slate-900 shadow-sm focus:ring-[1rem] focus:ring-indigo-600/5 transition-all outline-none">
            </div>

            <div class="flex items-center gap-3 bg-white border border-slate-100 rounded-[2rem] px-6 py-2 shadow-sm shrink-0">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2">Show</span>
                <select x-model="filters.perPage" @change="fetchMedia()" class="bg-transparent border-none font-black text-xs text-slate-900 focus:ring-0 cursor-pointer pr-8">
                    <option value="24">24 Items</option>
                    <option value="48">48 Items</option>
                    <option value="96">96 Items</option>
                    <option value="all">View All</option>
                </select>
            </div>
        </div>

        <!-- Bulk Actions Floating Bar -->
        <template x-if="selectedIds.length > 0">
            <div class="fixed bottom-12 left-1/2 lg:left-[calc(50%+160px)] -translate-x-1/2 z-[100] bg-slate-900 text-white px-10 py-6 rounded-[2.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] flex items-center gap-10 animate-in slide-in-from-bottom duration-500 backdrop-blur-xl bg-opacity-95 border border-white/5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-lg font-black shadow-lg" x-text="selectedIds.length"></div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40 leading-none">Items Selected</p>
                        <p class="text-[12px] font-bold mt-1.5 uppercase tracking-widest">Bulk Actions</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 border-l border-white/10 pl-10">
                    <button @click="openMoveModal()" class="px-7 py-4 bg-white/5 border border-white/10 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-3">
                        <i class="fas fa-up-down-left-right text-indigo-400"></i> Move
                    </button>
                    <button @click="bulkDownload()" class="px-7 py-4 bg-white/5 border border-white/10 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-3">
                        <i class="fas fa-download text-emerald-400"></i> Zip
                    </button>
                    <button @click="bulkDelete()" class="px-7 py-4 bg-rose-500 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all flex items-center gap-3 shadow-xl shadow-rose-950/40">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <button @click="selectedIds = []" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all ml-4" title="Cancel Selection">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </template>

        <!-- The Grid -->
        <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-8">
            <template x-for="item in media" :key="item.id">
                <div @click="handleItemClick($event, item.id)"
                     class="group relative aspect-square rounded-[3.5rem] bg-white border border-slate-50 overflow-hidden hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.15)] transition-all duration-700 transform hover:-translate-y-2 cursor-pointer select-none"
                     :class="selectedIds.includes(item.id) ? 'ring-[0.5rem] ring-indigo-600 ring-offset-4' : ''">
                    
                    <img :src="item.thumbnail_url" class="w-full h-full object-cover transition-transform duration-[2.5s] group-hover:scale-110" loading="lazy">
                    
                    <!-- Selection Indicator (Always visible if selected) -->
                    <div class="absolute top-6 right-6 z-20" :class="selectedIds.includes(item.id) ? 'opacity-100' : 'opacity-0 group-hover:opacity-100 transition-opacity'">
                        <div class="w-8 h-8 rounded-xl border-2 flex items-center justify-center transition-all"
                             :class="selectedIds.includes(item.id) ? 'bg-indigo-600 border-indigo-600 shadow-lg scale-110' : 'bg-black/20 border-white/40 backdrop-blur-md'">
                            <i class="fas fa-check text-[10px] text-white" x-show="selectedIds.includes(item.id)"></i>
                        </div>
                    </div>

                    <!-- Smart Overlay (Only visible on hover + if NOT in selection mode) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 p-8 flex flex-col justify-end"
                         x-show="selectedIds.length === 0">
                        <div class="space-y-5">
                            <div class="flex flex-wrap gap-2 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <div class="px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-xl text-[7px] font-black uppercase tracking-widest text-white border border-white/20" x-text="item.category"></div>
                                <div class="px-3 py-1.5 bg-indigo-600 rounded-xl text-[7px] font-black uppercase tracking-widest text-white" x-show="item.usage_count > 0" x-text="item.usage_count + ' Links'"></div>
                            </div>
                            
                            <div class="flex gap-2 translate-y-4 group-hover:translate-y-0 transition-transform duration-700 delay-75">
                                <button @click.stop="openEditModal(item)" class="flex-1 py-3.5 bg-white text-slate-900 rounded-[1.25rem] font-black text-[9px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all shadow-xl">
                                    Manage
                                </button>
                                <button @click.stop="copyUrl(item.path)" class="w-11 h-11 bg-white/10 backdrop-blur-md text-white rounded-[1.25rem] flex items-center justify-center hover:bg-white hover:text-slate-900 transition-all border border-white/20">
                                    <i class="fas fa-link text-[11px]"></i>
                                </button>
                                <button @click.stop="deleteItem(item.id)" class="w-11 h-11 bg-rose-500 text-white rounded-[1.25rem] flex items-center justify-center hover:bg-rose-600 transition-all shadow-xl">
                                    <i class="fas fa-trash text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Selection Mode Overlay -->
                    <div class="absolute inset-0 bg-indigo-600/10 pointer-events-none" x-show="selectedIds.includes(item.id)"></div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && media.length === 0" class="py-40 text-center bg-white rounded-[4rem] border border-dashed border-slate-200">
            <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-slate-200">
                <i class="fas fa-folder-open text-4xl"></i>
            </div>
            <h4 class="text-2xl font-black text-slate-900">Folder is Empty</h4>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-3">Start by dragging files here</p>
        </div>

        <!-- Skeleton -->
        <div x-show="loading" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-8">
            @for($i=0; $i<10; $i++)
                <div class="aspect-square bg-slate-50 rounded-[3.5rem] animate-pulse"></div>
            @endfor
        </div>

        <!-- Pagination -->
        <div x-show="last_page > 1" class="flex flex-col sm:flex-row items-center justify-between gap-8 pt-12 border-t border-slate-100">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                Showing <span class="text-slate-900" x-text="media.length"></span> of <span class="text-slate-900" x-text="stats.total"></span> Assets
            </p>
            <div class="flex items-center gap-3">
                <button @click="changePage(current_page - 1)" :disabled="current_page === 1" class="w-14 h-14 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-indigo-600 disabled:opacity-20 transition-all flex items-center justify-center">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl">
                    Page <span x-text="current_page"></span> / <span x-text="last_page"></span>
                </div>
                <button @click="changePage(current_page + 1)" :disabled="current_page === last_page" class="w-14 h-14 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-indigo-600 disabled:opacity-20 transition-all flex items-center justify-center">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </main>

    <!-- Modal: Rename Folder -->
    <div x-show="renamingFolder" class="fixed inset-0 z-[210] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="renamingFolder = null"></div>
        <div class="relative bg-white w-full max-w-md rounded-[3.5rem] p-12 shadow-2xl" x-transition>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter leading-none mb-4">Rename Folder</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Folder: <span x-text="renamingFolderData.old_name" class="text-indigo-600"></span></p>
            
            <div class="space-y-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-4">New Folder Name</label>
                    <input type="text" x-model="renamingFolderData.new_name" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-sm focus:ring-8 focus:ring-indigo-600/5 transition-all outline-none">
                </div>
                <button @click="saveRenameFolder()" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-indigo-600 transition-all">
                    Update Folder Name
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Move Assets -->
    <div x-show="movingAssets" class="fixed inset-0 z-[210] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="movingAssets = null"></div>
        <div class="relative bg-white w-full max-w-md rounded-[3.5rem] p-12 shadow-2xl" x-transition>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter leading-none mb-4">Move Items</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Relocate <span x-text="selectedIds.length" class="text-indigo-600"></span> selected items</p>
            
            <div class="space-y-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-4">Select Target Folder</label>
                    <div class="grid grid-cols-1 gap-2">
                        <template x-for="cat in categories" :key="cat.name">
                            <button @click="moveTarget = cat.name" 
                                    :class="moveTarget === cat.name ? 'bg-indigo-600 text-white shadow-xl scale-105' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
                                    class="w-full flex items-center justify-between px-6 py-4 rounded-2xl transition-all">
                                <div class="flex items-center gap-4">
                                    <i class="fas text-xs" :class="cat.icon"></i>
                                    <span class="text-[11px] font-black uppercase tracking-widest" x-text="cat.name"></span>
                                </div>
                            </button>
                        </template>
                        <button @click="moveTarget = prompt('New Folder Name?')" class="w-full px-6 py-4 bg-slate-50 text-slate-400 border border-dashed border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:border-indigo-400 hover:text-indigo-600 transition-all">
                            + Create & Move to New
                        </button>
                    </div>
                </div>
                <button @click="saveMove()" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-indigo-600 transition-all disabled:opacity-30" :disabled="!moveTarget">
                    Confirm Move
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Edit File / Rename -->
    <div x-show="editingItem" class="fixed inset-0 z-[210] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="editingItem = null"></div>
        <div class="relative bg-white w-full max-w-xl rounded-[4rem] p-12 shadow-2xl overflow-hidden" x-transition>
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter leading-none">File Properties</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-4" x-text="editingItem?.mime_type + ' • ' + (editingItem?.size / 1024).toFixed(1) + ' KB'"></p>
                </div>
                <button @click="editingItem = null" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-8">
                <div class="aspect-video w-full rounded-[2.5rem] overflow-hidden bg-slate-50 border border-slate-100 shadow-inner group/preview relative">
                    <img :src="editingItem?.url" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                         <a :href="editingItem?.url" target="_blank" class="w-10 h-10 bg-white/20 backdrop-blur-md text-white rounded-xl flex items-center justify-center hover:bg-white hover:text-slate-900 transition-all border border-white/20">
                            <i class="fas fa-expand-alt"></i>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-4">Original Filename</label>
                        <input type="text" x-model="editingItemData.filename" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-sm focus:ring-8 focus:ring-indigo-600/5 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-4">SEO Alternative Text</label>
                        <input type="text" x-model="editingItemData.alt_text" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-sm focus:ring-8 focus:ring-indigo-600/5 transition-all outline-none" placeholder="Deskripsi untuk Google Image...">
                    </div>
                </div>

                <button @click="saveEdit()" class="w-full py-6 bg-slate-900 text-white rounded-[2.5rem] font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-indigo-600 transition-all">
                    Update All Properties
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function mediaManager() {
    return {
        media: [],
        categories: [],
        current_page: 1,
        last_page: 1,
        isDragging: false,
        loading: true,
        selectedIds: [],
        editingItem: null,
        editingItemData: { filename: '', alt_text: '', category: '' },
        movingAssets: false,
        moveTarget: '',
        renamingFolder: null,
        renamingFolderData: { old_name: '', new_name: '' },
        syncing: false,
        stats: { total: 0, orphans: 0 },
        filters: { search: '', category: '', usage: 'all', perPage: 24 },
        lastSelectedId: null,
        
        init() { this.fetchMedia(); },

        get isAllSelected() {
            return this.media.length > 0 && this.media.every(item => this.selectedIds.includes(item.id));
        },

        toggleSelectAll() {
            if (this.isAllSelected) {
                this.selectedIds = [];
            } else {
                this.selectedIds = [...new Set([...this.selectedIds, ...this.media.map(item => item.id)])];
            }
        },

        handleItemClick(e, id) {
            if (e.shiftKey && this.lastSelectedId) {
                const ids = this.media.map(m => m.id);
                const start = ids.indexOf(this.lastSelectedId);
                const end = ids.indexOf(id);
                if (start !== -1 && end !== -1) {
                    const range = ids.slice(Math.min(start, end), Math.max(start, end) + 1);
                    this.selectedIds = [...new Set([...this.selectedIds, ...range])];
                    return;
                }
            }

            this.lastSelectedId = id;
            if (this.selectedIds.includes(id)) {
                this.selectedIds = this.selectedIds.filter(i => i !== id);
            } else {
                this.selectedIds.push(id);
            }
        },

        setCategory(cat) {
            this.filters.category = cat;
            this.current_page = 1;
            this.fetchMedia();
        },

        setUsage(usage) {
            this.filters.usage = usage;
            this.current_page = 1;
            this.fetchMedia();
        },

        createNewFolder() {
            const name = prompt('Nama Folder Baru?');
            if (name) { this.setCategory(name.toLowerCase()); }
        },

        openRenameFolder(name) {
            this.renamingFolder = true;
            this.renamingFolderData = { old_name: name, new_name: name };
        },

        async saveRenameFolder() {
            try {
                const response = await fetch('{{ route('admin.media.rename-folder') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.renamingFolderData)
                });
                const data = await response.json();
                if (data.success) {
                    this.renamingFolder = null;
                    if (this.filters.category === this.renamingFolderData.old_name) {
                        this.filters.category = this.renamingFolderData.new_name;
                    }
                    this.fetchMedia();
                    this.showToast('✓ ' + data.message);
                }
            } catch (e) { console.error(e); }
        },

        openMoveModal() {
            this.movingAssets = true;
            this.moveTarget = '';
        },

        async saveMove() {
            try {
                const response = await fetch('{{ route('admin.media.move') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ ids: this.selectedIds, category: this.moveTarget })
                });
                const data = await response.json();
                if (data.success) {
                    this.movingAssets = false;
                    this.selectedIds = [];
                    this.fetchMedia();
                    this.showToast('✓ ' + data.message);
                }
            } catch (e) { console.error(e); }
        },

        async syncMedia() {
            this.syncing = true;
            try {
                const response = await fetch('{{ route('admin.media.sync') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.success) { this.fetchMedia(); this.showToast('✓ ' + data.message); }
            } catch (e) { console.error(e); } finally { this.syncing = false; }
        },

        fetchMedia() {
            this.loading = true;
            this.selectedIds = []; // Clear selection on view change
            let url = `/admin/media?page=${this.current_page}&search=${this.filters.search}&category=${this.filters.category}&usage=${this.filters.usage}&per_page=${this.filters.perPage}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    this.media = data.media.data;
                    this.last_page = data.media.last_page;
                    this.categories = data.categories;
                    this.stats = data.stats;
                    this.loading = false;
                });
        },

        changePage(page) {
            if (page < 1 || page > this.last_page) return;
            this.current_page = page; this.fetchMedia();
        },

        handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;
            this.uploadFiles({ target: { files } });
        },

        uploadFiles(e) {
            const files = e.target.files;
            if (!files.length) return;
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) { formData.append('files[]', files[i]); }
            formData.append('category', this.filters.category || 'uploads');
            formData.append('_token', '{{ csrf_token() }}');
            this.loading = true;
            fetch('/admin/media', { method: 'POST', body: formData })
            .then(res => res.json()).then(data => { if (data.success) { this.fetchMedia(); this.showToast('✓ ' + data.message); } })
            .catch(err => { alert('Upload error'); this.loading = false; });
        },

        bulkDownload() {
            const form = document.createElement('form');
            form.method = 'POST'; form.action = '{{ route('admin.media.bulk-download') }}';
            const csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            this.selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                form.appendChild(input);
            });
            document.body.appendChild(form); form.submit(); form.remove();
        },

        openEditModal(item) {
            this.editingItem = item;
            this.editingItemData = { 
                filename: item.original_name || item.filename,
                alt_text: item.alt_text || '', 
                category: item.category || '' 
            };
        },

        async saveEdit() {
            try {
                // First save metadata
                await fetch(`/admin/media/${this.editingItem.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ alt_text: this.editingItemData.alt_text, category: this.editingItemData.category })
                });

                // Then rename file if changed
                if (this.editingItemData.filename !== (this.editingItem.original_name || this.editingItem.filename)) {
                    await fetch(`/admin/media/${this.editingItem.id}/rename`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ filename: this.editingItemData.filename })
                    });
                }

                this.editingItem = null;
                this.fetchMedia();
                this.showToast('✓ File Properties Updated');
            } catch (e) { console.error(e); }
        },

        async bulkDelete() {
            if (!confirm(`Hapus permanen ${this.selectedIds.length} aset?`)) return;
            try {
                const response = await fetch('{{ route('admin.media.bulk-delete') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ ids: this.selectedIds })
                });
                const data = await response.json();
                if (data.success) {
                    this.selectedIds = [];
                    this.fetchMedia();
                    this.showToast('✓ ' + data.message);
                }
            } catch (e) { console.error(e); }
        },

        deleteItem(id) {
            if (!confirm('Hapus permanen?')) return;
            fetch(`/admin/media/${id}`, { 
                method: 'DELETE', 
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                } 
            })
            .then(res => res.json())
            .then(data => { 
                this.fetchMedia(); 
                this.showToast('✓ Media berhasil dihapus');
            });
        },

        copyUrl(path) {
            const url = window.location.origin + '/storage/' + path;
            navigator.clipboard.writeText(url); this.showToast('✓ URL Copied');
        },

        showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-12 left-1/2 -translate-x-1/2 px-10 py-5 bg-slate-900 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-widest shadow-2xl z-[1000] animate-in slide-in-from-bottom duration-500 backdrop-blur-xl bg-opacity-95';
            toast.innerText = message;
            document.body.appendChild(toast);
            setTimeout(() => { toast.classList.add('animate-out', 'fade-out', 'duration-500'); setTimeout(() => toast.remove(), 500); }, 3000);
        }
    }
}
</script>
@endsection
