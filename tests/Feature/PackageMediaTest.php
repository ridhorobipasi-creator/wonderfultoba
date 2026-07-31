<?php

namespace Tests\Feature;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageMediaTest extends TestCase
{
    use RefreshDatabase;

    private int $urutan = 0;

    private function paket(array $extra = []): Package
    {
        $this->urutan++;

        return Package::create(array_merge([
            'slug' => 'paket-uji-media-'.$this->urutan,
            'name' => 'Paket Uji Media',
            'description' => 'Deskripsi uji.',
            'price' => 400,
            'duration' => '3D2N',
            'images' => [],
            'includes' => [],
            'excludes' => [],
            'status' => 'active',
        ], $extra));
    }

    public function test_tautan_youtube_dan_vimeo_jadi_url_sematan(): void
    {
        $paket = $this->paket(['videos' => [
            ['type' => 'link', 'src' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=30s', 'title' => 'Hari 1'],
            ['type' => 'link', 'src' => 'https://youtu.be/abc123XYZ', 'title' => ''],
            ['type' => 'link', 'src' => 'https://vimeo.com/76979871', 'title' => ''],
        ]]);

        $list = $paket->videoList();

        $this->assertCount(3, $list);
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $list[0]['url']);
        $this->assertSame('Hari 1', $list[0]['title']);
        $this->assertSame('https://www.youtube-nocookie.com/embed/abc123XYZ', $list[1]['url']);
        $this->assertSame('https://player.vimeo.com/video/76979871', $list[2]['url']);
    }

    public function test_tautan_video_berbahaya_dibuang_bukan_diteruskan(): void
    {
        // Nilai ini mendarat di atribut src <iframe>. Meloloskan skema
        // javascript:/data: dari form admin berarti satu akun admin yang jebol
        // bisa menanam skrip di halaman publik paling ramai.
        $paket = $this->paket(['videos' => [
            ['type' => 'link', 'src' => 'javascript:alert(1)'],
            ['type' => 'link', 'src' => 'data:text/html;base64,PHNjcmlwdD4='],
            ['type' => 'link', 'src' => 'https://situs-asing.example/video-halaman'],
        ]]);

        $this->assertSame([], $paket->videoList());
    }

    public function test_peta_hanya_menerima_domain_google(): void
    {
        $this->assertNull($this->paket(['mapEmbed' => 'https://penyerang.example/maps?q=1'])->mapEmbedUrl());
        $this->assertNull($this->paket(['mapEmbed' => '<iframe src="https://penyerang.example/x"></iframe>'])->mapEmbedUrl());
        $this->assertNull($this->paket(['mapEmbed' => ''])->mapEmbedUrl());

        // Kode <iframe> utuh dari Google Maps: yang diambil hanya src-nya.
        $iframe = '<iframe src="https://www.google.com/maps/embed?pb=!1m18" width="600" height="450"></iframe>';
        $this->assertSame('https://www.google.com/maps/embed?pb=!1m18', $this->paket(['mapEmbed' => $iframe])->mapEmbedUrl());

        // Koordinat mentah dirakit jadi URL sematan.
        $this->assertSame(
            'https://maps.google.com/maps?q=-2.6845,98.8756&z=14&output=embed',
            $this->paket(['mapEmbed' => ' -2.6845, 98.8756 '])->mapEmbedUrl()
        );
    }

    public function test_halaman_detail_tanpa_form_tetap_menampilkan_media_dan_menunjuk_canonical(): void
    {
        $paket = $this->paket([
            'videos' => [['type' => 'link', 'src' => 'https://youtu.be/abc123XYZ', 'title' => 'Cuplikan']],
            'mapEmbed' => '-2.6845, 98.8756',
        ]);

        $berform = $this->withSession(['locale' => 'id'])
            ->get(route('tour.package.detail', $paket->slug))->assertOk()->getContent();
        $tanpaForm = $this->withSession(['locale' => 'id'])
            ->get(route('tour.package.detail.plain', $paket->slug))->assertOk()->getContent();

        // Form pemesanan: ada di satu halaman, tidak di halaman lainnya.
        $this->assertStringContainsString('id="booking-form"', $berform);
        $this->assertStringNotContainsString('id="booking-form"', $tanpaForm);

        // Media yang sama muncul di KEDUANYA -- itu inti permintaannya.
        foreach (['berform' => $berform, 'tanpa form' => $tanpaForm] as $label => $html) {
            $this->assertStringContainsString('youtube-nocookie.com/embed/abc123XYZ', $html, "video hilang di halaman {$label}");
            $this->assertStringContainsString('maps.google.com/maps?q=-2.6845,98.8756', $html, "peta hilang di halaman {$label}");
        }

        // Halaman kembar tidak boleh saling menggerus di hasil pencarian.
        $canonical = route('tour.package.detail', $paket->slug);
        $this->assertStringContainsString('<link rel="canonical" href="'.$canonical.'">', $tanpaForm);
    }

    public function test_blok_sales_hanya_muncul_di_halaman_tanpa_form(): void
    {
        \App\Models\Setting::updateOrCreate(['key' => 'cms_tour'], ['value' => [
            'video_credit_note' => 'Semua video rekaman tim kami sendiri.',
            'detail_usp' => [
                ['title' => 'Masuk ke lokasi sulit', 'text' => 'Bukan cuma titik yang mudah dijangkau.'],
                ['title' => '', 'text' => 'baris kosong harus diabaikan'],
            ],
        ]]);

        $paket = $this->paket([
            'accommodations' => [
                ['night' => 2, 'name' => 'Hotel Malam Kedua', 'class' => 'Bintang 4', 'image' => ''],
                ['night' => 1, 'name' => 'Hotel Malam Pertama', 'class' => 'Bintang 3', 'image' => ''],
                ['night' => 3, 'name' => '', 'class' => 'tanpa nama, harus dibuang', 'image' => ''],
            ],
            'videos' => [
                ['type' => 'link', 'src' => 'https://youtu.be/abc123XYZ', 'title' => 'Hari 1', 'gear' => 'DJI Mavic 3'],
            ],
        ]);

        $tanpaForm = $this->withSession(['locale' => 'id'])
            ->get(route('tour.package.detail.plain', $paket->slug))->assertOk()->getContent();
        $berform = $this->withSession(['locale' => 'id'])
            ->get(route('tour.package.detail', $paket->slug))->assertOk()->getContent();

        // Blok sales lengkap di halaman tanpa form.
        $this->assertStringContainsString('Hotel Malam Pertama', $tanpaForm);
        $this->assertStringContainsString('Hotel Malam Kedua', $tanpaForm);
        $this->assertStringContainsString('Masuk ke lokasi sulit', $tanpaForm);
        $this->assertStringContainsString('DJI Mavic 3', $tanpaForm);
        $this->assertStringContainsString('Semua video rekaman tim kami sendiri.', $tanpaForm);
        $this->assertStringContainsString('Masih Ada Pertanyaan?', $tanpaForm);

        // Malam 1 harus tercetak sebelum malam 2 walau urutan datanya terbalik.
        //
        // Dicari HANYA di dalam blok penginapan: seluruh objek paket ikut
        // diserialisasi ke x-data lebih awal di halaman, jadi mencari di
        // seluruh HTML akan menemukan nama hotel di blob JSON itu -- yang
        // urutannya memang urutan input admin -- bukan di kartu terender.
        $blok = substr($tanpaForm, (int) strpos($tanpaForm, 'Menginap di Mana'));
        $this->assertLessThan(
            strpos($blok, 'Hotel Malam Kedua'),
            strpos($blok, 'Hotel Malam Pertama'),
            'penginapan harus urut menurut malam, bukan urutan input admin'
        );

        // Baris tanpa nama hotel tidak boleh jadi kartu kosong.
        $this->assertStringNotContainsString('tanpa nama, harus dibuang', $blok);

        // Halaman berform tetap ramping: tak satu pun blok sales ikut.
        //
        // Yang diperiksa PENANDA BLOKNYA, bukan nilai datanya. Seluruh objek
        // paket ikut diserialisasi ke x-data di kedua halaman, jadi mencari
        // 'Hotel Malam Pertama' akan selalu ketemu -- di payload Alpine,
        // bukan di blok yang dirender.
        $penanda = [
            'judul blok penginapan' => 'Menginap di Mana',
            'judul blok pembeda' => 'Kenapa Kami Berbeda',
            'judul CTA penutup' => 'Masih Ada Pertanyaan?',
            'lencana alat rekam' => 'text-[12px]">videocam',
        ];
        foreach ($penanda as $apa => $jangan) {
            $this->assertStringNotContainsString($jangan, $berform, "{$apa} seharusnya tidak ikut ke halaman berform");
            $this->assertStringContainsString($jangan, $tanpaForm, "{$apa} hilang di halaman tanpa form");
        }
    }

    public function test_tata_letak_poster_mobile_hanya_di_halaman_tanpa_form(): void
    {
        $paket = $this->paket([
            'videos' => [['type' => 'link', 'src' => 'https://youtu.be/abc123XYZ', 'title' => 'Hari 1']],
            'mapEmbed' => '-2.6845, 98.8756',
        ]);

        $tanpaForm = $this->withSession(['locale' => 'id'])
            ->get(route('tour.package.detail.plain', $paket->slug))->assertOk()->getContent();
        $berform = $this->withSession(['locale' => 'id'])
            ->get(route('tour.package.detail', $paket->slug))->assertOk()->getContent();

        $poster = [
            'media menembus tepi layar' => 'bleed-mobile',
            'batang WhatsApp lengket' => 'md:hidden fixed inset-x-0 bottom-0',
            'judul kapital berjarak' => 'uppercase tracking-[0.12em]',
            // Tanpa ruang bawah, batang lengket menutupi tombol terakhir
            // secara permanen -- tidak ada gejalanya selain tombol yang
            // "tidak bisa ditekan".
            'ruang bawah untuk batang lengket' => 'pb-28 md:pb-10',
        ];

        foreach ($poster as $apa => $penanda) {
            $this->assertStringContainsString($penanda, $tanpaForm, "{$apa} hilang di halaman tanpa form");
            $this->assertStringNotContainsString($penanda, $berform, "{$apa} seharusnya tidak ikut ke halaman berform");
        }
    }

    public function test_halaman_tanpa_form_memberi_jalan_memesan_lewat_kalkulator(): void
    {
        $paket = $this->paket();

        $html = $this->withSession(['locale' => 'id'])
            ->get(route('tour.package.detail.plain', $paket->slug))->assertOk()->getContent();

        // Tanpa form, tamu tetap harus punya jalan keluar: kalkulator pax
        // dengan tombol Booking (ke halaman berform) dan WhatsApp.
        $this->assertStringContainsString('paxCalc(', $html);
        $this->assertStringContainsString(':href="bookingUrl"', $html);
        $this->assertStringContainsString(':href="waUrl"', $html);
    }
}
