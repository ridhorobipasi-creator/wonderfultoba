<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    /**
     * Show the application error log (storage/logs/laravel.log) in the admin panel
     * so booking/system errors can be read without SSH.
     */
    public function index(Request $request)
    {
        $path = storage_path('logs/laravel.log');

        $entries = [];
        $fileSize = 0;
        $exists = file_exists($path);

        if ($exists) {
            $fileSize = filesize($path);
            $content = $this->tail($path);
            $entries = $this->parse($content);
        }

        // Filters
        $level = $request->get('level');
        $search = trim((string) $request->get('search'));

        $entries = array_filter($entries, function ($e) use ($level, $search) {
            if ($level && strtoupper($e['level']) !== strtoupper($level)) {
                return false;
            }
            if ($search !== '' && stripos($e['raw'], $search) === false) {
                return false;
            }

            return true;
        });

        // Newest first, cap at 300 for the page.
        $entries = array_slice(array_reverse(array_values($entries)), 0, 300);

        $levels = ['ERROR', 'WARNING', 'CRITICAL', 'ALERT', 'EMERGENCY', 'INFO', 'DEBUG'];

        return view('admin.error-logs.index', compact('entries', 'exists', 'fileSize', 'levels'));
    }

    /**
     * Empty the log file (truncate). Superadmin-only via route middleware.
     */
    public function clear()
    {
        $path = storage_path('logs/laravel.log');
        if (file_exists($path)) {
            file_put_contents($path, '');
        }

        return back()->with('success', 'Log berhasil dibersihkan.');
    }

    /**
     * Read only the last ~512KB of the log to stay memory-safe on large files.
     */
    private function tail(string $path, int $maxBytes = 524288): string
    {
        $size = filesize($path);
        $fh = fopen($path, 'rb');
        if ($fh === false) {
            return '';
        }

        if ($size > $maxBytes) {
            fseek($fh, -$maxBytes, SEEK_END);
            fgets($fh); // drop the partial first line
        }

        $content = stream_get_contents($fh);
        fclose($fh);

        return $content ?: '';
    }

    /**
     * Split raw log text into structured entries keyed by the [date] env.LEVEL header.
     */
    private function parse(string $content): array
    {
        if (trim($content) === '') {
            return [];
        }

        // Each entry starts at a line like: [2026-06-04 23:02:09] production.ERROR: ...
        $chunks = preg_split('/(?=^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\])/m', $content, -1, PREG_SPLIT_NO_EMPTY);

        $entries = [];
        foreach ($chunks as $chunk) {
            if (! preg_match('/^\[(.*?)\] (\w+)\.(\w+): (.*?)(\n|$)/s', $chunk, $m)) {
                continue;
            }

            $entries[] = [
                'time' => $m[1],
                'env' => $m[2],
                'level' => $m[3],
                'message' => trim($m[4]),
                'raw' => trim($chunk),
            ];
        }

        return $entries;
    }
}
