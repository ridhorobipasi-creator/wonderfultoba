<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Package;
use App\Models\Blog;
use App\Models\City;
use Illuminate\Support\Str;

echo "Fixing Package slugs...\n";
Package::all()->each(function($p) {
    $p->slug = Str::slug($p->name);
    $p->save();
});

echo "Fixing Blog slugs...\n";
Blog::all()->each(function($b) {
    $b->slug = Str::slug($b->title);
    $b->save();
});

echo "Fixing City slugs...\n";
City::all()->each(function($c) {
    $c->slug = Str::slug($c->name);
    $c->save();
});

echo "Done!\n";
