import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET() {
  try {
    const locations = await prisma.outboundLocation.findMany({ orderBy: { orderPriority: 'asc' } });
    return NextResponse.json(locations);
  } finally {
    await prisma.$disconnect();
  }
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const location = await prisma.outboundLocation.create({ data: body });
    return NextResponse.json(location, { status: 201 });
  } finally {
    await prisma.$disconnect();
  }
}
