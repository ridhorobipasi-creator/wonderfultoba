import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

export async function GET() {
  try {
    const clients = await prisma.client.findMany({ orderBy: { orderPriority: 'asc' } });
    return NextResponse.json(clients);
  } finally {
    await prisma.$disconnect();
  }
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const client = await prisma.client.create({ data: body });
    return NextResponse.json(client, { status: 201 });
  } finally {
    await prisma.$disconnect();
  }
}
