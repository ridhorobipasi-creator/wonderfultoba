import sharp from 'sharp';
import fs from 'fs';
import path from 'path';

const INPUT_DIR = 'public/images/sumut';

const files = fs.readdirSync(INPUT_DIR).filter(f => f.match(/\.(jpg|jpeg|png|webp)$/i) && !f.includes('-400.webp') && !f.includes('-800.webp'));

files.forEach(async (file) => {
    const inputPath = path.join(INPUT_DIR, file);
    
    // We generate a 400px wide and an 800px wide version.
    const baseName = file.replace(/\.[^/.]+$/, "");
    const output400Path = path.join(INPUT_DIR, `${baseName}-400.webp`);
    const output800Path = path.join(INPUT_DIR, `${baseName}-800.webp`);
    
    // Optimize 400px version
    await sharp(inputPath)
        .resize({ width: 400, withoutEnlargement: true })
        .webp({ quality: 75, effort: 6 })
        .toFile(output400Path)
        .catch(err => console.error(`Error processing 400px ${file}:`, err));
        
    // Optimize 800px version
    await sharp(inputPath)
        .resize({ width: 800, withoutEnlargement: true })
        .webp({ quality: 75, effort: 6 })
        .toFile(output800Path)
        .catch(err => console.error(`Error processing 800px ${file}:`, err));
        
    console.log(`Optimized: ${file} (400w, 800w)`);
});
