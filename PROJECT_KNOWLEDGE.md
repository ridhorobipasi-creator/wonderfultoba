# PROJECT_KNOWLEDGE — sujailaketoba.com

Catatan teknis ringkas yang tidak tersurat dari kode. Lihat juga `CLAUDE.md` untuk aturan kerja.

---

## Palet Merek

Semua warna didefinisikan sebagai token di `resources/css/app.css` blok `@theme`. **Jangan tulis hex mentah / arbitrary value (`text-[#...]`) di Blade** — tambahkan token baru kalau memang perlu.

**Hijau — hanya SATU. Jangan tambah varian baru.**

| Token | Hex | Peran | Kontras di putih |
|---|---|---|---|
| `--color-toba-green` | `#166534` | **satu-satunya hijau merek** — logo, link, ikon, tombol, aksen admin | 7.13:1 |
| `--color-primary` | `#052e16` | bukan warna identitas; hanya latar gelap besar (hero, band CTA) agar teks putih terbaca | 14.91:1 |
| `--color-toba-light` | `#f0fdf4` | bukan warna identitas; nyaris putih, untuk latar lembut | — |

Butuh hijau yang lebih terang/gelap untuk sesuatu? **Jangan bikin hex baru.** Pakai modifier opasitas Tailwind di atas token yang ada (`bg-toba-green/10`, `shadow-primary/30`) supaya nilainya tetap satu sumber.

**Oranye — warna aksi (CTA):**

| Token | Hex | Peran |
|---|---|---|
| `--color-toba-orange` | `#e67e22` | tombol pill, state aktif navbar |
| `--color-toba-orange-dark` | `#d35400` | hover tombol |

Aturan pakai: **hijau = identitas, oranye = ajakan bertindak.** Oranye jangan dipakai untuk teks isi karena kontrasnya hanya 2.85:1 di putih (gagal AA) — aman hanya sebagai latar tombol dengan teks putih.

`theme-color` di `layouts/app.blade.php` dan `theme_color` di `public/manifest.json` mengikuti `#166534`; ubah bertiga sekaligus kalau hijau merek berganti.

**Catatan build:** `app.css` punya `@source '../../storage/framework/views/*.php'`. Kalau cache view sedang hangat saat `npm run build`, CSS hasil build membengkak ~13 KB dari kelas duplikat. Jalankan `php artisan view:clear` sebelum build produksi.

**Kontras yang diketahui turun:** label lokasi & harga di preview slider `admin/cms/tour.blade.php` (baris 768, 775) adalah teks hijau di atas panel gelap. Dulu `#15803d` (3.56:1), sekarang `#166534` (2.50:1) — keduanya sudah gagal AA sejak awal. Kalau mau diperbaiki, ubah teksnya jadi putih, jangan tambah hijau baru.

**Utang teknis:** masih ada dua sistem penamaan token yang jalan berdampingan — token Material (`primary`, `secondary`, `surface`, `on-*`) dipakai halaman publik, token `toba-*` dipakai panel admin (±279 pemakaian). Nilainya sudah disatukan ke ramp yang sama, tapi penamaannya belum. Penggabungan penuh = sweep besar di `resources/views/admin`.

---

## Standar Mobile-First Tabel Admin

Seluruh halaman (user + admin) memakai Tailwind yang **mobile-first by default** (kelas tanpa prefix = mobil; `sm:`/`md:`/`lg:` menambah gaya saat layar membesar). Halaman user (`index`, `tour/package-detail`, navbar) sudah mobile-first kualitas tinggi — jangan ditulis ulang.

Untuk **tabel daftar di panel admin**, ikuti pola berikut (acuan asli: `admin/bookings/index.blade.php`):

1. **Sembunyikan kolom sekunder di HP**, tampilkan bertahap:
   - `hidden sm:table-cell` — muncul mulai layar kecil
   - `hidden md:table-cell` — muncul mulai tablet
   - `hidden lg:table-cell` — muncul mulai desktop
   - Terapkan kelas yang sama pada `<th>` **dan** `<td>` kolom tersebut.
2. **Surface info penting secara inline** di sel utama (nama) khusus HP dengan blok `md:hidden` / `sm:hidden` (mis. harga, status, tanggal).
3. **Padding adaptif**: sel tepi pakai `px-5 md:px-8` atau `px-5 md:px-10` agar tidak sempit di HP.
4. **Tombol aksi**: gunakan `md:opacity-0 group-hover:opacity-100` — JANGAN `opacity-0 group-hover:...` saja, karena di HP tidak ada hover sehingga tombol jadi tak terlihat & tak bisa ditekan. Pastikan `<tr>` punya kelas `group`.
5. `min-w-0` + `truncate` pada wadah teks agar tidak melebar/overflow.

### Status penerapan (per audit mobile-first)
Sudah sesuai pola: `bookings`, `cars`, `users`, `customers` (index & show), `blogs`, `cities` (tab kategori), `regencies`, `logs`, `finance`, `reports/financial`.
Grid kartu (sudah responsif, bukan tabel): `packages/index`, `cities` (tab destinasi), `media/index`.
