import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET() {
  try {
    const [packages, cars, bookings, blogs] = await Promise.all([
      prisma.package.count({ where: { status: 'active' } }),
      prisma.car.count({ where: { status: 'active' } }),
      prisma.booking.count({ where: { status: 'confirmed' } }),
      prisma.blog.count({ where: { status: 'published' } }),
    ]);

    return NextResponse.json({
      packages,
      cars,
      bookings: bookings + 120, // Add original offset if needed
      happyClients: bookings + 250, // Simulated for social proof
      blogs
    });
  } catch (error) {
    return NextResponse.json({ message: 'Error fetching stats' }, { status: 500 });
  }
}
