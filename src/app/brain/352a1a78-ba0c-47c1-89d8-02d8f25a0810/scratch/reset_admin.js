/* eslint-disable @typescript-eslint/no-require-imports */
const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');
const prisma = new PrismaClient();

async function main() {
  const password = await bcrypt.hash('admin123', 10);
  
  // Find the first user with 'admin' role or just update ridho's email if common
  const admin = await prisma.users.findFirst({
    where: { role: 'admin' }
  });

  if (admin) {
    await prisma.users.update({
      where: { id: admin.id },
      data: { password: password }
    });
    console.log(`Password untuk ${admin.email} telah di-reset menjadi: admin123`);
  } else {
    // Create one if none exists
    await prisma.users.create({
      data: {
        name: 'Admin Wonderful',
        email: 'admin@wonderfultoba.com',
        role: 'admin',
        password: password
      }
    });
    console.log(`Akun admin baru telah dibuat:\nEmail: admin@wonderfultoba.com\nPassword: admin123`);
  }
}

main().catch(console.error).finally(() => prisma.$disconnect());
