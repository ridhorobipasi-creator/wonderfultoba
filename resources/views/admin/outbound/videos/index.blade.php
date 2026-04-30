@extends('admin.layout')

@section('title', 'Outbound Videos')
@section('page-title', 'Testimonial Videos')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Videos</h1>
            <p class="text-gray-600 mt-1 text-sm">Add YouTube videos for testimonials and highlights</p>
        </div>
        <button class="inline-flex items-center justify-center bg-toba-green text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> Add Video
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($videos as $video)
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group">
                <div class="aspect-video bg-black relative">
                    <iframe class="w-full h-full" src="{{ $video->youtubeUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="p-6 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">{{ $video->title }}</h3>
                    <form action="{{ route('admin.outbound.videos.destroy', $video) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-gray-400 font-bold">No videos uploaded yet</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
