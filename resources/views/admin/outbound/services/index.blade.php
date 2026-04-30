@extends('admin.layout')

@section('title', 'Outbound Services')
@section('page-title', 'Outbound Services')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Services</h1>
            <p class="text-gray-600 mt-1 text-sm">Define your outbound service offerings</p>
        </div>
        <button onclick="openModal()" class="inline-flex items-center justify-center bg-toba-green text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> Add Service
        </button>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Service</th>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Icon</th>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Order</th>
                    <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($services as $service)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-8 py-5">
                            <p class="font-bold text-gray-900">{{ $service->title }}</p>
                            <p class="text-xs text-gray-500 line-clamp-1">{{ $service->description }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600">
                                <i class="fas fa-{{ $service->icon ?? 'star' }}"></i>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-gray-600">
                            {{ $service->sortOrder }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('admin.outbound.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete this service?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-16 text-center">
                            <p class="text-gray-400 font-bold">No services found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
