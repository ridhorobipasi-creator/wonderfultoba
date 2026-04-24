import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET() {
  try {
    const services = await prisma.outboundService.findMany({
      where: { isActive: true },
      orderBy: { orderPriority: 'asc' }
    });
    return NextResponse.json(services);
  } catch (error) {
    console.error('Error fetching outbound services:', error);
    return NextResponse.json(
      { error: 'Failed to fetch services' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
}
