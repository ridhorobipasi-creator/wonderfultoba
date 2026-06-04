<?php
header('Content-Type: application/json');

// Get the base path dynamically
$isHostinger = strpos(__DIR__, 'public_html') !== false;
$basePath = dirname(__DIR__); // Should be the laravel root
$uploadsDir = $isHostinger 
              ? dirname($basePath) . '/persistent_uploads/gallery/uploads'
              : __DIR__ . '/storage/gallery/uploads';

$logoPath = $uploadsDir . '/logo-1-1780547549.webp';

$result = [
    'time' => date('Y-m-d H:i:s'),
    'is_hostinger' => $isHostinger,
    'uploads_dir_path' => $uploadsDir,
    'logo_exists' => file_exists($logoPath),
    'uploads_dir_exists' => is_dir($uploadsDir),
    'uploads_files' => is_dir($uploadsDir) ? array_values(array_diff(scandir($uploadsDir), ['.', '..'])) : [],
];

// Check public/storage/.gitignore
$gitignorePath = __DIR__ . '/storage/.gitignore';
$result['gitignore_exists'] = file_exists($gitignorePath);
if (file_exists($gitignorePath)) {
    $result['gitignore_content'] = file_get_contents($gitignorePath);
}

echo json_encode($result, JSON_PRETTY_PRINT);
