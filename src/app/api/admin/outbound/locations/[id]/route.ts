import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function PUT(request: Request, { params }: { params: { id: string } }) {
  try {
    const body = await request.json();
    const location = await prisma.outboundLocation.update({ where: { id: parseInt(params.id) }, data: body });
    return NextResponse.json(location);
  } finally {
    await prisma.$disconnect();
  }
}

export async function DELETE(request: Request, { params }: { params: { id: string } }) {
  try {
    await prisma.outboundLocation.delete({ where: { id: parseInt(params.id) } });
    return NextResponse.json({ message: 'Deleted successfully' });
  } finally {
    await prisma.$disconnect();
  }
}
