import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET() {
  try {
    const videos = await prisma.outboundVideo.findMany({
      where: { isActive: true },
      orderBy: { orderPriority: 'asc' }
    });
    return NextResponse.json(videos);
  } catch (error) {
    console.error('Error fetching outbound videos:', error);
    return NextResponse.json(
      { error: 'Failed to fetch videos' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
}
