<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutboundVideo;
use Illuminate\Http\Request;

class OutboundVideoController extends Controller
{
    public function index()
    {
        $videos = OutboundVideo::latest()->get();
        return view('admin.outbound.videos.index', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtubeUrl' => 'required|url',
        ]);

        OutboundVideo::create($request->all());

        return redirect()->back()->with('success', 'Video added!');
    }

    public function destroy(OutboundVideo $video)
    {
        $video->delete();
        return redirect()->back()->with('success', 'Video removed!');
    }
}
