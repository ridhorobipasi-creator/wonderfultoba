@extends('admin.layout')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Packages
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Edit Package: {{ $package->name }}</h2>

        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package Name *</label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type & Status Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Type *</label>
                        <select name="isOutbound" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                            <option value="0" {{ old('isOutbound', $package->isOutbound) == '0' ? 'selected' : '' }}>Tour Package</option>
                            <option value="1" {{ old('isOutbound', $package->isOutbound) == '1' ? 'selected' : '' }}>Outbound Package</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status *</label>
                        <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                            <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Current Images & Upload -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package Images</label>
                    
                    @if($package->images && count($package->images) > 0)
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-4 mb-4">
                            @foreach($package->images as $image)
                                <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm group">
                                    <img src="{{ $image }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <label class="cursor-pointer bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 transition">
                                            <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="hidden">
                                            <i class="fas fa-trash-alt"></i>
                                        </label>
                                    </div>
                                    <div class="absolute top-1 right-1 bg-red-600 text-[10px] text-white px-1 rounded font-black delete-indicator hidden">TO DELETE</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-toba-green transition group bg-gray-50/50">
                        <input type="file" name="images[]" multiple id="images" class="hidden" accept="image/*" onchange="previewImages(event)">
                        <label for="images" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-toba-green transition mb-3"></i>
                            <p class="text-sm font-bold text-gray-700">Click to upload more images</p>
                            <p class="text-xs text-gray-500 mt-1">JPEG, PNG, JPG, WEBP (Max 2MB per image)</p>
                        </label>
                    </div>
                    <div id="image-preview" class="grid grid-cols-4 sm:grid-cols-6 gap-4 mt-4"></div>
                    <p class="text-xs text-gray-400 mt-2 italic">* Hover over existing images to delete them.</p>
                </div>

                <!-- Short Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Short Description</label>
                    <input type="text" name="shortDescription" value="{{ old('shortDescription', $package->shortDescription) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Description</label>
                    <textarea name="description" id="editor" rows="6"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">{{ old('description', $package->description) }}</textarea>
                </div>

                <!-- Location & Duration Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Location Tag</label>
                        <input type="text" name="locationTag" value="{{ old('locationTag', $package->locationTag) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Duration</label>
                        <input type="text" name="duration" value="{{ old('duration', $package->duration) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                    </div>
                </div>

                <!-- Price & Child Price Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (Adult) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="price" value="{{ old('price', $package->price) }}" required min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition @error('price') border-red-500 @enderror">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (Child)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" name="childPrice" value="{{ old('childPrice', $package->childPrice) }}" min="0" step="1000"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">City</label>
                    <select name="cityId"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        <option value="">Select City (Optional)</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('cityId', $package->cityId) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Featured Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="isFeatured" value="1" id="isFeatured" {{ old('isFeatured', $package->isFeatured) ? 'checked' : '' }}
                        class="w-5 h-5 text-toba-green border-gray-300 rounded focus:ring-toba-green">
                    <label for="isFeatured" class="ml-3 text-sm font-bold text-gray-700 cursor-pointer">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>Mark as Featured Package
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
                        <i class="fas fa-save mr-2"></i> Update Package
                    </button>
                    <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'lists link table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat',
        height: 300,
        branding: false,
        promotion: false
    });

    function previewImages(event) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        if (event.target.files) {
            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // Toggle delete indicator
    document.querySelectorAll('input[name="remove_images[]"]').forEach(input => {
        input.addEventListener('change', function() {
            const indicator = this.closest('.group').querySelector('.delete-indicator');
            if (this.checked) {
                indicator.classList.remove('hidden');
                this.closest('.group').classList.add('ring-2', 'ring-red-500');
            } else {
                indicator.classList.add('hidden');
                this.closest('.group').classList.remove('ring-2', 'ring-red-500');
            }
        });
    });
</script>
@endpush
