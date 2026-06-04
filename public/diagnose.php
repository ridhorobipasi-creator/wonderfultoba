<?php
header('Content-Type: application/json');
$log = [];
$log['time'] = date('Y-m-d H:i:s');

// Check if the specific logo exists
$logoPath = __DIR__ . '/storage/gallery/uploads/logo-1-1780545528.webp';
$log['logo_exists'] = file_exists($logoPath);

// List contents of public/storage/gallery/uploads/
$uploadsDir = __DIR__ . '/storage/gallery/uploads';
$log['uploads_dir_exists'] = is_dir($uploadsDir);
if (is_dir($uploadsDir)) {
    $log['uploads_files'] = array_values(array_diff(scandir($uploadsDir), ['.', '..']));
}

// Check public/storage/.gitignore
$gitignorePath = __DIR__ . '/storage/.gitignore';
$log['gitignore_exists'] = file_exists($gitignorePath);
if (file_exists($gitignorePath)) {
    $log['gitignore_content'] = file_get_contents($gitignorePath);
}

echo json_encode($log, JSON_PRETTY_PRINT);
