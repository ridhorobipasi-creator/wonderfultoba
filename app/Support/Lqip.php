<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Low Quality Image Placeholder (LQIP).
 *
 * Menghasilkan data-URI base64 mungil (JPEG ~24px) dari sebuah gambar tersimpan,
 * untuk dipakai sebagai placeholder blur-up sebelum gambar resolusi penuh dimuat.
 * Warna placeholder identik dengan foto aslinya, tampil seketika tanpa request jaringan.
 */
class Lqip
{
    /**
     * Buat LQIP dari path gambar (relatif terhadap disk public, atau path publik).
     * Mengembalikan null bila tidak bisa diproses (URL eksternal, file hilang, GD nonaktif).
     */
    public static function fromStoredPath(?string $path): ?string
    {
        if (empty($path) || ! extension_loaded('gd')) {
            return null;
        }

        // Lewati URL eksternal / data-URI yang sudah jadi
        if (preg_match('#^(https?:|//|data:|blob:)#i', $path)) {
            return null;
        }

        $abs = self::resolveAbsolute($path);
        if (! $abs || ! is_file($abs)) {
            return null;
        }

        return self::generate($abs);
    }

    /**
     * Resolusi path relatif ke path filesystem absolut (mengikuti logika imageUrl()).
     */
    private static function resolveAbsolute(string $path): ?string
    {
        $clean = ltrim($path, '/');

        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, strlen('storage/'));
        }

        // Utamakan varian .webp bila ada (konsisten dengan imageUrl())
        $ext = strtolower(pathinfo($clean, PATHINFO_EXTENSION));
        if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
            $webp = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $clean);
            if (Storage::disk('public')->exists($webp)) {
                $clean = $webp;
            }
        }

        if (Storage::disk('public')->exists($clean)) {
            return Storage::disk('public')->path($clean);
        }

        if (is_file(public_path($clean))) {
            return public_path($clean);
        }

        return null;
    }

    /**
     * Hasilkan data-URI dari file absolut menggunakan GD.
     */
    private static function generate(string $abs): ?string
    {
        try {
            $info = @getimagesize($abs);
            if (! $info) {
                return null;
            }

            [$w, $h] = $info;
            if ($w < 1 || $h < 1) {
                return null;
            }
            $mime = $info['mime'] ?? '';

            $src = match (true) {
                str_contains($mime, 'webp') => @imagecreatefromwebp($abs),
                str_contains($mime, 'png') => @imagecreatefrompng($abs),
                str_contains($mime, 'jpeg') => @imagecreatefromjpeg($abs),
                str_contains($mime, 'gif') => @imagecreatefromgif($abs),
                default => null,
            };
            if (! $src) {
                return null;
            }

            $targetW = 24;
            $targetH = max(1, (int) floor($h * ($targetW / $w)));

            $thumb = imagecreatetruecolor($targetW, $targetH);
            imagecopyresampled($thumb, $src, 0, 0, 0, 0, $targetW, $targetH, $w, $h);

            ob_start();
            imagejpeg($thumb, null, 40);
            $data = ob_get_clean();

            imagedestroy($src);
            imagedestroy($thumb);

            if (! $data) {
                return null;
            }

            return 'data:image/jpeg;base64,'.base64_encode($data);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
