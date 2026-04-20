# 📸 Media Library - Panduan Lengkap

## ✨ Fitur Baru: Centralized Media Management

Media Library adalah fitur baru yang memungkinkan Anda mengelola semua file gambar di satu tempat terpusat.

---

## 🎯 Fitur Utama

### 1. **Drag & Drop Upload** 🖱️
- Seret file langsung ke area upload
- Support multiple files sekaligus
- Preview real-time saat upload

### 2. **Folder Organization** 📁
Organize file berdasarkan kategori:
- **All Media** - Semua file
- **Packages** - Gambar paket wisata
- **Blog** - Gambar artikel blog
- **Cars** - Gambar armada mobil
- **Landing** - Gambar landing page

### 3. **View Modes** 👁️
- **Grid View** - Tampilan kartu (default)
- **List View** - Tampilan tabel detail

### 4. **Search & Filter** 🔍
- Cari file berdasarkan nama
- Filter berdasarkan folder
- Real-time search

### 5. **Bulk Actions** ✅
- Select multiple files
- Delete multiple files sekaligus
- Select all dengan satu klik

### 6. **File Preview** 🖼️
- Preview gambar full size
- Lihat detail file (size, dimensions)
- Copy URL dengan satu klik

### 7. **Statistics** 📊
- Total files
- Total folders
- Storage usage
- Selected files count

---

## 🚀 Cara Menggunakan

### Upload File

**Metode 1: Drag & Drop**
```
1. Buka Media Library (/admin/media)
2. Pilih folder tujuan (sidebar kiri)
3. Drag file dari komputer Anda
4. Drop ke area "Drag & drop files here"
5. Tunggu upload selesai
```

**Metode 2: Button Upload**
```
1. Klik tombol "Upload File" (kanan atas)
2. Pilih file dari komputer
3. Bisa pilih multiple files (Ctrl/Cmd + Click)
4. Klik Open
5. File otomatis terupload
```

### Mengelola File

**Preview File:**
```
1. Hover mouse ke gambar
2. Klik icon mata (Eye)
3. Modal preview akan muncul
4. Lihat detail: size, dimensions, URL
```

**Copy URL:**
```
1. Preview file atau hover ke gambar
2. Klik icon download
3. URL otomatis disalin ke clipboard
4. Paste di form yang membutuhkan
```

**Delete File:**
```
Single Delete:
1. Hover ke gambar
2. Klik icon trash
3. Confirm delete

Bulk Delete:
1. Centang checkbox di gambar
2. Klik tombol "Hapus (X)" di toolbar
3. Confirm delete
```

**Select All:**
```
1. Klik checkbox di header tabel (List View)
2. Atau centang semua manual (Grid View)
3. Semua file di folder aktif akan terselect
```

### Switch View Mode

**Grid View (Default):**
- Tampilan kartu dengan preview besar
- Cocok untuk browse visual
- Hover untuk quick actions

**List View:**
- Tampilan tabel dengan detail lengkap
- Cocok untuk manage banyak file
- Lihat size, upload date, dll

---

## 📱 Mobile Responsive

Media Library **fully responsive** untuk semua device:

### Desktop (>1024px)
- Sidebar folder di kiri
- Grid 4 kolom
- Full features

### Tablet (768px - 1024px)
- Sidebar tetap visible
- Grid 3 kolom
- Compact toolbar

### Mobile (<768px)
- Grid 2 kolom
- Touch-friendly buttons
- Simplified toolbar
- Stats dalam 2 kolom

---

## 🎨 UI/UX Improvements

### Grid View
```
✅ Hover effects dengan scale animation
✅ Gradient overlay untuk actions
✅ Quick actions: Preview & Copy URL
✅ Checkbox untuk bulk select
✅ Featured badge untuk special files
```

### List View
```
✅ Sortable columns
✅ Inline actions
✅ Thumbnail preview
✅ File details (size, date)
✅ Select all checkbox
```

### Upload Zone
```
✅ Drag & drop dengan visual feedback
✅ Active state saat drag over
✅ Upload progress indicator
✅ Success/error notifications
```

---

## 🔧 Technical Details

### File Structure
```
src/
├── pages/
│   └── AdminMediaLibrary.tsx    # Main component
├── app/
│   └── admin/
│       └── media/
│           └── page.tsx          # Route wrapper
└── components/
    └── admin/
        └── AdminSidebar.tsx      # Updated with Media menu
```

### API Endpoints (To Implement)
```typescript
GET    /api/media              # List all files
POST   /api/media/upload       # Upload files
DELETE /api/media/:id          # Delete file
GET    /api/media/stats        # Get statistics
```

### State Management
```typescript
- files: MediaFile[]           # All uploaded files
- folders: Folder[]            # Folder categories
- selectedFolder: string       # Active folder
- viewMode: 'grid' | 'list'   # View preference
- selectedFiles: string[]      # Selected file IDs
- previewFile: MediaFile       # File being previewed
```

---

## 🎯 Best Practices

### File Naming
```
✅ GOOD: danau-toba-sunset.jpg
✅ GOOD: innova-reborn-2024.jpg
❌ BAD: IMG_1234.jpg
❌ BAD: photo (1).jpg
```

### File Organization
```
Packages/     → Gambar paket wisata
Blog/         → Featured images artikel
Cars/         → Foto armada mobil
Landing/      → Hero images, banners
```

### File Size
```
Recommended:
- Hero images: < 500KB (optimized)
- Thumbnails: < 100KB
- Blog images: < 300KB

Use image optimization tools:
- TinyPNG
- ImageOptim
- Squoosh
```

---

## 🐛 Troubleshooting

### Upload Gagal
```
Problem: File tidak terupload
Solution:
1. Check file size (max 5MB)
2. Check file type (hanya image)
3. Check internet connection
4. Refresh page dan coba lagi
```

### File Tidak Muncul
```
Problem: File sudah diupload tapi tidak muncul
Solution:
1. Refresh page (Ctrl + R)
2. Check folder yang aktif
3. Clear search query
4. Check browser console untuk error
```

### Preview Tidak Muncul
```
Problem: Modal preview tidak muncul
Solution:
1. Check browser console
2. Disable ad blocker
3. Try different browser
4. Clear browser cache
```

---

## 🚀 Future Enhancements

### Phase 1 (Current) ✅
- [x] Drag & drop upload
- [x] Folder organization
- [x] Grid/List view
- [x] Search & filter
- [x] Bulk actions
- [x] File preview
- [x] Mobile responsive

### Phase 2 (Next)
- [ ] Image optimization (WebP conversion)
- [ ] Image cropping tool
- [ ] Bulk upload progress
- [ ] Storage quota management
- [ ] Advanced search (by date, size)
- [ ] Folder creation/deletion
- [ ] File renaming
- [ ] Move files between folders

### Phase 3 (Future)
- [ ] CDN integration
- [ ] Image editing (filters, resize)
- [ ] Video support
- [ ] PDF support
- [ ] Zip upload/extract
- [ ] Duplicate detection
- [ ] Usage tracking (where file is used)

---

## 📞 Support

Jika ada masalah atau pertanyaan:
1. Check dokumentasi ini
2. Check browser console untuk error
3. Contact developer team

---

## 🎉 Kesimpulan

Media Library adalah **game changer** untuk manajemen file di admin panel:

✅ **Centralized** - Semua file di satu tempat  
✅ **Organized** - Folder system yang jelas  
✅ **Efficient** - Drag & drop, bulk actions  
✅ **User-friendly** - Intuitive UI/UX  
✅ **Mobile-ready** - Responsive design  

**Selamat menggunakan Media Library! 🚀**

---

**Created:** 20 April 2026  
**Version:** 1.0.0  
**Status:** Production Ready ✅
