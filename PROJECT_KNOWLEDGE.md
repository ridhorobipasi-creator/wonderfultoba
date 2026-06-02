# PROJECT_KNOWLEDGE — sujailaketoba.com

Catatan teknis ringkas yang tidak tersurat dari kode. Lihat juga `CLAUDE.md` untuk aturan kerja.

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
