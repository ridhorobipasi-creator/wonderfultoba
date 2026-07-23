@props([
    'name' => 'image',
    'label' => 'Gambar',
    'value' => null, // media_id atau array media_ids untuk multiple
    'multiple' => false,
    'required' => false,
    'category' => 'general',
    'class' => '',
    'help' => null
])

<div {{ $attributes->merge(['class' => 'image-input-component ' . $class]) }} 
     x-data="imageInputHandler('{{ $name }}', {{ $multiple ? 'true' : 'false' }}, @js($value))">
    
    <label class="block text-sm font-bold text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500 ml-1">*</span>
        @endif
    </label>

    @if($help)
        <p class="text-xs text-gray-500 mb-3">{{ $help }}</p>
    @endif

    <!-- Media Library Picker Button -->
    <div class="flex flex-col gap-4 mb-4">
        <button type="button" @click="openMediaPicker()" 
                class="w-full border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition group bg-gray-50/50 flex flex-col items-center justify-center gap-3">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                <i class="fas fa-images text-2xl"></i>
            </div>
            <p class="text-lg font-black text-slate-800 tracking-tight">Pilih dari Media Library</p>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                {{ $multiple ? 'Pilih satu atau lebih gambar dari galeri' : 'Pilih gambar dari galeri pusat' }}
            </p>
        </button>
    </div>

    <!-- Selected Media Preview -->
    <div class="grid gap-4 mb-4" 
         :class="{{ $multiple ? '\'grid-cols-3 sm:grid-cols-4 md:grid-cols-6\'' : '\'grid-cols-1\'' }}" 
         x-show="selectedMedia.length > 0">
        <template x-for="(item, idx) in selectedMedia" :key="'media-' + item.id">
            <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-indigo-500 shadow-lg group">
                <img :src="item.thumbnail_url || item.url" 
                     :alt="item.alt_text || 'Selected image'"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-slate-900/40 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition flex items-center justify-center">
                    <button type="button" @click="removeSelectedMedia(idx)" 
                            class="w-11 h-11 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg hover:bg-rose-600 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                <!-- Hidden inputs for selected media -->
                <input type="hidden" 
                       :name="{{ $multiple ? "'{$name}_media_ids[]'" : "'{$name}_media_id'" }}" 
                       :value="item.id">
                <div class="absolute top-1 right-1 bg-indigo-600 text-[7px] text-white px-2 py-1 rounded-full font-black tracking-widest">LIBRARY</div>
            </div>
        </template>
    </div>

    <!-- Local File Upload -->
    <div class="mt-4 flex flex-col gap-2">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            Atau Upload Gambar Baru dari Perangkat
        </label>
        <input type="file" 
               :name="{{ $multiple ? "'{$name}[]'" : "'{$name}'" }}" 
               :multiple="{{ $multiple ? 'true' : 'false' }}"
               accept="image/*"
               @change="handleLocalFiles($event)" 
               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition cursor-pointer">
    </div>

    <!-- Local Files Preview -->
    <div class="grid gap-4 mt-4" 
         :class="{{ $multiple ? '\'grid-cols-3 sm:grid-cols-4 md:grid-cols-6\'' : '\'grid-cols-1\'' }}" 
         x-show="localPreviews.length > 0">
        <template x-for="(preview, idx) in localPreviews" :key="'local-' + idx">
            <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-green-500 shadow-lg group">
                <img :src="preview.url" 
                     :alt="preview.file.name"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-slate-900/40 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition flex items-center justify-center">
                    <button type="button" @click="removeLocalFile(idx)" 
                            class="w-11 h-11 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg hover:bg-rose-600 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                <div class="absolute top-1 right-1 bg-green-600 text-[7px] text-white px-2 py-1 rounded-full font-black tracking-widest">LOCAL</div>
            </div>
        </template>
    </div>

    <!-- Error Messages -->
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error($name . '_media_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error($name . '_media_ids')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
function imageInputHandler(fieldName, isMultiple, initialValue = null) {
    return {
        selectedMedia: [],
        localPreviews: [],
        fileInputRef: null,

        init() {
            this.fileInputRef = this.$el.querySelector('input[type="file"]');
            
            // Load initial selected media if provided
            if (initialValue) {
                this.loadInitialMedia(initialValue);
            }
        },

        async loadInitialMedia(value) {
            if (!value) return;
            
            const mediaIds = Array.isArray(value) ? value : [value];
            
            try {
                const response = await fetch('/admin/media/batch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ ids: mediaIds })
                });
                
                if (response.ok) {
                    const mediaItems = await response.json();
                    this.selectedMedia = mediaItems;
                }
            } catch (error) {
                console.error('Error loading initial media:', error);
            }
        },

        openMediaPicker() {
            const callback = isMultiple ? 
                (items) => {
                    // For multiple, append to existing selection
                    const newItems = items.filter(item => 
                        !this.selectedMedia.some(existing => existing.id === item.id)
                    );
                    this.selectedMedia.push(...newItems);
                } : 
                (item) => {
                    // For single, replace selection
                    this.selectedMedia = [item];
                };

            window.dispatchEvent(new CustomEvent('open-media-picker', {
                detail: { 
                    callback: callback,
                    multiple: isMultiple,
                    category: '{{ $category }}'
                }
            }));
        },

        removeSelectedMedia(index) {
            this.selectedMedia.splice(index, 1);
        },

        handleLocalFiles(event) {
            const files = Array.from(event.target.files || []);
            
            if (!isMultiple) {
                // For single mode, clear existing previews and selected media
                this.clearPreviews();
                this.selectedMedia = [];
            }
            
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.localPreviews.push({
                            file: file,
                            url: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },

        removeLocalFile(index) {
            this.localPreviews.splice(index, 1);
            
            // Clear file input and recreate it to remove the file
            if (this.fileInputRef) {
                const newInput = this.fileInputRef.cloneNode(true);
                newInput.value = '';
                this.fileInputRef.parentNode.replaceChild(newInput, this.fileInputRef);
                this.fileInputRef = newInput;
                
                // Re-attach event listener
                newInput.addEventListener('change', (e) => this.handleLocalFiles(e));
            }
        },

        clearPreviews() {
            this.localPreviews.forEach(preview => {
                URL.revokeObjectURL(preview.url);
            });
            this.localPreviews = [];
        }
    }
}
</script>
