<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncMediaLibrary extends Command
{
    protected $signature = 'media:sync {--fresh : Delete all records and re-index from scratch}';

    protected $description = 'Sync all files in public storage into the Media Library table';

    public function handle()
    {
        if ($this->option('fresh')) {
            Media::truncate();
            $this->info('Cleared all existing media records.');
        }

        $files = Storage::disk('public')->allFiles();
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $indexed = 0;
        $skipped = 0;

        $this->info('Found '.count($files).' total files in storage. Indexing...');
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            $bar->advance();

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            // Skip non-images and thumbnails
            if (! in_array($ext, $extensions) || str_contains($file, '/thumbnails/') || str_contains($file, 'temp/')) {
                $skipped++;

                continue;
            }

            // Skip already indexed
            if (Media::where('path', $file)->exists()) {
                $skipped++;

                continue;
            }

            // Determine category from path
            $category = 'uncategorized';
            $parts = explode('/', $file);
            if (count($parts) > 1) {
                if ($parts[0] === 'gallery' && isset($parts[1])) {
                    $category = $parts[1];
                } else {
                    $category = $parts[0];
                    if (is_numeric($category)) {
                        $category = 'uploads';
                    }
                }
            }

            try {
                $size = Storage::disk('public')->size($file);
                Media::create([
                    'filename' => basename($file),
                    'original_name' => basename($file),
                    'path' => $file,
                    'category' => $category,
                    'mime_type' => 'image/'.($ext === 'jpg' ? 'jpeg' : $ext),
                    'size' => $size,
                ]);
                $indexed++;
            } catch (\Exception $e) {
                $skipped++;
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Done! Indexed: {$indexed} | Skipped/Already exists: {$skipped}");
        $this->info('Total in Media Library: '.Media::count());

        return 0;
    }
}
