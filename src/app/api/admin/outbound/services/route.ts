import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

// GET all services (including inactive)
export async function GET() {
  try {
    const services = await prisma.outboundService.findMany({
      orderBy: { orderPriority: 'asc' }
    });
    return NextResponse.json(services);
  } catch (error) {
    console.error('Error fetching services:', error);
    return NextResponse.json(
      { error: 'Failed to fetch services' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
}

// POST create new service
export async function POST(request: Request) {
  try {
    const body = await request.json();
    
    const service = await prisma.outboundService.create({
      data: {
        title: body.title,
        shortDesc: body.shortDesc,
        detailDesc: body.detailDesc,
        icon: body.icon,
        image: body.image,
        orderPriority: body.orderPriority || 0,
        isActive: body.isActive !== undefined ? body.isActive : true
      }
    });
    
    return NextResponse.json(service, { status: 201 });
  } catch (error) {
    console.error('Error creating service:', error);
    return NextResponse.json(
      { error: 'Failed to create service' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
}
