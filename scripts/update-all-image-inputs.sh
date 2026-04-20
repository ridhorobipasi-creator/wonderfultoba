#!/bin/bash

# Script untuk update semua image input menjadi ImageUpload component
# Run: bash scripts/update-all-image-inputs.sh

echo "🚀 Starting batch update for all admin pages..."

# Array of files to update
files=(
  "src/pages/AdminOutboundLocations.tsx"
  "src/pages/AdminClients.tsx"
  "src/pages/AdminGallery.tsx"
  "src/pages/AdminPackageTiers.tsx"
  "src/pages/AdminPackageCreate.tsx"
  "src/pages/AdminPackageEdit.tsx"
  "src/pages/AdminBlogCreate.tsx"
  "src/pages/AdminBlogEdit.tsx"
  "src/pages/AdminCarCreate.tsx"
  "src/pages/AdminCarEdit.tsx"
)

for file in "${files[@]}"; do
  if [ -f "$file" ]; then
    echo "✅ Processing: $file"
    
    # Add import if not exists
    if ! grep -q "import ImageUpload" "$file"; then
      # Find the last import line and add after it
      sed -i "/^import.*from/a import ImageUpload from '../components/ImageUpload';" "$file" 2>/dev/null || \
      sed -i '' "/^import.*from/a\\
import ImageUpload from '../components/ImageUpload';
" "$file"
    fi
    
    echo "   - Added ImageUpload import"
  else
    echo "⚠️  File not found: $file"
  fi
done

echo ""
echo "✨ Batch update completed!"
echo "📝 Note: You still need to manually replace input fields with ImageUpload components"
echo "   Use this pattern:"
echo "   <ImageUpload label=\"Gambar\" value={image} onChange={setImage} aspectRatio=\"square\" />"
