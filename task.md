# Task — Redesign Besar Mobile-First (Sujai Laketoba)

Tujuan: seluruh situs **mobile-first, lancar, ringan, enak dilihat**. Dikerjakan bertahap agar tidak merusak situs yang sudah jalan.

## Bahasa Desain Baru "Toba Editorial"
- Mobile-first: rancang untuk layar kecil dulu, skala ke atas.
- Typografi: Plus Jakarta Sans, heading tebal tracking rapat, skala mobile naik bertahap.
- Ritme section konsisten: `py-16 md:py-24`, padding `px-5 md:px-8`.
- Kartu: `rounded-3xl`, shadow lembut, border tipis.
- Ringan: kurangi `backdrop-blur` berat (mahal di GPU mobile), gradient sederhana.
- Warna: emerald `primary` + aksen hangat, surface off-white.

## Fase
- [x] **Fase 0 — Fondasi performa (semua halaman)**
  - [x] Hapus font Outfit + Playfair Display yang tak terpakai
  - [x] FontAwesome di layout = deferred (non-render-blocking)
  - [x] Plus Jakarta Sans via preconnect+link
  - [x] Polling CMS 5s -> 30s + jeda saat tab non-aktif
- [~] **Fase 1 — Homepage flagship** (`tour/index.blade.php`)
  - [ ] Restyle hero slider (visual saja, mekanika JS utuh) — tunggu approval arah dulu
  - [x] Section paket unggulan (header eyebrow + ritme mobile py-16)
  - [x] Galeri showcase (header konsisten)
  - [x] Testimoni (eyebrow konsisten)
  - [x] Blok spesialis (rounded-3xl, center di mobile)
  - [x] Jurnal/blog (header eyebrow + grid gap mobile)
  - [x] FAQ (FIX bug `border` ganda + ritme)
  - [x] Cinema CTA (radius/padding/heading mobile-first)
- [ ] **Fase 2 — Approval user atas arah desain** <-- CHECKPOINT SEKARANG
- [~] **Fase 3 — Sebar ke halaman publik lain**
  - [x] packages (gutter px-5, heading bold, ritme mobile, CTA)
  - [x] gallery (gutter, heading bold, masonry gap mobile)
  - [x] blog (gutter, heading bold, featured radius mobile, grid gap)
  - [x] about (py-24->py-16 mobile, gutter, heading bold, CTA radius)
  - [x] footer (partial — gutter px-5)
  - [x] cars/index (gutter, heading bold)
  - [x] blog-detail (gutter, heading bold, padding mobile, related grid)
  - [x] package-detail (SUDAH mobile-first via token desain — tidak diubah)
  - [x] terms, privacy (gutter px-5, padding mobile, heading bold)
  - [x] payment (gutter, heading bold, header margin mobile)
- [x] **Fase 3 — Halaman publik SELESAI** (build hijau)
- [x] **Fase 4 — Panel admin** (SELESAI — build hijau)
  - [x] layout.blade.php sudah mobile-first (sidebar overlay+toggle+backdrop) — tambah FontAwesome deferred, font trim (drop italic), toast aman di mobile
  - [x] Semua tabel data dibungkus overflow-x-auto (fix tabel kabupaten di cities/index)
  - [x] Dashboard: grid sudah menumpuk rapi di mobile (verifikasi)
  - [x] Editor blog create/edit: padding mobile 96px/sisi -> wajar (p-5 sm:p-10 md:p-24)
  - [x] Filter bar index: sudah flex-wrap (verifikasi, aman)

## SELESAI SEMUA FASE ✅
Prinsip: yang sudah mobile-first (package-detail, admin layout, dashboard) TIDAK dirombak —
hanya diperbaiki titik yang benar-benar rusak (surgical). Sisanya diselaraskan ke bahasa
desain "Toba Editorial". Build hijau di setiap langkah.

## Catatan
- Slider = kode baru di-fix (commit ca373e0). Jangan ubah logika `activeIndex`/clones/drag.
- Semua binding data (`$packages`, `$blogs`, `$gallerySlides`, `$settings`) & route harus tetap utuh.

---

# Task Baru — Perbaikan UX & Alur (audit 2026-07-20)

Redesign visual sudah selesai. Babak ini soal **alur**: kebocoran antar-langkah yang
memakan booking, bukan soal tampilan. Semua item punya bukti `file:baris`.

Prinsip sama: surgical. Jangan rombak yang sudah jalan.

## Batch 1 — Kritis (memakan konversi / merusak trust)

- [ ] **Apostrof merusak form booking** — `tour/package-detail.blade.php:181,191-195`
  `old()` disuntik ke string literal JS di dalam atribut `x-data`. Blade escape `'` -> `&#039;`
  -> browser decode balik -> string putus -> SELURUH Alpine di halaman mati.
  Pemicu: user bernama "O'Brien" gagal validasi. Juga `pax: {{ old('pax', 1) }}` -> `pax: ,`
  bila field dikosongkan.
  Fix: pindahkan semua nilai `old()` ke satu blok `@json()`, jangan interpolasi ke literal.

- [ ] **Nomor tampil != nomor dituju** — `partials/footer.blade.php:128-130`, `navbar.blade.php:9,11`
  href -> `wa.me/6282277848855`, teks tampil `+62 813-2388-8207`. Dua nomor di satu elemen.
  Fix: satu variabel untuk href dan teks, hapus default yang berbeda.

- [ ] **Kode booking hilang saat refresh/back** — `PublicController.php:351`
  Pakai `back()->with()`, bukan redirect ke URL permanen.
  Fix: `redirect()->route('booking.track', $code)`. Sekaligus menyelesaikan panel sukses
  yang minim info (`package-detail.blade.php:678-681` cuma menampilkan Booking ID).

- [ ] **Auto-redirect WA 2 detik di tab yang sama** — `package-detail.blade.php:640-656`
  Fix: naikkan ke 8-10 detik + buka di tab baru, jangan `window.location.href`.

- [ ] **Testimoni fiktif auto-generate per kota** — `landing-origin.blade.php:378-383,401`
  "Wisatawan dari {kota}" di-`array_unshift` ke posisi teratas, 15 halaman.
  Risiko UU Perlindungan Konsumen. Fix: hapus, render section hanya bila `$settings['testimonials']` terisi.

- [ ] **Testimoni default homepage palsu** — `tour/index.blade.php:358-371`
  "Julian Thorne / London, UK" + avatar stok. Fix: sama, section kosong lebih baik.

- [ ] **Kartu paket tidak bisa dibuka keyboard** — `index.blade.php:131-132,450`,
  `landing-origin.blade.php:154-155,478`
  `<div onclick="window.location.href=...">`. Fix: jadikan `<a href>` + `focus-visible:ring`.
  Sekaligus memperbaiki SEO dan buka-di-tab-baru.

- [ ] **CTA mobile berbahasa Melayu untuk semua locale** — `layouts/app.blade.php:181`
  `__('Tempah')` — terverifikasi TIDAK ada di `en.json` maupun `id.json`.
  Fix: ganti key + tambahkan ke 3 file bahasa.

- [ ] **Kalkulator harga frontend != backend** — `package-detail.blade.php:239-241`
  vs `BookingService.php:270-272` (weekend + peak surcharge ditambah sebelum pajak).
  Default surcharge = 0 jadi BELUM aktif — tapi jadi bug diam begitu admin mengisinya.
  Fix: kirim surcharge ke frontend, atau blokir input surcharge sampai kalkulator disamakan.

## Batch 2 — Tinggi

- [ ] **Tidak ada jalan bayar** — `/payment` tidak di-link dari panel sukses, halaman track,
  maupun pesan WA. Dan `payment.blade.php:95,100` sama-sama "Hubungi kami untuk no. rekening".
- [ ] **Honeypot menjebak user asli** — `PublicController.php:253-261` mengembalikan success
  palsu berkode `BOT-xxxxxx` yang akan 404 di halaman track. Autofill bisa mengisi `website_url`.
- [ ] **Fallback "Private Jet Charter Rp 120jt"** — `package-detail.blade.php:184-186`
- [ ] **Mata uang berubah di detik terakhir** — frontend MYR/SGD, tapi WA/track/invoice
  hard-coded `'Rp '` (`PublicController.php:317`)
- [ ] **Legalitas nihil + alamat inkonsisten** — `terms.blade.php:17` "CV/UD" (ambigu),
  nol NIB/TDUP. Alamat: schema=Balige (`index.blade.php:45`) vs footer=Parapat (`:118`).
  -> KEPUTUSAN BISNIS, bukan kode.
- [ ] **Kebijakan refund tersembunyi** — sudah ditulis jelas di `terms.blade.php:37-43`
  tapi tidak ada di homepage/FAQ/dekat tombol booking. Fix: tambahkan 2 FAQ soal uang.
- [ ] **FAQ/Terms/Privacy/404 tidak diterjemahkan** — locale default `my`, target MYR/SGD,
  tapi halaman yang paling dibutuhkan pembeli asing hanya bahasa Indonesia.
  Fix struktural: pindahkan FAQ ke `lang/{locale}/faq.php` dengan key pendek.
- [ ] **16 `<h1>` di homepage** — `home-slider.blade.php:396` di dalam `x-for` (6 klon awal +
  N + 6 klon akhir). Fix: `<div>` di dalam loop, satu `<h1>` sr-only di luar.
- [ ] **Halaman pSEO kehilangan semua optimasi gambar** — `landing-origin.blade.php:84,156,422,446,480,543,576`
  tanpa srcset/lazy, padahal `index.blade.php` sudah punya. Ini justru jalur masuk utama dari Google.
- [ ] **404 tanpa navbar/footer** — `errors/404.blade.php` berdiri sendiri, semua jalur kontak
  lenyap saat user tersesat. Plus memuat FontAwesome CDN penuh untuk 3 ikon (`:10-15`).
- [ ] **Klaim partner tanpa bukti** — `about.blade.php:242-271` (Mandiri/USU/Pelindo/Hyundai +
  "Agen Resmi Wonderful Indonesia"). Logo di-hotlink dari Wikipedia (`:268`, `footer:180`).
  -> KEPUTUSAN BISNIS: buktikan atau hapus.

## Batch 3 — Sedang

Discovery:
- [ ] Filter kategori galeri mati — chip dari data yang query-nya sudah dibatasi ke `tour` (`TourService.php:198-207`)
- [ ] State filter tidak masuk URL — Back menghapus semua filter, hasil tidak bisa di-share
- [ ] Tidak ada pagination — seluruh dataset di-dump via `@js()`
- [ ] Search paket tidak mencakup nama kota — ketik "Samosir" bisa 0 hasil
- [ ] Tag artikel `<span>` bukan link, tapi punya hover style yang menipu (`blog-detail:141-149`)
- [ ] Tombol wishlist mati — `packages.blade.php:187`, `package-card.blade.php:42-46`
- [ ] Rating `4.8` hardcoded identik di semua kartu — `package-card.blade.php:34`
- [ ] Tidak ada breadcrumb; tidak ada halaman Kontak (semua CTA keluar ke WA)
- [ ] Judul kartu blog pakai `post.title` mentah, featured pakai `translated_title` (`blog.blade.php:119` vs `:149`)

Booking:
- [ ] `isAvailable()` selalu `true` (`BookingService.php:22-30`) — bisa booking hari ini juga
- [ ] Tanggal pulang dihitung backend tapi tidak pernah ditampilkan (`PublicController.php:270-278`)
- [ ] Kode booking salah -> 404 mentah, bukan pesan ramah (`PublicController.php:385`)
- [ ] Email sinkron + render PDF inline, gagalnya cuma `Log::warning` (`CustomerBookingNotification`, `BookingService.php:86`)
- [ ] `paxChildren` dikirim 2x dan tidak punya blok `@error` (`package-detail.blade.php:766,852`)

Mobile & a11y:
- [ ] Dot indicator setinggi 3px (`home-slider.blade.php:454,526`)
- [ ] Tap target 40px < 44px (`index.blade.php:108-115`, `home-slider.blade.php:518,532`)
- [ ] Nomor telepon disembunyikan di mobile (`navbar.blade.php:29`)
- [ ] Galeri auto-scroll 4s tanpa pause, abaikan `prefers-reduced-motion` (`index.blade.php:227-235`)
- [ ] `focus:outline-none` tanpa pengganti (`index.blade.php:496`, `navbar.blade.php:45`)
- [ ] Akordeon FAQ & dropdown bahasa tanpa ARIA
- [ ] Polling CMS bisa `reload()` mendadak saat user membaca (`app.blade.php:208`)
- [ ] Alt text bermasalah (`index.blade.php:515,548,317`; `about.blade.php:10,40`)
- [ ] Kontras di bawah 4.5:1 (`index.blade.php:562`, `footer.blade.php:138,175,184`)
- [ ] Belum ada `errors/500.blade.php` & `503.blade.php`
- [ ] `privacy.blade.php:12` pakai `date('d F Y')` -> "terakhir diperbarui" selalu hari ini
- [ ] Statistik beda: `about.blade.php:91` = 5k+ vs `index.blade.php:529` = 1.500+
- [ ] `lang/id.json` tertinggal (215 baris vs en 298 / my 296)

## Batch 1B — Kritis, jalur uang (audit lanjutan, terverifikasi)

- [ ] **Tidak ada persetujuan S&K / Kebijakan Privasi di form booking**
  `StoreBookingRequest.php:25-36` — TERVERIFIKASI tidak ada field `terms`/`consent`.
  Form `package-detail.blade.php:761-935` juga tanpa checkbox maupun link ke `/terms`.
  User menyerahkan nama, email, telepon, tanggal tanpa menyetujui apa pun.
  Relevan UU PDP No. 27/2022 dan PDPA (tamu SG/MY). Ini bukan sekadar UX.

- [ ] **Kebijakan refund tidak muncul di form** — nol rujukan ke `terms.blade.php:37-43`
  di sepanjang 1009 baris. User menekan "Pesan Sekarang" tanpa tahu batal <7 hari = hangus.

- [ ] **Label form tidak terhubung ke input — SELURUH form booking**
  `package-detail.blade.php:773,782,788,797,837,849,860,878` — `<label>` tanpa `for`,
  `<input>` tanpa `id`. TERVERIFIKASI di baris 773-774.
  Tap label tidak fokus ke input (menyulitkan di layar sentuh); screen reader tidak
  menyebutkan nama field. Ini form yang membawa uang.
  Polanya sudah dikuasai — `booking/lookup.blade.php:21-31` sudah benar. Tinggal ditiru.

- [ ] **Celah validasi** — `StoreBookingRequest.php:31` TERVERIFIKASI:
  `'pax' => 'required|integer|min:1'` tanpa `max` -> bisa kirim 99999 peserta.
  `'customerPhone' => 'required|string'` tanpa format -> "abc" lolos, konfirmasi WA gagal.
  `startDate` boleh hari ini -> tidak ada lead time minimal.

- [ ] **Blok "Rincian Biaya" kemungkinan tidak pernah tampil**
  `package-detail.blade.php:459` — `x-show="package.pricingDetails && package.pricingDetails.length > 0"`
  padahal `pricingDetails` dipakai sebagai objek asosiatif (`.tiers` :183, `.additional_services` :184,
  `.includes` :270). Objek JS tidak punya `.length` -> undefined -> falsy.
  PERLU UJI BROWSER dulu sebelum diubah, tapi polanya jelas keliru.

- [ ] **Dua sumber data berbeda untuk Termasuk/Tidak Termasuk**
  `:270,278` pakai `pricingDetails['includes']`; `:514,530` pakai `package.package_includes`.
  Salah satu hampir pasti kosong — dan "apa yang saya dapat?" adalah pertanyaan #2 pembeli.

- [ ] **Blok `sr-only` + `aria-hidden` berisi konten SEO** — `package-detail.blade.php:266`
  TERVERIFIKASI: `<section class="sr-only" id="ai-context" aria-hidden="true">` berisi h2, h3,
  harga, daftar includes/excludes. Tidak terlihat user MAUPUN screen reader — hanya crawler.
  Ini definisi teks tersembunyi; berisiko penalti Google.
  Ironisnya isinya justru yang dibutuhkan user (lihat item di atas). Tampilkan saja betulan.

- [ ] **Dua nomor WA berbeda di halaman pembayaran** — `payment.blade.php:119` menampilkan
  `+62 813-2388-8207`, `:157` menaut ke `wa.me/6282277848855`. Pola sama dengan footer,
  tapi di halaman pembayaran ini terbaca sebagai tanda penipuan.

- [ ] **Halaman track: nol `__()` di 212 baris** — `booking/track.blade.php`
  Tamu MY/SG yang SUDAH BAYAR tidak bisa membaca status pesanannya sendiri.
  Plus mata uang hard-coded `Rp` (`:140,151,156,163,169,173`) padahal mereka memilih dalam RM/SGD.
  Plus `Pajak & Layanan (11%)` ditulis keras di `:168` padahal nilainya dari settings.

- [ ] **Invoice: nol `__()` di 397 baris** — `invoice/show.blade.php`
  Dokumen yang diterima tamu internasional sepenuhnya bahasa Indonesia.

- [ ] **Tidak ada cara membatalkan/mengubah pesanan** — `track.blade.php` hanya menampilkan status.
  Minimal tampilkan tenggat refund spesifik pesanan itu ("Batalkan sebelum 3 Agustus untuk
  pengembalian 100%") — datanya sudah ada di `$booking->startDate`.

- [ ] **Tombol "Konsultasi Gratis" memanggil nomor WA sebagai telepon**
  `packages.blade.php:241` — `href="tel:+..."` ke `contact_whatsapp`.
  Kalau itu nomor WhatsApp Business, panggilan suara tidak diangkat.

- [ ] **`{!! !!}` menyuntik URL ke string JS** — `package-detail.blade.php:652`
  Nilainya dari server jadi risiko rendah, tapi gunakan `@json(session('whatsappUrl'))`.

- [ ] **`</style>` yatim** — `package-detail.blade.php:341` (dibuka :334, ditutup :339, lalu :341 lagi)

- [ ] **15 tautan kota di SETIAP halaman paket** — `package-detail.blade.php:389-411`
  URL `/tour/package/{slug}-dari-{kota}` yang isinya identik, hanya nama kota berbeda.
  User merasa tertipu; bagi Google mendekati doorway pages. Sama akarnya dengan
  testimoni pSEO di Batch 1.

- [ ] **Email tidak konsisten** — `payment.blade.php:76` & `terms.blade.php:61` = `info@`,
  tapi schema.org `index.blade.php:24` = `hello@`

- [ ] **`payment.blade.php:135` menyuruh user pakai toggle bahasa "di pojok kanan atas"**
  Di mobile toggle itu tombol ikon tanpa teks, dan bilahnya disembunyikan (`navbar.blade.php:29`).
  User mencari sesuatu yang tidak ada.

## Batch 1C — Invoice (dokumen keuangan, terverifikasi)

- [ ] **Invoice bergantung 3 CDN eksternal** — `invoice/show.blade.php:7-9` TERVERIFIKASI.
  Baris 7 = `cdn.tailwindcss.com`, yaitu Tailwind CDN build yang mengompilasi CSS di browser
  saat runtime — resmi TIDAK untuk produksi.
  Invoice dibuka di jaringan kantor, disimpan, dicetak, dibuka lagi berbulan kemudian.
  Bila CDN diblokir/mati, tamu menerima teks mentah tanpa gaya. Self-host via `@vite`.

- [ ] **"Harga Satuan" dihitung dari total termasuk pajak** — `:83` TERVERIFIKASI
  `$unitPrice = $booking->totalPrice / $pax;` lalu ditampilkan sebagai Harga Satuan (`:281`).
  Di dokumen keuangan, satuan x kuantitas jadi tidak sama dengan subtotal.
  Hanya di cabang fallback (tanpa `price_breakdown`) — tapi itu justru pesanan lama.

- [ ] **"Mohon transfer ke rekening berikut" lalu tidak ada rekening** — `:306` TERVERIFIKASI
  Instruksi transfer di `:306-308`, blok rekening dibungkus `@if($bankAccount)` di `:310`.
  Bila belum dikonfigurasi, tamu membaca instruksi yang menunjuk ruang kosong.
  Pola SAMA dengan `payment.blade.php:95,100`. Di dua tempat, situs meminta bayar
  tanpa memberi tahu ke mana.

- [ ] **Invoice selalu Rupiah tanpa catatan kurs** — `:348` `(IDR)` + seluruh nominal `Rp`.
  Tamu memilih dalam SGD/MYR, menerima tagihan Rupiah, tanpa kurs maupun jumlah asli.
  Tidak punya cara mencocokkan, dan tidak tahu berapa yang harus ditransfer dari banknya.

- [ ] **NPWP opsional padahal invoice memungut pajak** — `:128` `@if($taxId)` vs `:336`
  "Pajak & Layanan". Dokumen yang memungut PPN 11% tanpa NPWP bukan faktur pajak sah.
  -> KEPUTUSAN BISNIS: pastikan status PKP dengan akuntan. Bila belum PKP, yang perlu
  ditinjau adalah pungutan 11%-nya, bukan sekadar tampilannya.

- [ ] **Tidak ada tenggat pembayaran** — `terms.blade.php:29` menetapkan pelunasan maks 7 hari
  sebelum berangkat, tapi invoice tidak mencantumkan jatuh tempo. Datanya ada (`startDate`).
  Ini penyebab paling umum pesanan menggantung.

- [ ] **Label tanggal salah** — `:176` label "Tanggal Pesanan" menampilkan `startDate`
  (tanggal berangkat), baru fallback ke `created_at`.

- [ ] **Baris "Diskon - Rp 0" selalu nol** — `:340-341` ditulis keras, tidak ada logika diskon.

- [ ] **Nol `__()` di 397 baris** — `<html lang="id">` ditulis keras di `:2`.
  Nama paket juga tidak diterjemahkan (`:86`).

- [x] **`auth/login.blade.php` — TIDAK ADA MASALAH.** Label terhubung (`:52/57, 69/74, 86/90`),
  `@vite` tanpa CDN, loading state, route sudah `throttle:10,1`. Hanya logo tanpa `alt` (`:26`).
  Jangan diutak-atik.

## Batch 1D — Halaman pelacakan RUSAK TERLIHAT (TERVERIFIKASI, prioritas tertinggi)

- [ ] **Ikon timeline tampil sebagai teks mentah menimpa label**
  `booking/track.blade.php:112`:
  `<span class="material-symbols-outlined text-sm">{{ $isDone ? 'check' : 'radio_button_unchecked' }}</span>`

  Situs memakai font ikon yang DIPANGKAS (`resources/fonts/material-symbols-subset.py`).
  Terverifikasi: subset memuat `check_circle` dan `done`, TAPI TIDAK memuat `check`
  maupun `radio_button_unchecked`. Ligatur tidak ada -> browser menampilkan teksnya
  apa adanya: "check" dan "radio_button_unchecked" (22 karakter) meluber keluar
  lingkaran 32px dan menimpa label langkah.

  Cakupan terverifikasi: 45 ikon statis dipakai di seluruh view publik, SEMUA aman.
  Ini satu-satunya pemakaian nama ikon DINAMIS di view publik — dan justru yang rusak.
  Akar masalahnya: nama ikon di dalam `{{ }}` tidak akan pernah terbaca skrip subset.

  Fix tercepat (1 baris, tanpa build ulang): ganti `check` -> `done` (ada di subset),
  dan ganti lingkaran kosong dengan `<span class="h-2 w-2 rounded-full bg-slate-300">`.
  Fix yang benar: pakai SVG inline supaya tidak bergantung font sama sekali.

  ATURAN BARU: jangan pernah menulis nama ikon dinamis di `{{ }}`. Kalau terpaksa,
  tambahkan nama ikonnya manual ke `USED` di `material-symbols-subset.py`.

- [ ] Pindahkan/gitignore `image.png` yang tergeletak di root proyek

## Batch 1D-b — Bug logika timeline pelacakan (TERVERIFIKASI)

`booking/track.blade.php:43-55`. Penanda selesai memakai `$isDone = $stepNumber <= $currentStep`,
tapi `$currentStep` diisi meleset satu langkah di KETIGA status:

- [ ] **`completed` -> `currentStep = 3` padahal ada 4 langkah**
  Langkah "Trip Selesai" tetap abu-abu. Perjalanan yang sudah rampung terlihat menggantung.
- [ ] **`cancelled` -> `currentStep = 2` padahal ada 3 langkah**
  Langkah "Dibatalkan" tetap abu-abu, tampak seperti belum terjadi.
  Pembatalan justru yang paling harus jelas terbaca — dan sebaiknya merah, bukan abu.
- [ ] **`confirmed` -> `currentStep = 2`**
  Menandai "Menunggu Konfirmasi" sebagai posisi sekarang, padahal sudah dikonfirmasi.
  Langkah "Dikonfirmasi" tetap abu-abu.

Artinya: user yang pesanannya SUDAH dikonfirmasi, SUDAH selesai, atau SUDAH dibatalkan
sama-sama melihat timeline yang tidak mencerminkan keadaannya. Ini halaman yang dibuka
justru saat user cemas menunggu kabar.

Catatan tambahan: tidak ada garis penghubung antar langkah, jadi 4 baris itu terbaca
sebagai daftar terpisah, bukan progres. Perbaiki sekalian saat menyentuh blok ini.

## AKAR MASALAH — 6 pola, bukan 112 masalah terpisah

Ini bagian terpenting dari seluruh audit. Memperbaiki 6 pola ini menutup ~40 temuan sekaligus:

| Pola | Muncul di | Perbaikan |
|---|---|---|
| Nomor kontak tidak konsisten | footer, navbar, payment, invoice | satu sumber di config/CMS |
| Mata uang berubah setelah memesan | tracking, invoice, pesan WA | `AppCurrency` dipakai sampai invoice |
| Terjemahan berhenti tepat di jalur uang | payment, tracking, lookup, invoice | bungkus `__()` + lengkapi lang |
| Aturan refund ada tapi tidak pernah tampil | form, invoice, tracking, FAQ | tampilkan di 4 titik itu |
| Data palsu/placeholder tertinggal | testimoni, rating 4.8, private jet | hapus semua |
| Form tanpa label terhubung | HANYA form pemesanan | tiru pola `lookup.blade.php` |

Pola ke-5 dan ke-6 paling murah: menghapus dan menyalin pola yang sudah ada di repo sendiri.

---

# Task Baru — Pindah Basis Harga ke MYR (mulai 2026-07-21)

Keputusan bisnis: harga jual dikelola dalam Ringgit (pasar utama MY/SG),
**tetapi laporan keuangan tetap IDR** (kewajiban pajak Indonesia).

Dua sumbu itu tidak boleh dicampur. Aturannya:

| Disimpan MYR | Disimpan IDR |
|---|---|
| `packages.price`, `childPrice`, `dronePrice` | `cost_price`, `total_cost` (modal ke vendor lokal) |
| `pricingDetails.tiers[].price`, `.child_price` | `bookings.totalPrice_idr` (dasar laporan & pajak) |
| `pricingDetails.additional_services[].price` | seluruh pesanan lama (dibiarkan apa adanya) |

**Prinsip inti: pesanan adalah CATATAN, bukan label harga.**
Mata uangnya dibekukan saat pemesanan (`currency` + `exchange_rate_idr` +
`totalPrice_idr`). Jangan pernah menurunkan ulang nominal pesanan dari kurs
saat ini — itu membuat invoice yang sudah terbit dan omzet bulan lalu berubah
sendiri ketika admin menyunting kurs.

## Selesai

- [x] `CurrencyHelper` disusun ulang di sekitar kode mata uang eksplisit
  - `formatIn($amount, $currency)` -> untuk CATATAN (invoice, laporan, admin, ekspor). Tanpa konversi.
  - `formatPrice($myr, $locale)` -> untuk ETALASE. Dengan konversi.
  - `toIdr()` -> dipakai SEKALI saat pemesanan, lalu disimpan.
- [x] Migration `2026_07_21_000001_freeze_booking_currency` — aditif, baris lama dilabeli IDR rate 1, **nol konversi**
- [x] Migration `2026_07_21_000002_convert_package_prices_to_myr`
  - Gagal keras bila `PRICE_MIGRATION_MYR_IDR` belum diset (jangan menebak kurs)
  - Lewat begitu saja bila tabel packages kosong (install baru & test suite)
  - Penanda `finance.price_base_currency` mencegah konversi ganda
  - Menembus JSON `pricingDetails`
- [x] `BookingService`: bekukan currency/rate/IDR saat create; pajak `round(...,2)`;
      `total_spent` menjumlahkan `totalPrice_idr` (bukan campur IDR+MYR);
      hapus fallback palsu "Private Jet Charter 120000000"
- [x] `Booking` model: fillable + cast decimal (dulu `double`, membatalkan tujuan migration 0718)
- [x] Test suite hijau 48/48

## Selesai — sapuan tampilan

- [x] `layouts/app.blade.php` — `window.AppCurrency` di-seed dari `CurrencyHelper::CURRENCIES`; parameter `format()` diganti nama jadi `priceInMyr`
- [x] `invoice/show.blade.php` + `pdf/invoice.blade.php` — render pakai `currency` milik pesanan; label `(IDR)` jadi dinamis; ditambah padanan Rupiah + kurs terkunci + tanggalnya
- [x] `booking/track.blade.php` — ikut mata uang pesanan, padanan IDR, pajak baca `tax_percentage` (dulu ditulis keras 11%)
- [x] `PublicController.php` — pesan WA ikut mata uang pesanan
- [x] Agregasi omzet pindah ke `totalPrice_idr` — `DashboardService`, `ReportService`, `ReportController`, `finance/index`. Laba `totalPrice_idr - total_cost` kini sama-sama IDR.
- [x] Ekspor CSV/Excel dapat kolom `Mata Uang` + `Total (IDR)` — `FinancialExport`, `ReportController`, `FinanceController`
- [x] 15 view admin — singkatan "K"/"jt" dihapus, harga paket MYR, nominal pesanan ikut mata uangnya, modal tetap IDR
- [x] Form admin — prefix `RM`, `step="0.01"`, placeholder & default JS diperbaiki; `cost_price` tetap Rupiah + keterangan
- [x] schema.org `priceCurrency` -> `CurrencyHelper::PRICE_BASE`
- [x] Kalkulator Alpine `package-detail` — pembulatan pajak 2 desimal, cocok dengan `BookingService`
- [x] `settings/index.blade.php` — default kurs baca dari `CurrencyHelper`, tidak lagi `3500` sendiri
- [x] `tests/Feature/BookingCurrencyTest.php` — 6 test baru; yang terpenting membuktikan nominal pesanan TIDAK bergerak saat kurs diubah
- [x] Bug timeline `track.blade.php` — `$currentStep` meleset satu langkah di 3 status; pembatalan kini merah dengan ikon silang
- [x] Test suite 54/54 hijau, `npm run build` hijau

## Belum

- [ ] Regenerasi cache banner OG (`OgBannerService`) — harga ter-render ke dalam PNG. Jalankan setelah migrasi + pembulatan harga.
- [ ] `invoice/show.blade.php:83` — `$unitPrice = totalPrice / pax` dihitung dari total yang SUDAH termasuk pajak (temuan Batch 1C, belum disentuh)
- [ ] `finance/index.blade.php` — kartu ringkasan hanya menjumlahkan halaman aktif (paginasi 20/hal). Masalah lama, bukan dari perubahan ini.
- [ ] Sisa temuan audit lain di Batch 1/2/3 yang bukan soal mata uang

## Sebelum deploy — WAJIB

1. **Dump database dulu.** `down()` membagi balik dengan kurs yang sama, tapi
   pembulatan 2 desimal membuang sisa (terbukti: 1.500.000 -> 340,91 -> 1.500.004).
   Rollback adalah jaring pengaman, bukan pengganti backup.
2. Set `PRICE_MIGRATION_MYR_IDR` di `.env` server. Kurs 2026-07-21 ~4.390-4.450.
3. Setelah migrasi, **tinjau & bulatkan daftar harga manual** — RM 340,91 benar
   secara matematis tapi janggal secara komersial.

## Catatan Audit
- Yang SUDAH BAGUS dan jangan dirusak: ringkasan harga real-time
  (`package-detail.blade.php:884-908`), loading state anti double-submit (`:919-929`),
  halaman track yang informatif (`track.blade.php:102-204`), lookup hanya butuh kode,
  link track di navbar + footer, email invoice PDF otomatis, lightbox galeri dengan
  keyboard nav, empty state di semua halaman list, konsep honeypot (implementasinya saja yang perlu diperbaiki).
- Beberapa item bertanda KEPUTUSAN BISNIS tidak bisa diselesaikan dengan kode:
  NIB asli, alamat kantor yang benar, bukti kemitraan, testimoni pelanggan nyata.
  Menghapus klaim yang tak terbukti menaikkan trust lebih banyak daripada mempercantiknya.
