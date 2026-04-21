/**
 * Seed destinasi dari konten yang ada di halaman user
 * Run: node scripts/seed-destinations.mjs
 */
import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

// Semua destinasi yang muncul di halaman user (slider, packages, locations, dll)
const destinations = [
  // ── SUMUT - Kab. Toba ──
  { name: 'Danau Toba', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Toba', place: 'Danau Toba', description: 'Danau vulkanik terbesar di dunia, ikon wisata Sumatera Utara' },
  { name: 'Parapat', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Toba', place: 'Parapat', description: 'Kota tepi danau, pintu masuk utama ke Danau Toba' },
  { name: 'Balige', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Toba', place: 'Balige', description: 'Ibukota Kabupaten Toba, pusat budaya Batak Toba' },

  // ── SUMUT - Kab. Samosir ──
  { name: 'Samosir', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Samosir', place: 'Pulau Samosir', description: 'Pulau di tengah Danau Toba, pusat budaya Batak' },
  { name: 'Tuktuk Siadong', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Samosir', place: 'Tuktuk Siadong', description: 'Kawasan wisata utama di Pulau Samosir' },
  { name: 'Tomok', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Samosir', place: 'Tomok', description: 'Desa wisata bersejarah di Samosir' },

  // ── SUMUT - Kab. Karo ──
  { name: 'Berastagi', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Karo', place: 'Berastagi', description: 'Kota wisata pegunungan, pasar buah & sayur segar' },
  { name: 'Gunung Sinabung', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Karo', place: 'Gunung Sinabung', description: 'Gunung berapi aktif di Tanah Karo' },
  { name: 'Sipiso-piso', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Karo', place: 'Air Terjun Sipiso-piso', description: 'Air terjun tertinggi di Sumatera Utara' },

  // ── SUMUT - Kab. Langkat ──
  { name: 'Bukit Lawang', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Langkat', place: 'Bukit Lawang', description: 'Pusat rehabilitasi orangutan & trekking hutan hujan tropis' },
  { name: 'Tangkahan', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Langkat', place: 'Tangkahan', description: 'The Hidden Paradise - wisata gajah & sungai jernih' },

  // ── SUMUT - Kab. Simalungun ──
  { name: 'Haranggaol', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Simalungun', place: 'Haranggaol', description: 'Desa tepi Danau Toba di Simalungun' },
  { name: 'Pematangsiantar', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kota Pematangsiantar', place: 'Pematangsiantar', description: 'Kota terbesar kedua di Sumatera Utara' },

  // ── SUMUT - Kab. Tapanuli Utara ──
  { name: 'Tarutung', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Tapanuli Utara', place: 'Tarutung', description: 'Ibukota Tapanuli Utara, kota rohani Batak' },

  // ── SUMUT - Kab. Humbang Hasundutan ──
  { name: 'Doloksanggul', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Humbang Hasundutan', place: 'Doloksanggul', description: 'Ibukota Humbang Hasundutan' },
  { name: 'Bakara', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Humbang Hasundutan', place: 'Bakara', description: 'Lembah indah tepi Danau Toba' },

  // ── SUMUT - Kab. Dairi ──
  { name: 'Sidikalang', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Dairi', place: 'Sidikalang', description: 'Kota kopi terbaik Sumatera Utara' },
  { name: 'Taman Simalem', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Karo', place: 'Taman Simalem Resort', description: 'Resort premium dengan view Danau Toba' },

  // ── SUMUT - Kota Medan ──
  { name: 'Medan', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kota Medan', place: 'Medan', description: 'Ibukota Sumatera Utara, kota terbesar ketiga di Indonesia' },

  // ── SUMUT - Kab. Tapanuli Tengah ──
  { name: 'Sibolga', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kota Sibolga', place: 'Sibolga', description: 'Kota pelabuhan di pantai barat Sumatera Utara' },

  // ── SUMUT - Kab. Nias ──
  { name: 'Nias', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Nias', place: 'Pulau Nias', description: 'Pulau dengan budaya megalitik dan surfing kelas dunia' },
  { name: 'Lagundri', type: 'domestic', country: 'Indonesia', region: 'Sumatera Utara', district: 'Kab. Nias Selatan', place: 'Pantai Lagundri', description: 'Pantai surfing legendaris di Nias Selatan' },
];

async function main() {
  console.log('🌏 Seeding destinasi...\n');

  // Hapus data lama yang tidak punya field baru (region=null)
  const oldCities = await prisma.city.findMany({ where: { region: null } });
  if (oldCities.length > 0) {
    console.log(`🗑️  Menghapus ${oldCities.length} data lama tanpa region...`);
    await prisma.city.deleteMany({ where: { region: null } });
  }

  let added = 0;
  let skipped = 0;

  for (const dest of destinations) {
    const slug = dest.name.toLowerCase()
      .replace(/[^a-z0-9\s]/g, '')
      .replace(/\s+/g, '-')
      .replace(/(^-|-$)/g, '');

    // Cek apakah sudah ada
    const existing = await prisma.city.findFirst({
      where: { name: dest.name }
    });

    if (existing) {
      // Update dengan data baru
      await prisma.city.update({
        where: { id: existing.id },
        data: {
          type: dest.type,
          country: dest.country,
          region: dest.region,
          district: dest.district,
          place: dest.place,
          description: dest.description,
        }
      });
      console.log(`  ✏️  Updated: ${dest.name}`);
      skipped++;
    } else {
      // Buat slug unik
      let finalSlug = slug;
      const existingSlug = await prisma.city.findUnique({ where: { slug } });
      if (existingSlug) finalSlug = `${slug}-${Date.now()}`;

      await prisma.city.create({
        data: {
          name: dest.name,
          slug: finalSlug,
          type: dest.type,
          country: dest.country,
          region: dest.region,
          district: dest.district,
          place: dest.place,
          description: dest.description,
        }
      });
      console.log(`  ✅ Added: ${dest.name} (${dest.district})`);
      added++;
    }
  }

  const total = await prisma.city.count();
  console.log(`\n✅ Selesai! Added: ${added}, Updated: ${skipped}, Total: ${total}`);
}

main()
  .catch(e => { console.error(e); process.exit(1); })
  .finally(() => prisma.$disconnect());
