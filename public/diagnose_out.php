<?php
$targetDir = dirname(__DIR__) . '/persistent_uploads';
$testFile = $targetDir . '/test.txt';

$result = [
    'target_dir' => $targetDir,
    'dir_exists_before' => is_dir($targetDir),
];

if (!is_dir($targetDir)) {
    $result['mkdir_success'] = @mkdir($targetDir, 0755, true);
} else {
    $result['mkdir_success'] = true;
}

if ($result['mkdir_success']) {
    $result['file_write'] = @file_put_contents($testFile, 'test');
    $result['file_exists'] = file_exists($testFile);
}

echo json_encode($result);
