@extends('admin.layout')

@section('title', 'Blogs')
@section('page-title', 'Articles & Publications')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Blog Management</h1>
        <a href="{{ route('admin.blogs.create') }}" class="w-full sm:w-auto bg-slate-900 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200 text-center">
            <i class="fas fa-plus mr-2"></i> New Article
        </a>
    </div>

    <!-- Compact Filter Bar -->
    <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm">
        <form method="GET" class="flex items-center gap-4">
            <div class="flex-1 relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-bold text-[11px] text-slate-900 transition">
            </div>
            <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.blogs.index') }}" class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition">
                    <i class="fas fa-rotate-left text-xs"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Clean Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <tbody class="divide-y divide-slate-50">
                    @forelse($blogs as $blog)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                            <td class="px-6 md:px-8 py-6">
                                <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6">
                                    <div class="w-full md:w-20 h-32 md:h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                                        @if($blog->image)
                                            <img src="{{ $blog->image }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-newspaper text-slate-300 text-xs"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-black text-slate-900 tracking-tight leading-tight mb-1">{{ $blog->title }}</h4>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ $blog->author ?? 'Admin' }} &bull; {{ $blog->createdAt->diffForHumans() }}</p>
                                            <span class="px-2 py-0.5 rounded bg-slate-50 text-slate-400 text-[8px] font-black uppercase tracking-widest md:hidden">
                                                {{ $blog->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-6 hidden md:table-cell">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Status</p>
                                <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $blog->status === 'published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $blog->status }}
                                </span>
                            </td>

                            <td class="px-6 md:px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-slate-900 transition shadow-sm">
                                        <i class="fas fa-pencil text-[10px]"></i>
                                    </a>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-300 hover:text-rose-500 transition shadow-sm">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-32 text-center">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">No Blog Posts Available</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($blogs->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/20">
                {{ $blogs->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
