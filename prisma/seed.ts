import { PrismaClient } from '@prisma/client'
import bcrypt from 'bcryptjs'

const prisma = new PrismaClient()

async function main() {
  const password = await bcrypt.hash('password123', 10)

  // Create Admin Umum
  await prisma.user.upsert({
    where: { email: 'admin@wonderfultoba.com' },
    update: {},
    create: {
      email: 'admin@wonderfultoba.com',
      name: 'Admin Umum',
      password: password,
      role: 'admin_umum',
    },
  })

  // Create Admin Tour
  await prisma.user.upsert({
    where: { email: 'tour@wonderfultoba.com' },
    update: {},
    create: {
      email: 'tour@wonderfultoba.com',
      name: 'Admin Tour',
      password: password,
      role: 'admin_tour',
    },
  })

  // Create Admin Outbound
  await prisma.user.upsert({
    where: { email: 'outbound@wonderfultoba.com' },
    update: {},
    create: {
      email: 'outbound@wonderfultoba.com',
      name: 'Admin Outbound',
      password: password,
      role: 'admin_outbound',
    },
  })

  console.log('Seed completed successfully')
}

main()
  .catch((e) => {
    console.error(e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
