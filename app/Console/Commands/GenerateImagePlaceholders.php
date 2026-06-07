<?php

namespace App\Console\Commands;

use App\Models\GalleryImage;
use App\Models\Media;
use App\Models\PackageImage;
use App\Support\Lqip;
use Illuminate\Console\Command;

/**
 * Backfill LQIP placeholder untuk gambar yang sudah ada di database.
 * Jalankan sekali setelah deploy: php artisan images:placeholders
 */
class GenerateImagePlaceholders extends Command
{
    protected $signature = 'images:placeholders {--force : Hitung ulang walau placeholder sudah ada}';

    protected $description = 'Generate LQIP (blur-up) placeholder untuk Package, Gallery, dan Media images';

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        $targets = [
            [PackageImage::class, 'image_path'],
            [GalleryImage::class, 'imageUrl'],
            [Media::class, 'path'],
        ];

        $total = 0;
        $done = 0;
        $skipped = 0;

        foreach ($targets as [$model, $column]) {
            // Media mungkin tidak punya kolom placeholder bila migrasi parsial; lewati dengan aman
            if (! \Schema::hasColumn((new $model)->getTable(), 'placeholder')) {
                continue;
            }

            $query = $model::query();
            if (! $force) {
                $query->whereNull('placeholder');
            }

            $rows = $query->get();
            $this->info(class_basename($model).': '.$rows->count().' baris diproses');
            $bar = $this->output->createProgressBar($rows->count());

            foreach ($rows as $row) {
                $total++;
                $path = $row->getAttribute($column);
                $lqip = Lqip::fromStoredPath($path);

                if ($lqip) {
                    // Simpan tanpa memicu event/timestamp berubah berlebihan
                    $model::withoutEvents(function () use ($row, $lqip) {
                        $row->placeholder = $lqip;
                        $row->saveQuietly();
                    });
                    $done++;
                } else {
                    $skipped++;
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
        }

        $this->info("Selesai. Total: {$total}, berhasil: {$done}, dilewati: {$skipped}");

        return self::SUCCESS;
    }
}
