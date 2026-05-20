<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\GalleryImage;

$images = [
    ['title' => 'Pemandangan Danau Toba', 'path' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1200', 'category' => 'tour', 'caption' => 'Keindahan Danau Toba dari sudut pandang terbaik'],
    ['title' => 'Air Terjun Sipiso-piso', 'path' => 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=1200', 'category' => 'tour', 'caption' => 'Air terjun megah di ujung utara Danau Toba'],
    ['title' => 'Bukit Holbung', 'path' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200', 'category' => 'tour', 'caption' => 'Bukit teletubbies dengan latar belakang Danau Toba yang biru'],
    ['title' => 'Desa Tomok Samosir', 'path' => 'https://images.unsplash.com/photo-1588107873618-97ce79f223f0?w=1200', 'category' => 'tour', 'caption' => 'Desa wisata budaya Batak Toba'],
    ['title' => 'Puncak Gunung Sibayak', 'path' => 'https://images.unsplash.com/photo-1587600813959-19ec6b0a7082?w=1200', 'category' => 'tour', 'caption' => 'Trekking seru ke kawah Gunung Sibayak Berastagi'],
    ['title' => 'Pulau Samosir', 'path' => 'https://images.unsplash.com/photo-1629853965935-430c4554b5ea?w=1200', 'category' => 'tour', 'caption' => 'Pulau vulkanik di tengah-tengah Danau Toba'],
    ['title' => 'Batu Gantung', 'path' => 'https://images.unsplash.com/photo-1542456456-4222f7ebba27?w=1200', 'category' => 'tour', 'caption' => 'Tebing batu misterius dan legendaris di Parapat'],
    ['title' => 'Pusuk Buhit', 'path' => 'https://images.unsplash.com/photo-1626084042846-95ffc06023cb?w=1200', 'category' => 'tour', 'caption' => 'Gunung suci tempat asal mula suku Batak'],
    ['title' => 'Pasar Buah Berastagi', 'path' => 'https://images.unsplash.com/photo-1606990660601-576ec66c4333?w=1200', 'category' => 'tour', 'caption' => 'Pusat sayur dan buah segar di dataran tinggi Karo'],
    ['title' => 'Tangkahan', 'path' => 'https://images.unsplash.com/photo-1563212891-b6a3713093b1?w=1200', 'category' => 'tour', 'caption' => 'Surga tersembunyi untuk penangkaran gajah'],
];

GalleryImage::truncate();

foreach ($images as $index => $img) {
    GalleryImage::create([
        'imageUrl' => $img['path'],
        'title' => $img['title'],
        'description' => $img['caption'],
        'category' => $img['category'],
        'isActive' => true,
        'orderPriority' => $index + 1
    ]);
}

echo "Gallery seeded with 10 images!\n";
