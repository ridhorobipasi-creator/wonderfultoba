<?php
header('Content-Type: application/json');

$publicStoragePath = __DIR__ . '/storage';
$exists = file_exists($publicStoragePath);
$isDir = is_dir($publicStoragePath);

$result = [
    'public_storage_exists' => $exists,
    'is_directory' => $isDir,
];

if ($isDir) {
    // Attempt to delete it if it's empty
    $deleted = @rmdir($publicStoragePath);
    $result['deleted_by_script'] = $deleted;
    if (!$deleted) {
        $result['error'] = error_get_last();
        // Check what's inside
        $result['contents'] = scandir($publicStoragePath);
    }
}

echo json_encode($result, JSON_PRETTY_PRINT);
