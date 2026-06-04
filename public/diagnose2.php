<?php
$parent = dirname(__DIR__); // __DIR__ is public_html/public, so $parent is public_html
$grandParent = dirname($parent); // This is /home/.../sujailaketoba.com
$testDir = $grandParent . '/persistent_uploads';

$result = [
    'parent' => $parent,
    'grandParent' => $grandParent,
    'testDir' => $testDir,
    'dir_exists' => is_dir($testDir),
];

if (!is_dir($testDir)) {
    $result['mkdir_success'] = @mkdir($testDir, 0755, true);
    $result['mkdir_error'] = error_get_last();
}

$logoPath = $testDir . '/gallery/uploads/logo-1-1780548538.webp';
$result['logo_exists'] = file_exists($logoPath);

echo json_encode($result, JSON_PRETTY_PRINT);
