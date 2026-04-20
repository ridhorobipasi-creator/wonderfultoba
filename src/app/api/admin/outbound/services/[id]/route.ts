import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

// PUT update service
export async function PUT(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const body = await request.json();
    const id = parseInt(params.id);
    
    const service = await prisma.outboundService.update({
      where: { id },
      data: {
        title: body.title,
        shortDesc: body.shortDesc,
        detailDesc: body.detailDesc,
        icon: body.icon,
        image: body.image,
        orderPriority: body.orderPriority,
        isActive: body.isActive
      }
    });
    
    return NextResponse.json(service);
  } catch (error) {
    console.error('Error updating service:', error);
    return NextResponse.json(
      { error: 'Failed to update service' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
}

// DELETE service
export async function DELETE(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const id = parseInt(params.id);
    
    await prisma.outboundService.delete({
      where: { id }
    });
    
    return NextResponse.json({ message: 'Service deleted successfully' });
  } catch (error) {
    console.error('Error deleting service:', error);
    return NextResponse.json(
      { error: 'Failed to delete service' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
}
