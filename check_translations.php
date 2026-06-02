<?php
$en = json_decode(file_get_contents('lang/en.json'), true);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
$missing = [];
foreach ($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/__\([\'\"](.*?)[\'\"]\)/', $content, $matches);
        foreach ($matches[1] as $key) {
            if (!isset($en[$key]) && !in_array($key, $missing) && strlen($key) > 2) {
                $missing[] = $key;
            }
        }
    }
}
sort($missing);
echo count($missing) . " missing keys:\n";
foreach ($missing as $k) {
    echo "  \"$k\"\n";
}
