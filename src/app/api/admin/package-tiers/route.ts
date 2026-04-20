import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET() {
  try {
    const tiers = await prisma.packageTier.findMany({ orderBy: { orderPriority: 'asc' } });
    return NextResponse.json(tiers);
  } finally {
    await prisma.$disconnect();
  }
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const tier = await prisma.packageTier.create({ data: body });
    return NextResponse.json(tier, { status: 201 });
  } finally {
    await prisma.$disconnect();
  }
}
