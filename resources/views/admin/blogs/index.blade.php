@extends('admin.layout')

@section('title', 'Blogs Management')
@section('page-title', 'Blogs')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Blog Posts</h1>
            <p class="text-gray-600 mt-1 text-sm">Create, edit and manage your blog articles</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> New Blog Post
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-gray-400"></i>Search
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search by title or content..." 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition"
                    >
                </div>

                <!-- Filter Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
                        <i class="fas fa-filter mr-2"></i> Apply Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Blogs Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Post</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-16 h-12 bg-gray-100 rounded-lg mr-3 overflow-hidden shadow-sm flex-shrink-0">
                                        @if($blog->image)
                                            <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $blog->title }}</p>
                                        <p class="text-xs text-gray-500">{{ Str::limit(strip_tags($blog->content), 50) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                {{ $blog->author ?? 'Admin' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <p class="font-medium text-gray-900">{{ $blog->createdAt->format('d M Y') }}</p>
                                <p class="text-xs">{{ $blog->createdAt->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $blog->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    <i class="fas fa-{{ $blog->status === 'published' ? 'check-circle' : 'clock' }} mr-1.5"></i>
                                    {{ ucfirst($blog->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-newspaper text-5xl mb-4"></i>
                                    <p class="text-lg font-bold text-gray-600">No blog posts found</p>
                                    <p class="text-sm text-gray-500 mt-1">Try adjusting your filters or create a new post</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($blogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
