<?php

namespace Tests\Feature;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageCardDetailsTest extends TestCase
{
    use RefreshDatabase;

    private function makePackage(): Package
    {
        return Package::create([
            'slug' => 'paket-kartu-detail',
            'name' => 'Paket Kartu Detail',
            'shortDescription' => 'Ringkas',
            'description' => 'Lengkap',
            'images' => [],
            'includes' => ['Hotel bintang 3', 'Transportasi AC'],
            'excludes' => ['Tiket pesawat'],
            'itinerary' => [
                ['day' => 1, 'title' => 'Penjemputan - Parapat', 'activities' => ['Jemput bandara']],
                ['day' => 2, 'title' => 'Samosir', 'activities' => ['Tomok']],
            ],
            'pricingDetails' => [],
            'translations' => [],
            'price' => 500,
            'duration' => '2 Hari',
            'status' => 'active',
            'isFeatured' => true,
        ]);
    }

    public function test_grid_card_carries_the_summary_accordion(): void
    {
        $this->makePackage();

        $html = $this->get(route('tour.packages'))->assertOk()->getContent();

        // Kartu grid memberi pkgDetails ekspresi pkg.*, bukan nilai tetap.
        $this->assertStringContainsString('pkgDetails(pkg.includes', $html);
        // Locale default pengunjung baru = 'my' (LocaleCurrencyMiddleware).
        $this->assertStringContainsString('Butiran Pakej', $html);
    }

    public function test_accordion_panel_ids_are_generated_per_card_not_fixed(): void
    {
        // Di grid, partial ini dirender SEKALI lalu diulang x-for. Id yang
        // statis akan terpasang di semua kartu sekaligus, dan aria-controls
        // tiap tombol menunjuk panel kartu pertama.
        $this->makePackage();

        // Blade meng-escape kutip tunggal jadi &#039; di dalam atribut; browser
        // mengembalikannya sebelum Alpine membaca. Bandingkan setelah didekode.
        $html = html_entity_decode(
            $this->get(route('tour.packages'))->assertOk()->getContent(),
            ENT_QUOTES,
            'UTF-8'
        );

        $this->assertStringContainsString(':aria-controls=', $html);
        $this->assertStringContainsString("'pkg-detail-grid-' + pkg.id", $html);
        // id yang statis di dalam x-for akan terpasang kembar di semua kartu.
        $this->assertStringNotContainsString('aria-controls="pkg-detail-grid-"', $html);
    }

    public function test_accordion_is_labelled_for_screen_readers(): void
    {
        $this->makePackage();

        $html = $this->get(route('tour.packages'))->assertOk()->getContent();

        $this->assertStringContainsString(':aria-expanded=', $html);
    }
}
