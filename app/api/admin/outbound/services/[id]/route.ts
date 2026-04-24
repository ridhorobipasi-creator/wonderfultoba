import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function PUT(request: Request, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params;
    const body = await request.json();
    const service = await prisma.outboundService.update({
      where: { id: parseInt(id) },
      data: {
        title: body.title,
        shortDesc: body.shortDesc,
        detailDesc: body.detailDesc,
        icon: body.icon,
        image: body.image,
        orderPriority: body.orderPriority,
        isActive: body.isActive,
      },
    });
    return NextResponse.json(service);
  } catch (error) {
    console.error('Error updating service:', error);
    return NextResponse.json({ error: 'Failed to update service' }, { status: 500 });
  } finally {
    await prisma.$disconnect();
  }
}

export async function DELETE(request: Request, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params;
    await prisma.outboundService.delete({ where: { id: parseInt(id) } });
    return NextResponse.json({ message: 'Service deleted successfully' });
  } catch (error) {
    console.error('Error deleting service:', error);
    return NextResponse.json({ error: 'Failed to delete service' }, { status: 500 });
  } finally {
    await prisma.$disconnect();
  }
}
