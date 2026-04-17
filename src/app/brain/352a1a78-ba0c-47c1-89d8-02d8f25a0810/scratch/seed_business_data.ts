import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  // Cities
  const samosir = await prisma.city.upsert({
    where: { slug: 'samosir' },
    update: {},
    create: { name: 'Pulau Samosir', slug: 'samosir' },
  });

  const parapat = await prisma.city.upsert({
    where: { slug: 'parapat' },
    update: {},
    create: { name: 'Parapat', slug: 'parapat' },
  });

  // Packages
  await prisma.package.upsert({
    where: { slug: 'pesona-danau-toba-3d2n' },
    update: {},
    create: {
      name: 'Pesona Danau Toba 3D2N',
      slug: 'pesona-danau-toba-3d2n',
      description: 'Nikmati keindahan Danau Toba selama 3 hari 2 malam dengan mengunjungi Pulau Samosir dan Bukit Holbung.',
      price: 2500000,
      duration: '3 Hari 2 Malam',
      images: [
        'https://images.unsplash.com/photo-1596402184320-417e7178b2cd',
        'https://images.unsplash.com/photo-1626497764746-6dc3e542b357'
      ],
      includes: ['Transportasi AC', 'Hotel 2 Malam', 'Makan sesuai program', 'Tiket Masuk'],
      excludes: ['Tiket Pesawat', 'Tips Driver', 'Keperluan Pribadi'],
      status: 'active',
      isOutbound: false,
      cityId: samosir.id
    },
  });

  // Cars
  await prisma.car.create({
    data: {
      name: 'Toyota Avanza Facelift',
      type: 'MPV',
      capacity: 7,
      transmission: 'Manual',
      fuel: 'Bensin',
      price: 550000,
      images: ['https://images.unsplash.com/photo-1592193660979-24177779426f'],
      status: 'active'
    }
  });

  console.log('Seeding finished!');
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
