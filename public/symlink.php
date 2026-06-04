<?php
$target = dirname(__DIR__) . '/persistent_uploads';
$link = __DIR__ . '/storage';

echo "<pre>";
if (file_exists($link)) {
    echo "Exists... removing: ";
    if (is_dir($link)) {
        echo rmdir($link) ? 'OK' : 'FAIL';
    } else {
        echo unlink($link) ? 'OK' : 'FAIL';
    }
    echo "\n";
}

echo "Target exists: " . (file_exists($target) ? 'Yes' : 'No') . "\n";
echo "Symlinking $target -> $link\n";
$result = @symlink($target, $link);
echo "Result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
if (!$result) {
    print_r(error_get_last());
}
echo "</pre>";
