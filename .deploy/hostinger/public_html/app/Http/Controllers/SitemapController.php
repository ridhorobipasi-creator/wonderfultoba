<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $packages = DB::table('packages')->where('status', 'active')->get();
        $blogs = DB::table('blogs')->where('status', 'published')->get();

        $content = view('sitemap', compact('packages', 'blogs'))->render();

        return Response::make($content, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    public function robots()
    {
        $content = view('robots')->render();
        return Response::make($content, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
