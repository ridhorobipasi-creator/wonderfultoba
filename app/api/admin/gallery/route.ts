import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET() {
  try {
    const images = await prisma.galleryImage.findMany({ orderBy: { orderPriority: 'asc' } });
    return NextResponse.json(images);
  } finally {
    await prisma.$disconnect();
  }
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const image = await prisma.galleryImage.create({ data: body });
    return NextResponse.json(image, { status: 201 });
  } finally {
    await prisma.$disconnect();
  }
}
