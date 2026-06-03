<?php
header('Content-Type: text/plain');
$paths = [
    __DIR__.'/../storage/logs/laravel.log',
    __DIR__.'/storage/logs/laravel.log',
    '../storage/logs/laravel.log',
    'storage/logs/laravel.log'
];
$found = false;
foreach ($paths as $path) {
    if (file_exists($path)) {
        echo "Found at: $path\n";
        $lines = file($path);
        echo implode("", array_slice($lines, -300));
        $found = true;
        break;
    }
}
if (!$found) echo "Log file not found in any path.";
