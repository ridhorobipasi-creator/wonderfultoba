@extends('admin.layout')

@section('title', 'Site Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100 bg-gray-50/50">
            <h1 class="text-2xl font-black text-gray-900">General Settings</h1>
            <p class="text-gray-600 mt-1 text-sm">Configure your website information and contact details</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-8">
            @csrf

            <div class="space-y-8">
                <!-- Site Identity -->
                <section>
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                        <i class="fas fa-id-card mr-2 text-toba-green"></i> Site Identity
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Site Name</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Wonderful Toba' }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Site Title Tagline</label>
                            <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                    </div>
                </section>

                <!-- Contact Info -->
                <section>
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                        <i class="fas fa-phone mr-2 text-toba-green"></i> Contact Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">WhatsApp Number</label>
                            <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] ?? '' }}" placeholder="e.g., 628123456789"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Office Address</label>
                            <textarea name="contact_address" rows="2"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">{{ $settings['contact_address'] ?? '' }}</textarea>
                        </div>
                    </div>
                </section>

                <!-- Social Media -->
                <section>
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                        <i class="fas fa-share-alt mr-2 text-toba-green"></i> Social Media Links
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fab fa-instagram mr-1 text-pink-500"></i> Instagram</label>
                            <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fab fa-facebook mr-1 text-blue-600"></i> Facebook</label>
                            <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fab fa-tiktok mr-1 text-black"></i> TikTok</label>
                            <input type="text" name="social_tiktok" value="{{ $settings['social_tiktok'] ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                    </div>
                </section>

                <!-- SEO -->
                <section>
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                        <i class="fas fa-search mr-2 text-toba-green"></i> Global SEO
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="seo_keywords" value="{{ $settings['seo_keywords'] ?? '' }}" placeholder="Keywords separated by comma"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Global Meta Description</label>
                            <textarea name="seo_description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green transition">{{ $settings['seo_description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </section>

                <div class="pt-8 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-toba-green to-emerald-600 text-white px-10 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
                        <i class="fas fa-check-circle mr-2"></i> Save All Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
