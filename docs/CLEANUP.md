# 🧹 Workspace Cleanup Guide

> Panduan untuk membersihkan sisa-sisa Next.js dan merapikan workspace

---

## 📋 Overview

Setelah migrasi dari Next.js ke Laravel Monolith selesai, workspace masih memiliki file-file dari Next.js yang tidak lagi diperlukan. Dokumen ini berisi panduan untuk membersihkan workspace.

---

## ⚠️ PENTING: Backup Dulu!

Sebelum menghapus apapun, pastikan:

```bash
# 1. Backup database
mysqldump -u root -p wonderfultoba > backup_$(date +%Y%m%d).sql

# 2. Backup files penting
tar -czf backup_files_$(date +%Y%m%d).tar.gz \
    storage/app \
    public/storage \
    .env

# 3. Commit semua perubahan
git add .
git commit -m "chore: pre-cleanup commit"
git push
```

---

## 🗑️ Files to Remove

### Next.js Artifacts

```bash
# Root directory cleanup
rm -rf .next
rm -rf node_modules
rm -rf src
rm -rf prisma
rm -rf public
rm next.config.ts
rm tsconfig.json
rm package.json
rm package-lock.json
rm .env.local
```

### Detailed List

| File/Folder | Size | Safe to Delete? | Reason |
|-------------|------|-----------------|--------|
| `.next/` | ~100MB | ✅ Yes | Next.js build output |
| `node_modules/` | ~500MB | ✅ Yes | Next.js dependencies |
| `src/` | ~50MB | ✅ Yes | Next.js source code |
| `prisma/` | ~5MB | ✅ Yes | Prisma schema & migrations |
| `public/` | ~20MB | ⚠️ Check first | May contain assets |
| `next.config.ts` | ~1KB | ✅ Yes | Next.js config |
| `tsconfig.json` | ~1KB | ✅ Yes | TypeScript config |
| `package.json` | ~1KB | ✅ Yes | Node dependencies |
| `package-lock.json` | ~500KB | ✅ Yes | Lock file |
| `.env.local` | ~1KB | ✅ Yes | Next.js env |

---

## 📂 Public Folder Handling

### Check Before Deleting

```bash
# List contents
ls -la public/

# Common structure:
# public/
# ├── favicon.ico
# ├── icons/
# ├── images/
# └── storage/
```

### Decision Tree

```
Is public/ folder empty?
├─ Yes → Delete it
└─ No → Check contents
    ├─ Only Next.js assets (favicon, manifest, etc.)
    │   └─ Delete it
    └─ Contains uploaded images/files
        ├─ Move to public/storage/
        └─ Then delete original
```

### Migration Script

```bash
#!/bin/bash

# Check if public folder has important files
if [ -d "public/storage" ]; then
    echo "Found public/storage, migrating..."
    
    # Create backup
    cp -r public/storage public_storage_backup
    
    # Move to Laravel public
    cp -r public/storage/* public/storage/
    
    echo "Migration complete. Check public/storage/"
    echo "If everything looks good, delete public_storage_backup"
fi
```

---

## 🔍 Verification Steps

### Before Cleanup

```bash
# Check disk usage
du -sh .next node_modules src prisma public

# List all Next.js files
find . -name "*.tsx" -o -name "*.ts" | grep -v node_modules

# Check for important files
find public -type f
```

### After Cleanup

```bash
# Verify files are gone
ls -la .next node_modules src prisma 2>/dev/null || echo "Cleanup successful"

# Check remaining size
du -sh .

# Verify Laravel app still works
php artisan serve
```

---

## 🚀 Cleanup Script

### Automated Cleanup

Create `cleanup.sh`:

```bash
#!/bin/bash

echo "🧹 Starting Wonderful Toba Workspace Cleanup..."
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to ask for confirmation
confirm() {
    read -p "$1 (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        return 0
    else
        return 1
    fi
}

# Check if we're in the right directory
if [ ! -d "app" ]; then
    echo -e "${RED}Error: Laravel app directory not found!${NC}"
    echo "Please run this script from the project root."
    exit 1
fi

echo -e "${YELLOW}⚠️  This will delete Next.js artifacts from the workspace.${NC}"
echo ""

# Show what will be deleted
echo "Files/folders to be deleted:"
echo "  - .next/"
echo "  - node_modules/"
echo "  - src/"
echo "  - prisma/"
echo "  - public/ (after checking)"
echo "  - next.config.ts"
echo "  - tsconfig.json"
echo "  - package.json"
echo "  - package-lock.json"
echo ""

if ! confirm "Continue with cleanup?"; then
    echo "Cleanup cancelled."
    exit 0
fi

# Create backup directory
BACKUP_DIR="cleanup_backup_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

echo ""
echo "📦 Creating backup in $BACKUP_DIR..."

# Backup important files
if [ -d "public/storage" ]; then
    cp -r public/storage "$BACKUP_DIR/"
    echo "  ✓ Backed up public/storage"
fi

if [ -f ".env.local" ]; then
    cp .env.local "$BACKUP_DIR/"
    echo "  ✓ Backed up .env.local"
fi

echo ""
echo "🗑️  Removing Next.js artifacts..."

# Remove directories
for dir in .next node_modules src prisma; do
    if [ -d "$dir" ]; then
        rm -rf "$dir"
        echo "  ✓ Removed $dir/"
    fi
done

# Remove files
for file in next.config.ts tsconfig.json package.json package-lock.json .env.local; do
    if [ -f "$file" ]; then
        rm "$file"
        echo "  ✓ Removed $file"
    fi
done

# Handle public folder
if [ -d "public" ]; then
    echo ""
    echo "📂 Checking public/ folder..."
    
    # Check if it has storage
    if [ -d "public/storage" ]; then
        echo "  Found public/storage, migrating to Laravel..."
        cp -r public/storage/* backend-toba/public/storage/ 2>/dev/null
        echo "  ✓ Migrated to backend-toba/public/storage/"
    fi
    
    # Remove public folder
    rm -rf public
    echo "  ✓ Removed public/"
fi

echo ""
echo "✨ Cleanup complete!"
echo ""
echo "📊 Space saved:"
du -sh "$BACKUP_DIR" | awk '{print "  Backup size: " $1}'
echo ""
echo "🔍 Verification:"
cd backend-toba
if php artisan --version > /dev/null 2>&1; then
    echo -e "  ${GREEN}✓ Laravel application is working${NC}"
else
    echo -e "  ${RED}✗ Laravel application check failed${NC}"
fi
cd ..

echo ""
echo "📝 Next steps:"
echo "  1. Test the application: cd backend-toba && php artisan serve"
echo "  2. If everything works, delete backup: rm -rf $BACKUP_DIR"
echo "  3. Commit changes: git add . && git commit -m 'chore: cleanup Next.js artifacts'"
echo ""
echo "🎉 Done!"
```

### Usage

```bash
# Make executable
chmod +x cleanup.sh

# Run cleanup
./cleanup.sh
```

---

## 📋 Manual Cleanup Checklist

If you prefer manual cleanup:

### Step 1: Remove Build Artifacts
- [ ] Delete `.next/` folder
- [ ] Delete `node_modules/` folder
- [ ] Verify: `ls -la .next node_modules` should show "No such file"

### Step 2: Remove Source Code
- [ ] Delete `src/` folder
- [ ] Delete `prisma/` folder
- [ ] Verify: `ls -la src prisma` should show "No such file"

### Step 3: Remove Config Files
- [ ] Delete `next.config.ts`
- [ ] Delete `tsconfig.json`
- [ ] Delete `package.json`
- [ ] Delete `package-lock.json`
- [ ] Verify: `ls -la *.ts *.json` should not show these files

### Step 4: Handle Public Folder
- [ ] Check `public/storage` for important files
- [ ] Copy important files to `backend-toba/public/storage/`
- [ ] Delete `public/` folder
- [ ] Verify: `ls -la public` should show "No such file"

### Step 5: Remove Environment Files
- [ ] Delete `.env.local` (if exists)
- [ ] Keep `.env` in backend-toba
- [ ] Verify: `ls -la .env*`

### Step 6: Verification
- [ ] Test Laravel app: `cd backend-toba && php artisan serve`
- [ ] Check website loads: Open http://localhost:8000
- [ ] Test API: `curl http://localhost:8000/api/packages`
- [ ] Check storage: Verify images still load

### Step 7: Git Cleanup
- [ ] Stage changes: `git add .`
- [ ] Commit: `git commit -m "chore: cleanup Next.js artifacts"`
- [ ] Push: `git push`

---

## 🔄 Rollback Plan

If something goes wrong:

```bash
# 1. Restore from backup
tar -xzf backup_files_YYYYMMDD.tar.gz

# 2. Restore database
mysql -u root -p wonderfultoba < backup_YYYYMMDD.sql

# 3. Restore from git
git reset --hard HEAD~1

# 4. Restore from backup directory
cp -r cleanup_backup_*/storage/* backend-toba/public/storage/
```

---

## 📊 Expected Results

### Before Cleanup

```
Total workspace size: ~1.5GB
├── .next/           ~100MB
├── node_modules/    ~500MB
├── src/             ~50MB
├── prisma/          ~5MB
├── public/          ~20MB
├── backend-toba/    ~800MB
└── others           ~25MB
```

### After Cleanup

```
Total workspace size: ~850MB
├── backend-toba/    ~800MB
├── documentation    ~5MB
└── others           ~45MB

Space saved: ~650MB (43%)
```

---

## ✅ Post-Cleanup Verification

### Functional Tests

```bash
# 1. Laravel app starts
cd backend-toba
php artisan serve
# Expected: Server started on http://localhost:8000

# 2. Homepage loads
curl -I http://localhost:8000
# Expected: HTTP/1.1 200 OK

# 3. API works
curl http://localhost:8000/api/packages
# Expected: JSON response with packages

# 4. Static assets load
curl -I http://localhost:8000/storage/2023/10/image.jpg
# Expected: HTTP/1.1 200 OK

# 5. Database connection
php artisan tinker
>>> DB::connection()->getPdo();
# Expected: PDO object

# 6. Cache works
php artisan cache:clear
# Expected: Application cache cleared successfully
```

### Visual Tests

- [ ] Homepage loads correctly
- [ ] Images display properly
- [ ] Navigation works
- [ ] Package pages load
- [ ] Blog pages load
- [ ] Car rental page loads
- [ ] PDF download works

---

## 🎯 Final Checklist

### Before Declaring Success

- [ ] All Next.js files removed
- [ ] Laravel app works perfectly
- [ ] All images/assets accessible
- [ ] Database intact
- [ ] API endpoints working
- [ ] No broken links
- [ ] Git repository clean
- [ ] Documentation updated
- [ ] Team notified
- [ ] Backup verified

---

## 📞 Support

If you encounter issues during cleanup:

1. **Stop immediately**
2. **Don't delete backups**
3. **Contact team:** dev@wonderfultoba.com
4. **Check documentation:** See PROJECT.md
5. **Review logs:** `tail -f backend-toba/storage/logs/laravel.log`

---

## 📝 Cleanup Log Template

Keep a log of your cleanup:

```
Cleanup Date: YYYY-MM-DD
Performed By: [Name]
Backup Location: [Path]

Files Removed:
- .next/ (100MB)
- node_modules/ (500MB)
- src/ (50MB)
- prisma/ (5MB)
- public/ (20MB)
- Config files (2MB)

Total Space Saved: 677MB

Verification:
✓ Laravel app working
✓ Images loading
✓ API responding
✓ Database connected

Issues Encountered:
[None / List issues]

Rollback Required:
[No / Yes - reason]

Notes:
[Any additional notes]
```

---

## 🎉 Success Criteria

Cleanup is successful when:

1. ✅ All Next.js artifacts removed
2. ✅ Workspace size reduced by ~40%
3. ✅ Laravel application fully functional
4. ✅ All assets accessible
5. ✅ No errors in logs
6. ✅ Team can continue development
7. ✅ Git repository clean
8. ✅ Documentation complete

---

**Cleanup Guide Version:** 1.0  
**Last Updated:** April 30, 2026  
**Status:** Ready to Execute ✅

---

**⚠️ Remember: Always backup before cleanup! ⚠️**
