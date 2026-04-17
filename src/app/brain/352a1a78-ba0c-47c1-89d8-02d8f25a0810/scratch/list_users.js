/* eslint-disable @typescript-eslint/no-require-imports */
const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const users = await prisma.users.findMany();
  console.log('--- DAFTAR PENGGUNA ---');
  users.forEach(u => {
    console.log(`Nama: ${u.name} | Email: ${u.email} | Role: ${u.role}`);
  });
}

main().catch(console.error).finally(() => prisma.$disconnect());
