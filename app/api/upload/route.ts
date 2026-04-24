import { NextRequest, NextResponse } from 'next/server';
import { writeFile, mkdir } from 'fs/promises';
import path from 'path';
import sharp from 'sharp';

export async function POST(req: NextRequest) {
  try {
    const formData = await req.formData();
    const file = formData.get('file') as File;
    
    if (!file) {
      return NextResponse.json({ error: 'No file uploaded' }, { status: 400 });
    }

    // Validate file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    if (!validTypes.includes(file.type)) {
      return NextResponse.json({ error: 'Invalid file type. Only images allowed.' }, { status: 400 });
    }

    // Get file buffer
    const bytes = await file.arrayBuffer();
    const buffer = Buffer.from(bytes);

    // Generate unique filename
    const timestamp = Date.now();
    const originalName = file.name.replace(/\.[^/.]+$/, ''); // Remove extension
    const sanitizedName = originalName.replace(/[^a-z0-9]/gi, '-').toLowerCase();
    const filename = `${sanitizedName}-${timestamp}.webp`;

    // Create directory structure: /public/storage/YYYY/MM/
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const uploadDir = path.join(process.cwd(), 'public', 'storage', String(year), month);
    
    // Ensure directory exists
    await mkdir(uploadDir, { recursive: true });

    // Convert to WebP and save
    const filepath = path.join(uploadDir, filename);
    await sharp(buffer)
      .webp({ quality: 85 }) // High quality WebP
      .resize(2000, 2000, { // Max 2000px, maintain aspect ratio
        fit: 'inside',
        withoutEnlargement: true
      })
      .toFile(filepath);

    // Return public URL
    const publicUrl = `/storage/${year}/${month}/${filename}`;
    
    // Get file size
    const stats = await sharp(filepath).metadata();
    
    return NextResponse.json({
      success: true,
      url: publicUrl,
      filename,
      size: stats.size,
      width: stats.width,
      height: stats.height,
      format: 'webp'
    });

  } catch (error: any) {
    console.error('Upload error:', error);
    return NextResponse.json(
      { error: 'Failed to upload file', details: error.message },
      { status: 500 }
    );
  }
}

// Get uploaded images
export async function GET(req: NextRequest) {
  try {
    const { searchParams } = new URL(req.url);
    const limit = parseInt(searchParams.get('limit') || '50');
    
    // This is a simple implementation
    // In production, you'd want to store metadata in database
    return NextResponse.json({
      images: [],
      message: 'Use media library for browsing images'
    });
  } catch (error) {
    return NextResponse.json({ error: 'Failed to fetch images' }, { status: 500 });
  }
}
