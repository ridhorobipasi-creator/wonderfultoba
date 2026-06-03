<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: application/json');

$action = $_GET['action'] ?? 'info';

if ($action === 'info') {
    echo json_encode([
        'count' => \App\Models\Media::count(),
        'latest' => \App\Models\Media::orderBy('id', 'desc')->take(20)->get(),
        'disk_public_path' => Storage::disk('public')->path(''),
    ], JSON_PRETTY_PRINT);
} elseif ($action === 'delete') {
    $id = $_GET['id'] ?? 0;
    $media = \App\Models\Media::find($id);
    if (!$media) {
        echo json_encode(['error' => 'Not found', 'id' => $id]);
        exit;
    }
    
    try {
        $path = $media->path;
        $res = $media->forceDelete();
        $file_deleted = false;
        
        if (!empty($path) && Storage::disk('public')->exists($path)) {
            $file_deleted = Storage::disk('public')->delete($path);
        }
        
        echo json_encode([
            'success' => true,
            'db_deleted' => $res,
            'file_deleted' => $file_deleted,
            'media' => $media,
            'still_exists_in_db' => \App\Models\Media::where('id', $id)->exists()
        ], JSON_PRETTY_PRINT);
    } catch (\Throwable $e) {
        echo json_encode([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], JSON_PRETTY_PRINT);
    }
}
