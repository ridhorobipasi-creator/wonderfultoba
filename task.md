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
