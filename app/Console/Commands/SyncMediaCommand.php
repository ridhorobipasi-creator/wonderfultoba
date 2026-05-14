<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncMediaCommand extends Command
{
    protected $signature = 'media:sync {--category=gallery : Category for gallery images}';
    protected $description = 'Sync physical storage files to Media and Gallery tables';

    public function handle()
    {
        $this->info('Starting media sync...');
        
        $files = Storage::disk('public')->allFiles();
        $synced = 0;

        foreach ($files as $file) {
            // Skip non-images and thumbnails
            if (!Str::contains($file, ['.jpg', '.jpeg', '.png', '.webp', '.svg'])) continue;
            if (Str::contains($file, ['thumbnails/', '.gitignore'])) continue;

            // Check if already in Media table
            $exists = Media::where('path', $file)->exists();
            
            if (!$exists) {
                $media = Media::create([
                    'filename' => basename($file),
                    'original_name' => basename($file),
                    'path' => $file,
                    'category' => $this->getCategory($file),
                    'mime_type' => $this->getMime($file),
                    'size' => Storage::disk('public')->size($file),
                ]);

                // If it's in gallery folder, add to GalleryImage as well
                if (Str::startsWith($file, 'gallery/')) {
                    GalleryImage::create([
                        'caption' => basename($file),
                        'category' => $this->option('category'),
                        'imageUrl' => $file,
                        'isActive' => true,
                    ]);
                }
                
                $synced++;
                $this->line("Synced: {$file}");
            }
        }

        $this->info("Successfully synced {$synced} new files to database.");
    }

    private function getCategory($path)
    {
        if (Str::startsWith($path, 'gallery/')) return 'gallery';
        if (Str::startsWith($path, 'cms/')) return 'cms';
        if (Str::startsWith($path, 'branding/')) return 'branding';
        return 'other';
    }

    private function getMime($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return match($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream'
        };
    }
}
