# 🏠 PANDUAN CMS HOMEPAGE

## 📅 21 April 2026

---

## 🎯 OVERVIEW

Sistem CMS (Content Management System) Homepage memungkinkan admin untuk mengatur **semua konten** yang tampil di halaman utama website tanpa perlu coding.

### ✨ Fitur CMS Homepage

1. **Hero Section** - Banner utama website
2. **Slider Beranda** - Carousel destinasi wisata
3. **Mengapa Kami** - Keunggulan perusahaan
4. **Testimoni** - Review dari pelanggan
5. **Statistik** - Pencapaian perusahaan
6. **Featured Packages** - Paket unggulan (otomatis dari database)

---

## 📂 STRUKTUR CMS

### 🏠 CMS Beranda Tour
**URL:** `/admin/cms-tour`  
**File:** `src/pages/AdminTourLanding.tsx`

Mengatur konten untuk halaman: `http://localhost:3000/`

### 🏢 CMS Beranda Outbound
**URL:** `/admin/cms-outbound`  
**File:** `src/pages/AdminOutboundLanding.tsx`

Mengatur konten untuk halaman: `http://localhost:3000/outbound`

---

## 🎨 SECTION 1: HERO SECTION

### Apa itu Hero Section?
Banner besar pertama yang dilihat pengunjung saat membuka website.

### Field yang Bisa Diatur:
```
1. Judul Hero
   - Contoh: "Explore the Beauty of Lake Toba"
   - Tampil besar di tengah hero

2. Sub-Judul
   - Contoh: "Nikmati pengalaman tak terlupakan bersama kami"
   - Tampil di bawah judul

3. URL Gambar Hero
   - Contoh: "https://images.unsplash.com/photo-..."
   - Background hero section
```

### Cara Mengatur:
1. Login ke admin panel
2. Klik **"🏠 CMS Beranda Tour"** di sidebar
3. Pilih tab **"Hero Section"**
4. Isi field yang tersedia
5. Klik **"Simpan Perubahan"**

### Tips:
- ✅ Gunakan gambar beresolusi tinggi (min 1920x1080px)
- ✅ Judul maksimal 60 karakter agar tidak terpotong
- ✅ Sub-judul maksimal 120 karakter
- ✅ Gunakan gambar dari Unsplash atau upload ke Media Library

---

## 🎠 SECTION 2: SLIDER BERANDA

### Apa itu Slider Beranda?
Carousel yang menampilkan destinasi wisata populer dengan animasi slide.

### Field per Slide:
```
1. Judul Slide
   - Contoh: "Danau Toba"
   - Nama destinasi

2. Wilayah/Region
   - Contoh: "Tobasa, Sumatera Utara"
   - Lokasi destinasi

3. Deskripsi
   - Contoh: "Jelajahi keajaiban danau vulkanik terbesar di Asia Tenggara"
   - Deskripsi singkat (max 200 karakter)

4. URL Gambar Background
   - Background slide

5. URL Gambar Kartu
   - Gambar kecil di card

6. Durasi
   - Contoh: "4 Hari 3 Malam"
   - Durasi paket

7. Harga Mulai
   - Contoh: "3500000"
   - Harga dalam angka (tanpa Rp)
```

### Cara Mengatur:
1. Pilih tab **"Slider Beranda"**
2. Klik **"+ Tambah Slide Baru"** untuk menambah
3. Isi semua field
4. Klik ikon **Trash** untuk menghapus slide
5. Klik **"Simpan Perubahan"**

### Tips:
- ✅ Maksimal 5-7 slide agar tidak terlalu lama
- ✅ Gunakan gambar landscape (16:9)
- ✅ Urutkan dari destinasi paling populer
- ✅ Pastikan harga up-to-date

---

## 🛡️ SECTION 3: MENGAPA KAMI

### Apa itu Section Mengapa Kami?
Menampilkan keunggulan dan alasan mengapa pelanggan harus memilih perusahaan Anda.

### Field:
```
1. Judul Utama Section
   - Contoh: "Pengalaman Wisata Terbaik di Sumut"
   - Judul section

2. Daftar Poin Keunggulan (Multiple)
   - Judul: "Pemandu Berpengalaman"
   - Ikon: "Shield" (nama ikon Lucide)
   - Deskripsi: "Tim guide profesional dengan sertifikasi resmi"
```

### Ikon yang Tersedia (Lucide Icons):
```
Shield, Clock, Globe, Heart, Award, Star, 
Users, MapPin, CheckCircle, Compass, etc.
```

**Lihat semua:** https://lucide.dev/icons/

### Cara Mengatur:
1. Pilih tab **"Mengapa Kami"**
2. Isi judul utama section
3. Klik **"+ Tambah Poin"** untuk menambah keunggulan
4. Isi judul, pilih ikon, dan tulis deskripsi
5. Klik ikon **Trash** untuk menghapus poin
6. Klik **"Simpan Perubahan"**

### Tips:
- ✅ Maksimal 6 poin agar tidak terlalu panjang
- ✅ Gunakan ikon yang relevan dengan keunggulan
- ✅ Deskripsi singkat dan jelas (max 100 karakter)
- ✅ Fokus pada unique selling point (USP)

---

## 💬 SECTION 4: TESTIMONI

### Apa itu Section Testimoni?
Menampilkan review dan testimoni dari pelanggan yang puas.

### Field per Testimoni:
```
1. Nama Pelanggan
   - Contoh: "Budi Santoso"
   - Nama lengkap pelanggan

2. Peran / Role
   - Contoh: "Traveler dari Jakarta"
   - Asal atau profesi

3. URL Avatar
   - Foto profil pelanggan
   - Bisa pakai foto dummy dari UI Faces

4. Rating (1-5)
   - Contoh: 5
   - Rating bintang

5. Isi Testimoni
   - Contoh: "Pengalaman luar biasa! Guide sangat ramah..."
   - Testimoni lengkap (max 300 karakter)
```

### Cara Mengatur:
1. Pilih tab **"Testimoni"**
2. Klik **"+ Tambah Testimoni Baru"**
3. Isi semua field
4. Klik ikon **Trash** untuk menghapus
5. Klik **"Simpan Perubahan"**

### Tips:
- ✅ Gunakan testimoni asli dari pelanggan
- ✅ Minta izin sebelum menampilkan nama dan foto
- ✅ Maksimal 6 testimoni di homepage
- ✅ Pilih testimoni yang spesifik dan detail
- ✅ Variasikan rating (tidak semua 5 bintang)

### Sumber Foto Avatar Dummy:
- https://ui-avatars.com/api/?name=Budi+Santoso
- https://i.pravatar.cc/150?img=1
- https://randomuser.me/api/portraits/men/1.jpg

---

## 📊 SECTION 5: STATISTIK KEBERHASILAN

### Apa itu Section Statistik?
Menampilkan pencapaian perusahaan dalam angka (social proof).

### Field per Statistik:
```
1. Ikon (Lucide)
   - Contoh: "Users"
   - Ikon yang merepresentasikan statistik

2. Nilai (Value)
   - Contoh: "500+"
   - Angka pencapaian

3. Label
   - Contoh: "Wisatawan Puas"
   - Deskripsi statistik
```

### Contoh Statistik:
```
1. Users | 1000+ | Wisatawan Puas
2. Star | 4.9/5 | Rating Kepuasan
3. MapPin | 50+ | Destinasi Tersedia
4. Award | 10+ | Penghargaan
```

### Cara Mengatur:
1. Pilih tab **"Statistik Keberhasilan"**
2. Klik **"+ Tambah Statistik Baru"**
3. Isi ikon, nilai, dan label
4. Klik ikon **Trash** untuk menghapus
5. Klik **"Simpan Perubahan"**

### Tips:
- ✅ Gunakan angka yang realistis dan bisa dibuktikan
- ✅ Maksimal 4 statistik agar tidak terlalu ramai
- ✅ Update secara berkala sesuai pencapaian terbaru
- ✅ Gunakan format yang menarik (500+, 4.9/5, dll)

---

## 🎯 FEATURED PACKAGES (OTOMATIS)

### Apa itu Featured Packages?
Paket wisata unggulan yang tampil di homepage.

### Cara Kerja:
**OTOMATIS** - Tidak perlu diatur di CMS!

Sistem akan otomatis menampilkan paket yang:
1. Status: **Active**
2. Is Featured: **✅ Checked**
3. Diurutkan berdasarkan **Sort Order**

### Cara Mengatur Featured Package:
1. Buka **"Paket Wisata"** di sidebar
2. Edit paket yang ingin dijadikan featured
3. Scroll ke tab **"Status & SEO"**
4. Centang **"Jadikan Paket Unggulan"**
5. Atur **Sort Order** (angka kecil tampil duluan)
6. Simpan

### Tips:
- ✅ Maksimal 3-6 paket featured
- ✅ Pilih paket yang paling laris
- ✅ Update secara berkala sesuai musim
- ✅ Pastikan gambar dan harga up-to-date

---

## 🔄 WORKFLOW LENGKAP

### Setup Awal Homepage (First Time)

#### 1. Setup Hero Section (5 menit)
```
1. Login admin
2. Klik "🏠 CMS Beranda Tour"
3. Tab "Hero Section"
4. Isi:
   - Judul: "Explore the Beauty of Lake Toba"
   - Sub-judul: "Nikmati pengalaman tak terlupakan..."
   - Gambar: Upload ke Media Library atau pakai Unsplash
5. Simpan
```

#### 2. Setup Slider (15 menit)
```
1. Tab "Slider Beranda"
2. Tambah 5 slide untuk destinasi populer:
   - Danau Toba
   - Samosir
   - Berastagi
   - Tangkahan
   - Bukit Lawang
3. Isi semua field per slide
4. Simpan
```

#### 3. Setup Mengapa Kami (10 menit)
```
1. Tab "Mengapa Kami"
2. Judul: "Pengalaman Wisata Terbaik di Sumut"
3. Tambah 4-6 poin keunggulan:
   - Pemandu Berpengalaman (Shield)
   - Harga Terjangkau (DollarSign)
   - Layanan 24/7 (Clock)
   - Asuransi Perjalanan (Shield)
4. Simpan
```

#### 4. Setup Testimoni (15 menit)
```
1. Tab "Testimoni"
2. Tambah 3-6 testimoni pelanggan
3. Gunakan testimoni asli atau dummy
4. Pastikan foto avatar tersedia
5. Simpan
```

#### 5. Setup Statistik (5 menit)
```
1. Tab "Statistik Keberhasilan"
2. Tambah 4 statistik:
   - 1000+ Wisatawan Puas
   - 4.9/5 Rating Kepuasan
   - 50+ Destinasi Tersedia
   - 10+ Penghargaan
3. Simpan
```

#### 6. Setup Featured Packages (10 menit)
```
1. Buka "Paket Wisata"
2. Edit 3 paket terbaik
3. Centang "Jadikan Paket Unggulan"
4. Atur Sort Order: 1, 2, 3
5. Simpan
```

**Total waktu setup: ~60 menit**

---

## 📱 PREVIEW & TESTING

### Cara Melihat Hasil:
1. Setelah simpan, buka website di tab baru
2. URL: `http://localhost:3000/`
3. Refresh halaman (Ctrl+F5)
4. Cek semua section sudah tampil

### Checklist Testing:
```
✅ Hero section tampil dengan gambar dan teks
✅ Slider berjalan otomatis setiap 5 detik
✅ Bisa klik arrow kiri/kanan untuk navigasi slider
✅ Section "Mengapa Kami" tampil dengan ikon
✅ Testimoni tampil dengan foto dan rating
✅ Statistik tampil dengan angka yang benar
✅ Featured packages tampil (max 3)
✅ Semua gambar loading dengan baik
✅ Responsive di mobile (cek dengan F12 > Toggle Device)
```

---

## 🐛 TROUBLESHOOTING

### Masalah 1: Perubahan Tidak Tampil
**Solusi:**
1. Hard refresh browser (Ctrl+Shift+R)
2. Clear cache browser
3. Cek apakah sudah klik "Simpan Perubahan"
4. Cek console browser (F12) untuk error

### Masalah 2: Gambar Tidak Tampil
**Solusi:**
1. Pastikan URL gambar valid (bisa dibuka di tab baru)
2. Gunakan HTTPS, bukan HTTP
3. Cek ukuran gambar tidak terlalu besar (max 2MB)
4. Upload ke Media Library jika perlu

### Masalah 3: Slider Tidak Berjalan
**Solusi:**
1. Pastikan minimal ada 2 slide
2. Cek console browser untuk error JavaScript
3. Refresh halaman
4. Cek apakah semua field slide sudah diisi

### Masalah 4: Ikon Tidak Tampil
**Solusi:**
1. Pastikan nama ikon sesuai dengan Lucide Icons
2. Gunakan PascalCase (Shield, bukan shield)
3. Cek di https://lucide.dev/icons/ untuk nama yang benar

### Masalah 5: Featured Package Tidak Tampil
**Solusi:**
1. Pastikan paket status = Active
2. Pastikan "Is Featured" sudah dicentang
3. Pastikan ada gambar di paket
4. Refresh halaman

---

## 💡 TIPS & BEST PRACTICES

### Content Writing:
- ✅ Gunakan bahasa yang menarik dan persuasif
- ✅ Fokus pada benefit, bukan fitur
- ✅ Gunakan angka spesifik (500+ bukan "banyak")
- ✅ Tambahkan call-to-action yang jelas

### Gambar:
- ✅ Gunakan gambar berkualitas tinggi
- ✅ Konsisten dalam style dan tone
- ✅ Compress gambar sebelum upload (TinyPNG)
- ✅ Gunakan format WebP jika memungkinkan

### SEO:
- ✅ Gunakan keyword di judul hero
- ✅ Deskripsi harus informatif dan menarik
- ✅ Alt text untuk semua gambar
- ✅ Update konten secara berkala

### Performance:
- ✅ Maksimal 5-7 slide di slider
- ✅ Compress semua gambar
- ✅ Lazy load untuk gambar di bawah fold
- ✅ Gunakan CDN untuk gambar

---

## 📊 ANALYTICS & MONITORING

### Metrik yang Perlu Dipantau:
```
1. Bounce Rate
   - Target: < 40%
   - Jika tinggi: Perbaiki hero section

2. Time on Page
   - Target: > 2 menit
   - Jika rendah: Konten kurang menarik

3. Scroll Depth
   - Target: > 75%
   - Jika rendah: Konten terlalu panjang

4. Click-Through Rate (CTR)
   - Target: > 5%
   - Jika rendah: CTA kurang jelas
```

### Tools untuk Monitoring:
- Google Analytics
- Hotjar (heatmap)
- Google Search Console
- PageSpeed Insights

---

## 🔄 UPDATE SCHEDULE

### Rekomendasi Update:
```
Harian:
- Cek featured packages masih relevan
- Cek harga masih up-to-date

Mingguan:
- Update statistik jika ada perubahan
- Tambah testimoni baru jika ada

Bulanan:
- Review dan update slider
- Update hero section jika ada promo
- Review dan update "Mengapa Kami"

Musiman:
- Update destinasi sesuai musim
- Update harga sesuai high/low season
- Update promo dan penawaran khusus
```

---

## 📚 RESOURCES

### Sumber Gambar Gratis:
- **Unsplash:** https://unsplash.com/
- **Pexels:** https://pexels.com/
- **Pixabay:** https://pixabay.com/

### Sumber Ikon:
- **Lucide Icons:** https://lucide.dev/icons/
- **Heroicons:** https://heroicons.com/

### Tools:
- **TinyPNG:** https://tinypng.com/ (compress gambar)
- **Remove.bg:** https://remove.bg/ (hapus background)
- **Canva:** https://canva.com/ (edit gambar)

---

## ✅ CHECKLIST LENGKAP

### Setup Awal:
- [ ] Hero section sudah diisi
- [ ] Minimal 3 slide di slider
- [ ] Minimal 4 poin di "Mengapa Kami"
- [ ] Minimal 3 testimoni
- [ ] Minimal 4 statistik
- [ ] Minimal 3 featured packages

### Quality Check:
- [ ] Semua gambar loading dengan baik
- [ ] Tidak ada typo di teks
- [ ] Semua link berfungsi
- [ ] Responsive di mobile
- [ ] Loading time < 3 detik

### SEO Check:
- [ ] Judul hero mengandung keyword
- [ ] Deskripsi informatif
- [ ] Alt text untuk gambar
- [ ] Meta tags sudah diatur

---

**Dibuat:** 21 April 2026  
**Terakhir Update:** 21 April 2026  
**Status:** ✅ Lengkap & Siap Digunakan

**CMS Homepage sudah siap! Semua konten bisa diatur tanpa coding! 🎉**
