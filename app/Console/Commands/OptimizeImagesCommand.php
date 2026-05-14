<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizeImagesCommand extends Command
{
    protected $signature = 'media:optimize {--quality=80 : WebP compression quality} {--force : Re-optimize existing webp files}';
    protected $description = 'Bulk convert existing PNG/JPG images to WebP to improve site performance';

    public function handle()
    {
        if (!extension_loaded('gd')) {
            $this->error('❌ PHP GD extension tidak aktif. Perintah ini tidak bisa dijalankan.');
            return;
        }

        $this->info('🚀 Memulai optimasi gambar massal...');
        
        $files = Storage::disk('public')->allFiles();
        $optimizedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            $bar->advance();
            
            // Hanya proses PNG dan JPG
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $skippedCount++;
                continue;
            }

            // Folder yang dilewati
            if (Str::contains($file, ['thumbnails/', 'framework/', 'sessions/'])) {
                $skippedCount++;
                continue;
            }

            $webpPath = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $file);

            // Cek jika sudah ada webp-nya
            if (Storage::disk('public')->exists($webpPath) && !$this->option('force')) {
                $skippedCount++;
                continue;
            }

            try {
                $realPath = Storage::disk('public')->path($file);
                $image = null;

                switch ($extension) {
                    case 'jpeg':
                    case 'jpg': $image = @\imagecreatefromjpeg($realPath); break;
                    case 'png':
                        $image = @\imagecreatefrompng($realPath);
                        if ($image) {
                            \imagepalettetotruecolor($image);
                            \imagealphablending($image, true);
                            \imagesavealpha($image, true);
                        }
                        break;
                }

                if ($image) {
                    ob_start();
                    \imagewebp($image, null, $this->option('quality'));
                    $webpData = ob_get_clean();
                    
                    Storage::disk('public')->put($webpPath, $webpData);
                    \imagedestroy($image);
                    $optimizedCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        $bar->finish();
        $this->info("\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("🎉 Optimasi Selesai!");
        $this->info("✅ Gambar Dikonversi ke WebP: {$optimizedCount}");
        $this->info("⏭️ File Dilewati: {$skippedCount}");
        if ($errorCount > 0) {
            $this->error("❌ Error/Gagal: {$errorCount}");
        }
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("Skor LCP Anda sekarang seharusnya jauh lebih baik!");
    }
}
