@extends('admin.layout')

@section('title', 'Car Details')
@section('page-title', 'Vehicle: ' . $car->name)

@section('content')
<div class="max-w-5xl">
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center text-sm font-black text-toba-green uppercase tracking-widest hover:text-emerald-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Fleet
        </a>
        <div class="flex gap-3">
            <a href="{{ route('admin.cars.edit', $car) }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition text-sm">
                <i class="fas fa-edit mr-2"></i> Edit Vehicle
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden p-8">
                <div class="aspect-video rounded-2xl overflow-hidden bg-gray-100 mb-8 border border-gray-100 shadow-inner">
                    @php $images = $car->images ?? []; @endphp
                    @if(count($images) > 0)
                        <img src="{{ $images[0] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                            <i class="fas fa-car text-6xl mb-4"></i>
                            <p class="font-bold uppercase tracking-widest text-xs">No Image Available</p>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <i class="fas fa-tag text-toba-green mb-2"></i>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Type</p>
                        <p class="font-black text-gray-900 text-sm">{{ $car->type }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <i class="fas fa-users text-blue-500 mb-2"></i>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Capacity</p>
                        <p class="font-black text-gray-900 text-sm">{{ $car->capacity }} Seats</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <i class="fas fa-cog text-orange-500 mb-2"></i>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Trans.</p>
                        <p class="font-black text-gray-900 text-sm uppercase">{{ $car->transmission }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <i class="fas fa-gas-pump text-red-500 mb-2"></i>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Fuel</p>
                        <p class="font-black text-gray-900 text-sm uppercase">{{ $car->fuel }}</p>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-50">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Description</h3>
                    <div class="prose prose-sm text-gray-600 max-w-none">
                        {!! $car->description ?? '<p class="italic text-gray-400">No description provided.</p>' !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Price & Status Sidebar -->
        <div class="space-y-8">
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden p-8 text-center">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-4">Rental Status</p>
                <div class="inline-flex px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest mb-8 {{ 
                    $car->status === 'active' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200'
                }}">
                    {{ $car->status }}
                </div>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Price per Day</p>
                        <p class="text-2xl font-black text-toba-green">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-gray-400 font-bold">(Self Drive)</p>
                    </div>
                    
                    @if($car->priceWithDriver)
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">With Driver</p>
                        <p class="text-xl font-black text-blue-600">Rp {{ number_format($car->priceWithDriver, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-8 pt-8 border-t border-gray-50">
                    <div class="flex items-center justify-center gap-4">
                        <div class="flex items-center text-xs font-bold text-gray-500">
                            <i class="fas fa-check-circle text-toba-green mr-2"></i> GPS
                        </div>
                        <div class="flex items-center text-xs font-bold text-gray-500">
                            <i class="fas fa-check-circle text-toba-green mr-2"></i> AC
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-[2rem] p-8 text-white shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="font-black text-lg">System Audit</h4>
                    <i class="fas fa-shield-alt text-toba-green"></i>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 font-bold uppercase">Created At</span>
                        <span class="font-black">{{ $car->createdAt->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 font-bold uppercase">Featured</span>
                        <span class="font-black text-{{ $car->isFeatured ? 'toba-green' : 'gray-500' }} uppercase">{{ $car->isFeatured ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 font-bold uppercase">Sort Order</span>
                        <span class="font-black">#{{ $car->sortOrder }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
