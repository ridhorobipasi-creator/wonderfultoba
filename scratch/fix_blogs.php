<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Blog;

$blogs = Blog::all();
foreach ($blogs as $blog) {
    echo "Blog Title: {$blog->title}\n";
    if (str_contains(strtolower($blog->title), 'halal') || str_contains(strtolower($blog->title), 'kuliner') || str_contains(strtolower($blog->title), 'makanan')) {
        $blog->content = "<p>Menemukan makanan halal di sekitar Danau Toba dan Medan sangatlah mudah jika Anda tahu tempat yang tepat. Sumatera Utara memiliki perpaduan budaya yang kaya, sehingga kulinernya pun sangat beragam.</p><p>Beberapa rekomendasi kuliner halal yang wajib Anda coba antara lain Mie Gomak (spaghetti Batak) versi halal, ikan mas arsik di restoran bersertifikat, dan berbagai olahan ayam napinadar. Pastikan selalu bertanya kepada pramusaji atau mencari logo halal sebelum memesan.</p><p>Sujai Laketoba selalu memastikan tamu kami dari Malaysia, Singapura, dan wilayah lain mendapatkan panduan tempat makan halal terbaik yang higienis, lezat, dan tentu saja 100% Halal.</p>";
        $blog->excerpt = "Panduan lengkap menemukan makanan halal dan kuliner khas Medan terbaik selama liburan di Danau Toba.";
        $blog->image = 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800';
        $blog->save();
        echo "Fixed blog: {$blog->title}\n";
    }
}

$b = new Blog();
$b->title = 'Panduan Makanan Halal di Danau Toba';
$b->slug = 'panduan-makanan-halal';
$b->content = '<p>Bagi wisatawan muslim, mencari makanan halal di Danau Toba tidaklah sulit. Berikut panduannya...</p>';
$b->excerpt = 'Panduan lengkap restoran dan rumah makan halal di sekitar Danau Toba.';
$b->image = 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800';
$b->status = 'published';
$b->author = 'Admin';
$b->category = 'Kuliner';
$b->save();
echo "Added Halal blog.\n";

echo "Blogs fixed.\n";
