<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavbarStackingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ambil nilai z-index dari kelas Tailwind sembarang, mis. z-[130].
     */
    private function zOf(string $html, string $pattern): int
    {
        $this->assertMatchesRegularExpression($pattern, $html, 'elemen tidak ditemukan di navbar');
        preg_match($pattern, $html, $m);

        return (int) $m[1];
    }

    public function test_currency_dropdown_stacks_above_the_main_nav(): void
    {
        // Panel pilih bahasa/mata uang duduk di topbar tapi terbuka ke bawah,
        // melintasi batas topbar dan bertumpuk dengan isi <nav>. Tombol
        // "Hubungi Kami" di dalam nav menimpanya.
        //
        // z-[200] pada panelnya sendiri tidak menolong: nilai itu hanya
        // berlaku DI DALAM konteks penumpukan yang dibuat pembungkusnya.
        // Yang menentukan adalah z pembungkus itu terhadap z milik <nav>.
        $html = $this->get(route('index'))->assertOk()->getContent();

        $pembungkusDropdown = $this->zOf($html, '/class="relative z-\[(\d+)\]"/');
        $nav = $this->zOf($html, '/class="sticky top-0 [^"]*z-\[(\d+)\]"/');

        $this->assertGreaterThan(
            $nav,
            $pembungkusDropdown,
            "pembungkus dropdown (z-{$pembungkusDropdown}) harus di atas <nav> (z-{$nav}), ".
            'kalau tidak panelnya tertimpa isi nav'
        );
    }

    public function test_mobile_menu_still_covers_everything_in_the_navbar(): void
    {
        // Menu mobile adalah lapisan penuh layar; menaikkan dropdown mata uang
        // tidak boleh membuatnya menembus menu itu.
        $html = $this->get(route('index'))->assertOk()->getContent();

        $pembungkusDropdown = $this->zOf($html, '/class="relative z-\[(\d+)\]"/');
        $menuMobile = $this->zOf($html, '/class="lg:hidden fixed inset-0 z-\[(\d+)\]"/');

        $this->assertGreaterThan($pembungkusDropdown, $menuMobile);
    }
}
