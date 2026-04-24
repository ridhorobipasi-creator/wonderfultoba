import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET() {
  try {
    const videos = await prisma.outboundVideo.findMany({
      orderBy: { orderPriority: 'asc' }
    });
    return NextResponse.json(videos);
  } finally {
    await prisma.$disconnect();
  }
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const video = await prisma.outboundVideo.create({ data: body });
    return NextResponse.json(video, { status: 201 });
  } finally {
    await prisma.$disconnect();
  }
}
