<?php
header('Content-Type: text/plain');
$logPath = __DIR__.'/../storage/logs/laravel.log';
if (file_exists($logPath)) {
    // Get the last 200 lines
    $lines = file($logPath);
    $lastLines = array_slice($lines, -200);
    echo implode("", $lastLines);
} else {
    echo "Log file not found.";
}
