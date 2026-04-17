import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

// Dalam Prisma 7, kita mungkin perlu sedikit penyesuaian untuk eksekusi skrip mandiri
const prisma = new PrismaClient();

async function main() {
  const email = 'admin@wonderfultoba.com';
  const password = 'password123';
  const hashedPassword = await bcrypt.hash(password, 10);

  const user = await prisma.user.upsert({
    where: { email },
    update: {
      password: hashedPassword,
      role: 'admin',
    },
    create: {
      email,
      name: 'Super Admin',
      password: hashedPassword,
      role: 'admin',
    },
  });

  console.log('------------------------------------------');
  console.log('SUCCESS: Admin user created!');
  console.log('Email:', email);
  console.log('Password:', password);
  console.log('------------------------------------------');
}

main()
  .catch((e) => {
    console.error('Error seeding admin:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
