/**
 * Migration Script: Download External Images & Convert to WebP
 * 
 * Usage:
 *   npx ts-node --project tsconfig.json scripts/migrate-images.ts
 *   npx tsx scripts/migrate-images.ts
 * 
 * What it does:
 * 1. Scans all database tables for external image URLs (Unsplash, Picsum, etc.)
 * 2. Downloads each image
 * 3. Converts to WebP (quality 85%, max 2000px)
 * 4. Saves to /public/storage/YYYY/MM/
 * 5. Updates database with new local URL
 * 6. Creates backup of old URLs in migration log
 */

import { PrismaClient } from '@prisma/client';
import sharp from 'sharp';
import { writeFile, mkdir, appendFile } from 'fs/promises';
import path from 'path';
import https from 'https';
import http from 'http';

const prisma = new PrismaClient();

// Log file for backup
const LOG_FILE = path.join(process.cwd(), 'scripts', 'migration-log.json');

interface MigrationEntry {
  table: string;
  id: number;
  field: string;
  oldUrl: string;
  newUrl: string;
  timestamp: string;
}

const migrationLog: MigrationEntry[] = [];
let totalProcessed = 0;
let totalSuccess = 0;
let totalFailed = 0;
let totalSkipped = 0;

// Check if URL is external (not local)
function isExternalUrl(url: string): boolean {
  if (!url) return false;
  return url.startsWith('http://') || url.startsWith('https://');
}

// Check if URL is already a local storage URL
function isLocalStorageUrl(url: string): boolean {
  return url.startsWith('/storage/') || url.startsWith('/assets/');
}

// Download image from URL
async function downloadImage(url: string): Promise<Buffer> {
  return new Promise((resolve, reject) => {
    const protocol = url.startsWith('https://') ? https : http;
    
    const request = protocol.get(url, {
      headers: {
        'User-Agent': 'Mozilla/5.0 (compatible; WonderfulToba/1.0)',
      },
      timeout: 30000,
    }, (response) => {
      // Handle redirects
      if (response.statusCode === 301 || response.statusCode === 302) {
        const redirectUrl = response.headers.location;
        if (redirectUrl) {
          downloadImage(redirectUrl).then(resolve).catch(reject);
          return;
        }
      }

      if (response.statusCode !== 200) {
        reject(new Error(`HTTP ${response.statusCode}: ${url}`));
        return;
      }

      const chunks: Buffer[] = [];
      response.on('data', (chunk: Buffer) => chunks.push(chunk));
      response.on('end', () => resolve(Buffer.concat(chunks)));
      response.on('error', reject);
    });

    request.on('error', reject);
    request.on('timeout', () => {
      request.destroy();
      reject(new Error(`Timeout downloading: ${url}`));
    });
  });
}

// Convert and save image to local storage
async function convertAndSave(imageBuffer: Buffer, originalUrl: string): Promise<string> {
  // Generate filename from URL
  const urlParts = originalUrl.split('/');
  const originalFilename = urlParts[urlParts.length - 1].split('?')[0];
  const baseName = originalFilename.replace(/\.[^/.]+$/, '') || 'image';
  const sanitizedName = baseName.replace(/[^a-z0-9]/gi, '-').toLowerCase().slice(0, 50);
  const timestamp = Date.now();
  const filename = `${sanitizedName}-${timestamp}.webp`;

  // Create directory: /public/storage/YYYY/MM/
  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const uploadDir = path.join(process.cwd(), 'public', 'storage', String(year), month);
  
  await mkdir(uploadDir, { recursive: true });

  // Convert to WebP
  const filepath = path.join(uploadDir, filename);
  await sharp(imageBuffer)
    .webp({ quality: 85 })
    .resize(2000, 2000, {
      fit: 'inside',
      withoutEnlargement: true,
    })
    .toFile(filepath);

  return `/storage/${year}/${month}/${filename}`;
}

// Process a single image URL
async function processImageUrl(
  url: string,
  table: string,
  id: number,
  field: string
): Promise<string | null> {
  if (!url || !isExternalUrl(url) || isLocalStorageUrl(url)) {
    totalSkipped++;
    return null;
  }

  totalProcessed++;
  console.log(`  📥 Downloading: ${url.substring(0, 80)}...`);

  try {
    const buffer = await downloadImage(url);
    const newUrl = await convertAndSave(buffer, url);
    
    migrationLog.push({
      table,
      id,
      field,
      oldUrl: url,
      newUrl,
      timestamp: new Date().toISOString(),
    });

    totalSuccess++;
    console.log(`  ✅ Saved: ${newUrl}`);
    return newUrl;
  } catch (error: any) {
    totalFailed++;
    console.log(`  ❌ Failed: ${error.message}`);
    return null;
  }
}

// Process JSON array of image URLs
async function processJsonImages(
  jsonValue: unknown,
  table: string,
  id: number,
  field: string
): Promise<unknown> {
  if (!jsonValue) return jsonValue;
  
  if (Array.isArray(jsonValue)) {
    const newArray = [];
    for (const item of jsonValue) {
      if (typeof item === 'string' && isExternalUrl(item)) {
        const newUrl = await processImageUrl(item, table, id, field);
        newArray.push(newUrl || item);
      } else {
        newArray.push(item);
      }
    }
    return newArray;
  }
  
  return jsonValue;
}

async function migratePackages() {
  console.log('\n📦 Migrating Packages...');
  const packages = await prisma.package.findMany({
    select: { id: true, images: true },
  });

  for (const pkg of packages) {
    const newImages = await processJsonImages(pkg.images, 'Package', pkg.id, 'images');
    if (JSON.stringify(newImages) !== JSON.stringify(pkg.images)) {
      await prisma.package.update({
        where: { id: pkg.id },
        data: { images: newImages as any },
      });
      console.log(`  Updated Package #${pkg.id}`);
    }
  }
}

async function migrateCars() {
  console.log('\n🚗 Migrating Cars...');
  const cars = await prisma.car.findMany({
    select: { id: true, images: true },
  });

  for (const car of cars) {
    const newImages = await processJsonImages(car.images, 'Car', car.id, 'images');
    if (JSON.stringify(newImages) !== JSON.stringify(car.images)) {
      await prisma.car.update({
        where: { id: car.id },
        data: { images: newImages as any },
      });
      console.log(`  Updated Car #${car.id}`);
    }
  }
}

async function migrateBlogs() {
  console.log('\n📝 Migrating Blogs...');
  const blogs = await prisma.blog.findMany({
    select: { id: true, image: true },
  });

  for (const blog of blogs) {
    if (blog.image && isExternalUrl(blog.image)) {
      const newUrl = await processImageUrl(blog.image, 'Blog', blog.id, 'image');
      if (newUrl) {
        await prisma.blog.update({
          where: { id: blog.id },
          data: { image: newUrl },
        });
        console.log(`  Updated Blog #${blog.id}`);
      }
    }
  }
}

async function migrateOutboundServices() {
  console.log('\n🏃 Migrating Outbound Services...');
  const services = await prisma.outboundService.findMany({
    select: { id: true, image: true },
  });

  for (const service of services) {
    if (service.image && isExternalUrl(service.image)) {
      const newUrl = await processImageUrl(service.image, 'OutboundService', service.id, 'image');
      if (newUrl) {
        await prisma.outboundService.update({
          where: { id: service.id },
          data: { image: newUrl },
        });
        console.log(`  Updated OutboundService #${service.id}`);
      }
    }
  }
}

async function migrateOutboundLocations() {
  console.log('\n📍 Migrating Outbound Locations...');
  const locations = await prisma.outboundLocation.findMany({
    select: { id: true, image: true },
  });

  for (const location of locations) {
    if (location.image && isExternalUrl(location.image)) {
      const newUrl = await processImageUrl(location.image, 'OutboundLocation', location.id, 'image');
      if (newUrl) {
        await prisma.outboundLocation.update({
          where: { id: location.id },
          data: { image: newUrl },
        });
        console.log(`  Updated OutboundLocation #${location.id}`);
      }
    }
  }
}

async function migrateClients() {
  console.log('\n🏢 Migrating Clients...');
  const clients = await prisma.client.findMany({
    select: { id: true, logo: true },
  });

  for (const client of clients) {
    if (client.logo && isExternalUrl(client.logo)) {
      const newUrl = await processImageUrl(client.logo, 'Client', client.id, 'logo');
      if (newUrl) {
        await prisma.client.update({
          where: { id: client.id },
          data: { logo: newUrl },
        });
        console.log(`  Updated Client #${client.id}`);
      }
    }
  }
}

async function migrateGallery() {
  console.log('\n🖼️  Migrating Gallery...');
  const gallery = await prisma.galleryImage.findMany({
    select: { id: true, imageUrl: true },
  });

  for (const item of gallery) {
    if (item.imageUrl && isExternalUrl(item.imageUrl)) {
      const newUrl = await processImageUrl(item.imageUrl, 'GalleryImage', item.id, 'imageUrl');
      if (newUrl) {
        await prisma.galleryImage.update({
          where: { id: item.id },
          data: { imageUrl: newUrl },
        });
        console.log(`  Updated GalleryImage #${item.id}`);
      }
    }
  }
}

async function migrateSettings() {
  console.log('\n⚙️  Migrating Settings (sliders, etc.)...');
  const settings = await prisma.setting.findMany();

  for (const setting of settings) {
    const value = setting.value as any;
    let updated = false;

    // Handle slider arrays
    if (Array.isArray(value)) {
      for (let i = 0; i < value.length; i++) {
        const item = value[i];
        if (item && typeof item === 'object') {
          // Check common image fields
          for (const imgField of ['image', 'imageUrl', 'backgroundImage', 'thumbnail', 'photo']) {
            if (item[imgField] && isExternalUrl(item[imgField])) {
              const newUrl = await processImageUrl(item[imgField], 'Setting', setting.id, `${setting.key}[${i}].${imgField}`);
              if (newUrl) {
                value[i][imgField] = newUrl;
                updated = true;
              }
            }
          }
        }
      }
    }
    // Handle object with image fields
    else if (value && typeof value === 'object') {
      for (const imgField of ['image', 'imageUrl', 'backgroundImage', 'thumbnail', 'photo', 'logo']) {
        if (value[imgField] && isExternalUrl(value[imgField])) {
          const newUrl = await processImageUrl(value[imgField], 'Setting', setting.id, `${setting.key}.${imgField}`);
          if (newUrl) {
            value[imgField] = newUrl;
            updated = true;
          }
        }
      }
    }

    if (updated) {
      await prisma.setting.update({
        where: { id: setting.id },
        data: { value },
      });
      console.log(`  Updated Setting: ${setting.key}`);
    }
  }
}

async function saveLog() {
  const logDir = path.join(process.cwd(), 'scripts');
  await mkdir(logDir, { recursive: true });
  await writeFile(LOG_FILE, JSON.stringify(migrationLog, null, 2));
  console.log(`\n📋 Migration log saved to: ${LOG_FILE}`);
}

async function main() {
  console.log('🚀 Starting Image Migration Script');
  console.log('=====================================');
  console.log('This will:');
  console.log('  1. Scan all database tables for external image URLs');
  console.log('  2. Download and convert images to WebP');
  console.log('  3. Save to /public/storage/YYYY/MM/');
  console.log('  4. Update database with new local URLs');
  console.log('  5. Create backup log at scripts/migration-log.json');
  console.log('=====================================\n');

  try {
    await migratePackages();
    await migrateCars();
    await migrateBlogs();
    await migrateOutboundServices();
    await migrateOutboundLocations();
    await migrateClients();
    await migrateGallery();
    await migrateSettings();

    await saveLog();

    console.log('\n=====================================');
    console.log('✅ Migration Complete!');
    console.log(`   Processed : ${totalProcessed}`);
    console.log(`   Success   : ${totalSuccess}`);
    console.log(`   Failed    : ${totalFailed}`);
    console.log(`   Skipped   : ${totalSkipped} (already local)`);
    console.log('=====================================');
  } catch (error) {
    console.error('\n❌ Migration failed:', error);
    process.exit(1);
  } finally {
    await prisma.$disconnect();
  }
}

main();
