import { NextRequest, NextResponse } from 'next/server';
import { readdir, stat, unlink } from 'fs/promises';
import path from 'path';
import sharp from 'sharp';

export interface MediaFileInfo {
  id: string;
  filename: string;
  url: string;
  size: number;
  type: string;
  folder: string; // year/month
  uploadedAt: string;
  dimensions?: { width: number; height: number };
}

async function scanDirectory(dir: string, baseUrl: string): Promise<MediaFileInfo[]> {
  const files: MediaFileInfo[] = [];
  
  try {
    const entries = await readdir(dir, { withFileTypes: true });
    
    for (const entry of entries) {
      const fullPath = path.join(dir, entry.name);
      
      if (entry.isDirectory()) {
        const subFiles = await scanDirectory(fullPath, `${baseUrl}/${entry.name}`);
        files.push(...subFiles);
      } else if (entry.isFile()) {
        const ext = path.extname(entry.name).toLowerCase();
        if (['.jpg', '.jpeg', '.png', '.webp', '.gif', '.svg'].includes(ext)) {
          try {
            const fileStat = await stat(fullPath);
            const url = `${baseUrl}/${entry.name}`;
            
            // Get image dimensions
            let dimensions: { width: number; height: number } | undefined;
            try {
              const meta = await sharp(fullPath).metadata();
              if (meta.width && meta.height) {
                dimensions = { width: meta.width, height: meta.height };
              }
            } catch {
              // Skip dimension reading for non-sharp-compatible files
            }
            
            // Extract folder from URL path (e.g., /storage/2026/04 -> 2026/04)
            const urlParts = url.split('/');
            const folder = urlParts.length >= 4 
              ? `${urlParts[urlParts.length - 3]}/${urlParts[urlParts.length - 2]}`
              : 'other';
            
            files.push({
              id: Buffer.from(url).toString('base64'),
              filename: entry.name,
              url,
              size: fileStat.size,
              type: `image/${ext.replace('.', '')}`,
              folder,
              uploadedAt: fileStat.mtime.toISOString(),
              dimensions,
            });
          } catch {
            // Skip files that can't be read
          }
        }
      }
    }
  } catch {
    // Directory doesn't exist or can't be read
  }
  
  return files;
}

// GET - List all media files
export async function GET(req: NextRequest) {
  try {
    const { searchParams } = new URL(req.url);
    const folder = searchParams.get('folder');
    const search = searchParams.get('search');
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '50');

    // Scan both /storage and /assets/images
    const storageDir = path.join(process.cwd(), 'public', 'storage');
    const assetsDir = path.join(process.cwd(), 'public', 'assets', 'images');

    const [storageFiles, assetFiles] = await Promise.all([
      scanDirectory(storageDir, '/storage'),
      scanDirectory(assetsDir, '/assets/images'),
    ]);

    let files = [...storageFiles, ...assetFiles];

    // Sort by upload date (newest first)
    files.sort((a, b) => new Date(b.uploadedAt).getTime() - new Date(a.uploadedAt).getTime());

    // Filter by folder
    if (folder && folder !== 'all') {
      files = files.filter(f => f.folder === folder);
    }

    // Filter by search
    if (search) {
      files = files.filter(f =>
        f.filename.toLowerCase().includes(search.toLowerCase())
      );
    }

    // Pagination
    const total = files.length;
    const totalPages = Math.ceil(total / limit);
    const offset = (page - 1) * limit;
    const paginatedFiles = files.slice(offset, offset + limit);

    // Get unique folders from all files
    const allFiles = [...storageFiles, ...assetFiles];
    const folderMap = new Map<string, number>();
    allFiles.forEach(f => {
      folderMap.set(f.folder, (folderMap.get(f.folder) || 0) + 1);
    });
    const folders = Array.from(folderMap.entries())
      .map(([name, count]: [string, number]) => ({ name, count }))
      .sort((a, b) => b.name.localeCompare(a.name));

    return NextResponse.json({
      files: paginatedFiles,
      total,
      page,
      totalPages,
      folders,
      totalSize: allFiles.reduce((acc: number, f: any) => acc + f.size, 0),
    });
  } catch (error: any) {
    console.error('Media list error:', error);
    return NextResponse.json({ error: 'Failed to list media', details: error.message }, { status: 500 });
  }
}

// DELETE - Delete a media file
export async function DELETE(req: NextRequest) {
  try {
    const { urls } = await req.json();
    
    if (!urls || !Array.isArray(urls) || urls.length === 0) {
      return NextResponse.json({ error: 'No URLs provided' }, { status: 400 });
    }

    const deleted: string[] = [];
    const failed: string[] = [];

    for (const url of urls) {
      try {
        // Security: only allow deleting from /storage/ path
        if (!url.startsWith('/storage/')) {
          failed.push(url);
          continue;
        }
        
        const filePath = path.join(process.cwd(), 'public', url);
        
        // Security: ensure path is within public/storage
        const storagePath = path.join(process.cwd(), 'public', 'storage');
        if (!filePath.startsWith(storagePath)) {
          failed.push(url);
          continue;
        }
        
        await unlink(filePath);
        deleted.push(url);
      } catch {
        failed.push(url);
      }
    }

    return NextResponse.json({ 
      success: true, 
      deleted, 
      failed,
      message: `${deleted.length} file dihapus${failed.length > 0 ? `, ${failed.length} gagal` : ''}`
    });
  } catch (error: any) {
    console.error('Media delete error:', error);
    return NextResponse.json({ error: 'Failed to delete media', details: error.message }, { status: 500 });
  }
}
