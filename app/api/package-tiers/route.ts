import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET(request: Request) {
  try {
    const { searchParams } = new URL(request.url);
    const category = searchParams.get('category');

    const where: any = { isActive: true };
    if (category) {
      where.category = category;
    }

    const tiers = await prisma.packageTier.findMany({
      where,
      orderBy: { orderPriority: 'asc' }
    });
    
    return NextResponse.json(tiers);
  } catch (error) {
    console.error('Error fetching package tiers:', error);
    return NextResponse.json(
      { error: 'Failed to fetch package tiers' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
}
