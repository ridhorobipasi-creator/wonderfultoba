<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BlogController extends Controller
{
    use \App\Traits\LogsActivity, HandlesImageUploads;

    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    public function index(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $blogs = $query->latest()->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'category' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image_url' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
            'metaTitle' => 'nullable|string|max:255',
            'metaDescription' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadAndIndex($request->file('image'), 'blogs', 'blog', $validated['title']);
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author'] = $validated['author'] ?? auth()->user()->name;
        $validated['excerpt'] = $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160);
        $validated['published_at'] = $validated['published_at'] ?? now();

        $blog = Blog::create($validated);
        $this->logActivity('created', "Created new blog post: {$blog->title}", $blog);
        SyncController::triggerSync();

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'category' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image_url' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
            'metaTitle' => 'nullable|string|max:255',
            'metaDescription' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadAndIndex($request->file('image'), 'blogs', 'blog', $validated['title']);
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        if ($validated['title'] !== $blog->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['excerpt'] = $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160);

        $blog->update($validated);
        $this->logActivity('updated', "Updated blog post: {$blog->title}", $blog);
        SyncController::triggerSync();

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $title = $blog->title;
        $blog->delete();
        $this->logActivity('deleted', "Deleted blog post: {$title}");

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        Blog::whereIn('id', $ids)->delete();
        $this->logActivity('bulk_deleted', 'Bulk deleted '.count($ids).' blogs');

        return response()->json(['message' => 'Blogs deleted successfully']);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'blogs-export-'.date('Y-m-d').'.'.$format;

        $blogs = Blog::all();

        return \Excel::download(new class($blogs) implements FromCollection, WithHeadings, WithMapping
        {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return ['ID', 'Judul', 'Kategori', 'Penulis', 'Status', 'Tanggal Rilis'];
            }

            public function map($row): array
            {
                return [
                    $row->id,
                    $row->title,
                    $row->category,
                    $row->author,
                    strtoupper($row->status),
                    $row->published_at ? $row->published_at->format('Y-m-d') : '-',
                ];
            }
        }, $filename);
    }
}
