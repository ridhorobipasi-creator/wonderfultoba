# 📊 ANALISIS KONTEN HARDCODED - APRIL 2026

## 🎯 TUJUAN
Mengidentifikasi SEMUA konten statis (hardcoded) di halaman user dan memindahkannya ke CMS Admin agar dapat dikelola secara real-time tanpa perlu edit kode.

---

## 📋 RINGKASAN EKSEKUTIF

### Status Saat Ini
- ✅ **Slider Homepage**: Sudah real-time dari CMS
- ✅ **Hero Section**: Sudah real-time dari CMS  
- ✅ **Why Us Section**: Sudah real-time dari CMS
- ✅ **Testimonials**: Sudah real-time dari CMS
- ✅ **Stats**: Sudah real-time dari CMS
- ❌ **Outbound Services**: HARDCODED (6 services)
- ❌ **Outbound Videos**: HARDCODED (6 videos)
- ❌ **Outbound Locations**: HARDCODED (16 locations)
- ❌ **Outbound Clients**: HARDCODED (19 client logos)
- ❌ **Outbound Gallery**: HARDCODED (24 images)
- ❌ **Outbound Packages Tiers**: HARDCODED (3 packages)
- ❌ **Profile Menu Items**: HARDCODED (3 menu items)
- ❌ **Hero Slider Fallback**: HARDCODED (5 destinations)

### Total Konten yang Perlu Dipindahkan
**8 Kategori Konten** dengan **82+ Item Individual**

---

## 🔍 DETAIL ANALISIS PER FILE

### 1️⃣ **src/pages/Outbound.tsx**

#### A. SERVICES SECTION (Line 127-134)
**Status**: ❌ HARDCODED  
**Lokasi**: Array `services` dengan 6 items  
**Data Structure**:
```typescript
{
  title: string,        // "TEAM BUILDING"
  desc: string,         // "Sinergi dan kolaborasi personal"
  icon: IconComponent,  // Users, Smile, Sparkles, Compass, Target, Swords
  image: string,        // "/assets/images/2023/10/A11-Team-Building.jpg"
  detail: string        // Deskripsi lengkap
}
```

**Items**:
1. TEAM BUILDING - Users icon
2. FUN GAMES - Smile icon
3. GATHERING - Sparkles icon
4. OUTBOUND KIDS - Compass icon
5. ARCHERY - Target icon
6. PAINTBALL - Swords icon

**Input yang Dibutuhkan di Admin**:
- ✏️ Title (text input)
- ✏️ Short Description (text input)
- ✏️ Detail Description (textarea)
- 🖼️ Image Upload
- 🎨 Icon Selector (dropdown: Users, Smile, Sparkles, Compass, Target, Swords, dll)
- 🔢 Order/Priority (number)

---

#### B. VIDEOS SECTION (Line 200-207)
**Status**: ❌ HARDCODED  
**Lokasi**: Array `videos` dengan 6 items  
**Data Structure**:
```typescript
{
  title: string,  // "Highlight"
  url: string     // "https://www.youtube.com/embed/J59m32QV0rM"
}
```

**Items**:
1. Highlight
2. Hutama Karya
3. Pelindo 1
4. Lions Club
5. BBPPTP
6. Charoen Pokphand

**Input yang Dibutuhkan di Admin**:
- ✏️ Title (text input)
- 🔗 YouTube Embed URL (text input dengan validasi URL)
- 🔢 Order (number)
- ✅ Active Status (toggle)

---

#### C. LOCATIONS SECTION (Line 136-153)
**Status**: ❌ HARDCODED  
**Lokasi**: Array `lokasiOutboundData` dengan 16 items  
**Data Structure**:
```typescript
{
  title: string,  // "Marianna Resort, Samosir"
  img: string     // "/assets/images/2023/10/00-Marianna-Resort..."
}
```

**Items**: 16 lokasi resort/venue di Sumatera Utara

**Input yang Dibutuhkan di Admin**:
- ✏️ Location Name (text input)
- 🖼️ Image Upload
- 📍 Region/Area (text input) - opsional
- 🔢 Order (number)
- ✅ Featured (toggle)

---

#### D. CLIENTS SECTION (Line 155-174)
**Status**: ❌ HARDCODED  
**Lokasi**: Array `clients` dengan 19 items  
**Data Structure**:
```typescript
string[]  // Array of image filenames
```

**Items**: 19 client logos (Mandiri-taspen, USU, Charoen Pokphand, dll)

**Input yang Dibutuhkan di Admin**:
- ✏️ Client Name (text input)
- 🖼️ Logo Upload (PNG/SVG preferred)
- 🔗 Website URL (optional)
- 🔢 Order (number)
- ✅ Active Status (toggle)

---

#### E. GALLERY SECTION (Line 209-216)
**Status**: ❌ HARDCODED  
**Lokasi**: Array `galleryImages` dengan 24 items  
**Data Structure**:
```typescript
string[]  // Array of image filenames
```

**Items**: 24 foto dokumentasi event

**Input yang Dibutuhkan di Admin**:
- 🖼️ Bulk Image Upload (multiple files)
- ✏️ Caption (optional, per image)
- 🏷️ Tags/Category (optional)
- 📅 Event Date (optional)
- 🔢 Order (drag & drop)

---

### 2️⃣ **src/pages/OutboundPackages.tsx**

#### PACKAGES TIERS (Line 11-76)
**Status**: ❌ HARDCODED  
**Lokasi**: Array `packages` dengan 3 items  
**Data Structure**:
```typescript
{
  tier: string,           // "Basic", "Standard", "Premium"
  tagline: string,        // "Paket Hemat Terbaik"
  badge: string | null,   // "TERPOPULER", "ALL-IN"
  color: string,          // "from-slate-700 to-slate-800"
  priceLabel: string,     // "Mulai dari"
  price: string,          // "Rp 250.000" atau "Custom Quote"
  unit: string,           // "/ pax (min. 30 pax)"
  duration: string,       // "Setengah Hari (4-5 Jam)"
  capacity: string,       // "30 – 100 Pax"
  location: string,       // "Area Jabodetabek & Sumut"
  features: string[],     // Array of included features
  excludes: string[]      // Array of excluded items
}
```

**Items**:
1. Basic Package
2. Standard Package (TERPOPULER)
3. Premium Package (ALL-IN)

**Input yang Dibutuhkan di Admin**:
- ✏️ Tier Name (text)
- ✏️ Tagline (text)
- 🏷️ Badge (dropdown: null, TERPOPULER, ALL-IN, BEST VALUE, dll)
- 🎨 Color Theme (color picker atau preset)
- ✏️ Price Label (text)
- 💰 Price (text - bisa angka atau "Custom Quote")
- ✏️ Unit (text)
- ⏱️ Duration (text)
- 👥 Capacity (text)
- 📍 Location (text)
- ✅ Features (repeater field - add/remove items)
- ❌ Excludes (repeater field - add/remove items)
- 🔢 Order (number)
- ✅ Active Status (toggle)

---

### 3️⃣ **src/pages/Profile.tsx**

#### MENU ITEMS (Line 11-15)
**Status**: ❌ HARDCODED  
**Lokasi**: Array `menuItems` dengan 3 items  
**Data Structure**:
```typescript
{
  icon: IconComponent,  // Package, Car, Settings
  label: string,        // "Pemesanan Saya"
  desc: string,         // "Lihat riwayat dan status pemesanan"
  href: string,         // "/my-bookings"
  color: string         // "text-toba-green bg-toba-green/8"
}
```

**Items**:
1. Pemesanan Saya
2. Rental Mobil
3. Pengaturan Akun

**Catatan**: Ini mungkin tidak perlu dipindahkan ke CMS karena bersifat fungsional/navigasi internal. **SKIP untuk sekarang**.

---

### 4️⃣ **src/components/HeroSlider.tsx**

#### FALLBACK DESTINATIONS (Line 11-73)
**Status**: ⚠️ HARDCODED FALLBACK  
**Lokasi**: Constants `tourDestinations` dan `outboundDestinations`  
**Catatan**: Ini adalah fallback data jika API gagal. Sudah ada mekanisme fetch dari API, tapi fallback masih hardcoded.

**Rekomendasi**: 
- Tetap pertahankan fallback untuk UX
- Tapi buat fallback bisa di-manage dari admin juga
- Atau gunakan data dari seed.ts sebagai fallback

**Action**: **SKIP untuk sekarang** - prioritas rendah karena sudah ada API fetch.

---

## 🎨 DESAIN SOLUSI CMS

### Struktur Database yang Dibutuhkan

#### 1. Tabel: `outbound_services`
```sql
CREATE TABLE outbound_services (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  short_desc VARCHAR(200),
  detail_desc TEXT,
  icon VARCHAR(50),  -- nama icon dari Lucide
  image VARCHAR(500),
  order_priority INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 2. Tabel: `outbound_videos`
```sql
CREATE TABLE outbound_videos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  youtube_url VARCHAR(500) NOT NULL,
  order_priority INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### 3. Tabel: `outbound_locations`
```sql
CREATE TABLE outbound_locations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(200) NOT NULL,
  image VARCHAR(500),
  region VARCHAR(100),
  is_featured BOOLEAN DEFAULT FALSE,
  order_priority INT DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### 4. Tabel: `clients`
```sql
CREATE TABLE clients (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(200) NOT NULL,
  logo VARCHAR(500),
  website_url VARCHAR(500),
  order_priority INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### 5. Tabel: `gallery_images`
```sql
CREATE TABLE gallery_images (
  id INT PRIMARY KEY AUTO_INCREMENT,
  image_url VARCHAR(500) NOT NULL,
  caption TEXT,
  category VARCHAR(50),  -- 'outbound', 'tour', 'general'
  tags TEXT,  -- JSON array
  event_date DATE,
  order_priority INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### 6. Tabel: `package_tiers`
```sql
CREATE TABLE package_tiers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category VARCHAR(50),  -- 'outbound', 'tour'
  tier_name VARCHAR(100) NOT NULL,
  tagline VARCHAR(200),
  badge VARCHAR(50),
  color_theme VARCHAR(100),
  price_label VARCHAR(50),
  price VARCHAR(100),
  unit VARCHAR(100),
  duration VARCHAR(100),
  capacity VARCHAR(100),
  location VARCHAR(200),
  features JSON,  -- array of strings
  excludes JSON,  -- array of strings
  order_priority INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## 📝 RENCANA IMPLEMENTASI

### FASE 1: Setup Database & API (Prioritas TINGGI)
- [ ] Update `prisma/schema.prisma` dengan 6 model baru
- [ ] Generate migration: `npx prisma migrate dev`
- [ ] Seed data dummy ke 6 tabel baru
- [ ] Buat API endpoints:
  - `GET /api/outbound/services`
  - `GET /api/outbound/videos`
  - `GET /api/outbound/locations`
  - `GET /api/clients`
  - `GET /api/gallery`
  - `GET /api/package-tiers?category=outbound`

### FASE 2: Admin Panel UI (Prioritas TINGGI)
- [ ] Buat `src/pages/AdminOutboundServices.tsx`
- [ ] Buat `src/pages/AdminOutboundVideos.tsx`
- [ ] Buat `src/pages/AdminOutboundLocations.tsx`
- [ ] Buat `src/pages/AdminClients.tsx`
- [ ] Buat `src/pages/AdminGallery.tsx`
- [ ] Buat `src/pages/AdminPackageTiers.tsx`
- [ ] Update sidebar menu dengan 6 menu baru

### FASE 3: Update User Pages (Prioritas TINGGI)
- [ ] Update `src/pages/Outbound.tsx` - fetch dari API
- [ ] Update `src/pages/OutboundPackages.tsx` - fetch dari API
- [ ] Hapus semua hardcoded arrays
- [ ] Tambahkan loading states
- [ ] Tambahkan error handling

### FASE 4: Testing & Documentation (Prioritas SEDANG)
- [ ] Test CRUD operations di admin
- [ ] Test tampilan di user pages
- [ ] Update dokumentasi CMS
- [ ] Create video tutorial (optional)

---

## ⚡ ESTIMASI WAKTU

| Fase | Estimasi | Prioritas |
|------|----------|-----------|
| Fase 1: Database & API | 2-3 jam | 🔴 TINGGI |
| Fase 2: Admin Panel | 4-5 jam | 🔴 TINGGI |
| Fase 3: User Pages | 2-3 jam | 🔴 TINGGI |
| Fase 4: Testing | 1-2 jam | 🟡 SEDANG |
| **TOTAL** | **9-13 jam** | - |

---

## 🎯 PRIORITAS EKSEKUSI

### MUST HAVE (Implementasi Sekarang)
1. ✅ Outbound Services
2. ✅ Outbound Videos
3. ✅ Outbound Locations
4. ✅ Clients
5. ✅ Gallery
6. ✅ Package Tiers

### NICE TO HAVE (Implementasi Nanti)
7. ⏸️ Profile Menu Items (skip - fungsional)
8. ⏸️ Hero Slider Fallback (skip - sudah ada API)

---

## 📊 IMPACT ANALYSIS

### Sebelum Implementasi
- ❌ Admin tidak bisa update konten tanpa edit kode
- ❌ Setiap perubahan butuh developer
- ❌ Risiko error saat edit kode
- ❌ Tidak ada preview sebelum publish
- ❌ Sulit maintain consistency

### Setelah Implementasi
- ✅ Admin bisa update konten real-time
- ✅ No-code content management
- ✅ Zero risk of code errors
- ✅ Instant preview & publish
- ✅ Easy content consistency
- ✅ Audit trail (created_at, updated_at)
- ✅ Bulk operations (activate/deactivate)

---

## 🚀 NEXT STEPS

1. **Review & Approval**: Tinjau analisis ini dengan tim
2. **Start Implementation**: Mulai dari Fase 1 (Database & API)
3. **Iterative Development**: Implementasi per section, test, lanjut
4. **Documentation**: Update docs setelah setiap fase selesai

---

**Dibuat**: 21 April 2026  
**Status**: 📋 READY FOR IMPLEMENTATION  
**Estimasi Completion**: 1-2 hari kerja
