<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

app()->setLocale('my');
$blogsMy = App\Models\Blog::all();
echo "MY blogs count: " . $blogsMy->count() . "\n";

app()->setLocale('id');
$blogsId = App\Models\Blog::all();
echo "ID blogs count: " . $blogsId->count() . "\n";
