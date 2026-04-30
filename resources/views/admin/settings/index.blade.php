@extends('admin.layout')

@section('title', 'Settings')
@section('page-title', 'Global System Configuration')

@section('content')
<div class="max-w-5xl mx-auto space-y-12 pb-32">
    <!-- Header -->
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">System Engine</h1>
        <p class="text-sm font-bold text-slate-400">Configure your website presence, landing pages, and contact points.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="space-y-12">
            
            <!-- 1. General Info -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-50 shadow-sm">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-globe text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-[12px] font-black text-slate-900 uppercase tracking-[0.2em]">General Identity</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Core website information</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Site Name</label>
                        <input type="text" name="general[site_name]" value="{{ $settings['general']['site_name'] ?? 'Wonderful Toba' }}" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-sm text-slate-900 transition">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Contact Email</label>
                        <input type="email" name="general[contact_email]" value="{{ $settings['general']['contact_email'] ?? '' }}" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-sm text-slate-900 transition">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Public Phone</label>
                        <input type="text" name="general[phone]" value="{{ $settings['general']['phone'] ?? '' }}" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-sm text-slate-900 transition">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">WhatsApp</label>
                        <input type="text" name="general[whatsapp]" value="{{ $settings['general']['whatsapp'] ?? '' }}" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-sm text-slate-900 transition">
                    </div>
                    <div class="md:col-span-2 space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Office Address</label>
                        <textarea name="general[address]" rows="2"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-sm text-slate-900 transition">{{ $settings['general']['address'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. Landing Page Configuration (Main) -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-50 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-toba-green flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-house text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-[12px] font-black text-slate-900 uppercase tracking-[0.2em]">Landing Page (Entrance)</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Split Landing: Tour vs Outbound</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Tour Side -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] pb-2 border-b border-slate-50">Tour Section</h3>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Title</label>
                                <input type="text" name="landing_page[tour][title]" value="{{ $settings['landing_page']['tour']['title'] ?? '' }}" 
                                    class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold text-xs">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subtitle</label>
                                <textarea name="landing_page[tour][subtitle]" rows="2" class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold text-xs">{{ $settings['landing_page']['tour']['subtitle'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Outbound Side -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] pb-2 border-b border-slate-50">Outbound Section</h3>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Title</label>
                                <input type="text" name="landing_page[outbound][title]" value="{{ $settings['landing_page']['outbound']['title'] ?? '' }}" 
                                    class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold text-xs">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subtitle</label>
                                <textarea name="landing_page[outbound][subtitle]" rows="2" class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold text-xs">{{ $settings['landing_page']['outbound']['subtitle'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Tour Landing (Specific) -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-50 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-map-marked text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-[12px] font-black text-slate-900 uppercase tracking-[0.2em]">Tour Page Hero</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Specific settings for /tour</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Hero Title</label>
                            <input type="text" name="tour_landing[hero][title]" value="{{ $settings['tour_landing']['hero']['title'] ?? '' }}" 
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Hero Subtitle</label>
                            <textarea name="tour_landing[hero][subtitle]" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm">{{ $settings['tour_landing']['hero']['subtitle'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-50">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-6">Why Choose Us (Tour)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            @for($i = 0; $i < 3; $i++)
                                <div class="bg-slate-50 p-6 rounded-3xl space-y-4">
                                    <input type="text" name="tour_landing[whyUs][items][{{$i}}][title]" value="{{ $settings['tour_landing']['whyUs']['items'][$i]['title'] ?? '' }}" placeholder="Item Title" class="w-full bg-white border-none rounded-xl text-xs font-bold">
                                    <textarea name="tour_landing[whyUs][items][{{$i}}][desc]" rows="2" placeholder="Item Description" class="w-full bg-white border-none rounded-xl text-xs font-bold">{{ $settings['tour_landing']['whyUs']['items'][$i]['desc'] ?? '' }}</textarea>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Outbound Landing (Specific) -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-50 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-users-gear text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-[12px] font-black text-slate-900 uppercase tracking-[0.2em]">Outbound Page Info</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Specific settings for /outbound</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">About Section Title</label>
                        <input type="text" name="outbound_landing[about][title]" value="{{ $settings['outbound_landing']['about']['title'] ?? '' }}" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Mission Statement</label>
                        <textarea name="outbound_landing[about][mission]" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm">{{ $settings['outbound_landing']['about']['mission'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sticky Save Bar -->
            <div class="sticky bottom-10 z-[60] flex justify-center">
                <button type="submit" class="bg-slate-900 text-white px-12 py-5 rounded-[2rem] text-[12px] font-black uppercase tracking-[0.3em] hover:bg-slate-800 transition shadow-2xl shadow-slate-300 transform active:scale-95">
                    <i class="fas fa-cloud-arrow-up mr-3"></i> Sync All System Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
