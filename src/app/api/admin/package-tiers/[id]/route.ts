import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function PUT(request: Request, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params;
    const body = await request.json();
    const tier = await prisma.packageTier.update({ where: { id: parseInt(id) }, data: body });
    return NextResponse.json(tier);
  } finally {
    await prisma.$disconnect();
  }
}

export async function DELETE(request: Request, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params;
    await prisma.packageTier.delete({ where: { id: parseInt(id) } });
    return NextResponse.json({ message: 'Deleted successfully' });
  } finally {
    await prisma.$disconnect();
  }
}
