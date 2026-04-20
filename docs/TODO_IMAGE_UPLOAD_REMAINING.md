# 📋 TODO: Image Upload - Halaman yang Masih Perlu Diupdate

**Status:** 🚧 IN PROGRESS  
**Progress:** 1/15 halaman selesai (6.7%)

---

## ✅ SELESAI (1 halaman)

1. **AdminTourLanding.tsx** ✅
   - Hero background image
   - Slider background images (5x)
   - Slider card images (5x)
   - Testimonial avatars (3x)
   - **Total: ~16 images**

---

## ⚠️ PRIORITY HIGH (Halaman Utama - 4 halaman)

### 2. **AdminOutboundLanding.tsx** 🔴 URGENT
**Location:** `src/pages/AdminOutboundLanding.tsx`

**Images to update:**
- [ ] Hero background image (line ~?)
- [ ] Slider background images (~3x)
- [ ] Slider card images (~3x)
- [ ] Services images (~6x) - line 246
- [ ] Locations images (~15x) - line 323
- [ ] Gallery images (~20x) - line 356
- [ ] Testimonial avatars (~3x)

**Estimated:** ~50 images

**Changes needed:**
```typescript
// Line 246 - Services
<InputGroup label="URL Gambar" ... />
// Replace with:
<ImageUpload label="Gambar Service" aspectRatio="square" ... />

// Line 323 - Locations
<input placeholder="URL Gambar" ... />
// Replace with:
<ImageUpload label="Gambar Lokasi" aspectRatio="video" ... />

// Line 356 - Gallery
<input placeholder="URL Gambar" ... />
// Replace with:
<ImageUpload label="Foto Gallery" aspectRatio="square" ... />
```

---

### 3. **AdminPackageCreate.tsx** 🔴 URGENT
**Location:** `src/pages/AdminPackageCreate.tsx`

**Images to update:**
- [ ] Package images (multiple URLs, comma-separated) - line 414

**Current:**
```typescript
<textarea
  placeholder="https://example.com/tour1.jpg, https://example.com/tour2.jpg"
  ...
/>
```

**Changes needed:**
- Replace with multiple ImageUpload components
- Add "Tambah Gambar" button
- Support array of images
- Show preview grid

**Estimated:** Variable (3-10 images per package)

---

### 4. **AdminBlogCreate.tsx** 🟡 MEDIUM
**Location:** `src/pages/AdminBlogCreate.tsx`

**Images to update:**
- [ ] Featured image / Banner - line 179, 189

**Note:** Sudah ada `ImageUploadField` component, tapi perlu verifikasi apakah sudah menggunakan `/api/upload` yang baru

**Estimated:** 1 image per blog

---

### 5. **AdminCarCreate.tsx** 🟡 MEDIUM
**Location:** `src/pages/AdminCarCreate.tsx`

**Images to update:**
- [ ] Car image - line 228

**Estimated:** 1 image per car

---

## 🟠 PRIORITY MEDIUM (CRUD Pages - 6 halaman)

### 6. **AdminOutboundServices.tsx**
**Location:** `src/pages/AdminOutboundServices.tsx`

**Images to update:**
- [ ] Service icon/image - line 192

**Estimated:** 1 image per service (6 services)

---

### 7. **AdminOutboundVideos.tsx**
**Location:** `src/pages/AdminOutboundVideos.tsx`

**Images to update:**
- [ ] Video thumbnail (optional, YouTube auto-generates)
- [ ] YouTube URL input - line 139 (keep as is, not image)

**Note:** Mungkin tidak perlu update, karena YouTube URL bukan image upload

**Estimated:** 0 images (YouTube handles thumbnails)

---

### 8. **AdminOutboundLocations.tsx**
**Location:** `src/pages/AdminOutboundLocations.tsx`

**Images to update:**
- [ ] Location image - line 107, 109

**Estimated:** 1 image per location (15 locations)

---

### 9. **AdminClients.tsx**
**Location:** `src/pages/AdminClients.tsx`

**Images to update:**
- [ ] Client logo - line 108 (website URL, not image)
- [ ] Need to find logo image input

**Estimated:** 1 logo per client (12 clients)

---

### 10. **AdminGallery.tsx**
**Location:** `src/pages/AdminGallery.tsx`

**Images to update:**
- [ ] Gallery image - line 106, 107

**Estimated:** 20+ images

---

### 11. **AdminPackageTiers.tsx**
**Location:** `src/pages/AdminPackageTiers.tsx`

**Images to update:**
- [ ] Tier icon/image (need to check file)

**Estimated:** 1 image per tier (3 tiers)

---

## 🟢 PRIORITY LOW (Edit Pages - 4 halaman)

### 12. **AdminPackageEdit.tsx**
- Same as AdminPackageCreate.tsx
- Multiple package images

### 13. **AdminBlogEdit.tsx**
- Same as AdminBlogCreate.tsx
- Featured image

### 14. **AdminCarEdit.tsx**
- Same as AdminCarCreate.tsx
- Car image

### 15. **Other Edit Pages**
- Check for any other edit pages with image inputs

---

## 📊 SUMMARY

| Priority | Pages | Estimated Images | Status |
|----------|-------|------------------|--------|
| ✅ Done | 1 | ~16 | Complete |
| 🔴 High | 4 | ~60 | Not started |
| 🟠 Medium | 6 | ~40 | Not started |
| 🟢 Low | 4 | ~20 | Not started |
| **TOTAL** | **15** | **~136** | **6.7%** |

---

## 🎯 RECOMMENDED APPROACH

### Phase 1: Core Landing Pages (URGENT)
1. ✅ AdminTourLanding.tsx (DONE)
2. 🔴 AdminOutboundLanding.tsx (NEXT)
3. 🔴 AdminPackageCreate.tsx
4. 🔴 AdminPackageEdit.tsx

**Impact:** 80% of visible images on user pages

### Phase 2: CRUD Pages (MEDIUM)
5. AdminOutboundServices.tsx
6. AdminOutboundLocations.tsx
7. AdminClients.tsx
8. AdminGallery.tsx
9. AdminPackageTiers.tsx

**Impact:** Admin management efficiency

### Phase 3: Blog & Cars (LOW)
10. AdminBlogCreate/Edit.tsx
11. AdminCarCreate/Edit.tsx

**Impact:** Secondary features

---

## 🛠️ IMPLEMENTATION STRATEGY

### For Simple Single Image Input
```typescript
// Before
<InputGroup 
  label="URL Gambar" 
  value={image} 
  onChange={setImage} 
/>

// After
<ImageUpload 
  label="Gambar Service" 
  value={image} 
  onChange={setImage} 
  aspectRatio="square"
/>
```

### For Multiple Images (Package)
```typescript
// Before
<textarea 
  placeholder="https://url1.jpg, https://url2.jpg"
  value={images.join(', ')}
/>

// After
<div className="space-y-4">
  {images.map((img, idx) => (
    <ImageUpload 
      key={idx}
      label={`Gambar ${idx + 1}`}
      value={img}
      onChange={(url) => updateImage(idx, url)}
      aspectRatio="video"
    />
  ))}
  <button onClick={addImage}>+ Tambah Gambar</button>
</div>
```

### For Gallery Grid
```typescript
// Before
{gallery.map((img, idx) => (
  <input 
    placeholder="URL Gambar"
    value={img}
  />
))}

// After
{gallery.map((img, idx) => (
  <ImageUpload 
    label={`Foto ${idx + 1}`}
    value={img}
    onChange={(url) => updateGallery(idx, url)}
    aspectRatio="square"
  />
))}
```

---

## 📝 CHECKLIST PER PAGE

### Template Checklist
- [ ] Import ImageUpload component
- [ ] Replace URL input with ImageUpload
- [ ] Set appropriate aspectRatio
- [ ] Test upload functionality
- [ ] Test save to database
- [ ] Test display on user page
- [ ] Update documentation

---

## 🚀 NEXT ACTIONS

**Immediate (Today):**
1. Update AdminOutboundLanding.tsx (biggest impact)
2. Update AdminPackageCreate.tsx (user-facing)

**Short Term (This Week):**
3. Update all CRUD pages
4. Create migration script for existing images

**Long Term (Next Week):**
5. Add bulk upload feature
6. Add media library browser
7. Add image cropping tool

---

## 💡 NOTES

1. **ImageUpload Component is Reusable**
   - Already created and tested
   - Just import and use
   - No need to recreate

2. **Aspect Ratios**
   - `square` (1:1) - Logos, avatars, icons
   - `video` (16:9) - Cards, thumbnails
   - `wide` (21:9) - Hero backgrounds
   - `portrait` (3:4) - Vertical images

3. **Migration Strategy**
   - New uploads use WebP automatically
   - Old URLs still work (backward compatible)
   - Gradual migration as admins update content
   - Optional: Create script to bulk convert

4. **Testing Priority**
   - Test upload in each page
   - Test save to database
   - Test display on user page
   - Test mobile upload

---

**Last Updated:** 21 April 2026  
**Next Review:** After Phase 1 completion
