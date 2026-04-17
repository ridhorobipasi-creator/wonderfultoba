import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { requireAdminFromRequest } from '@/lib/serverAuth';

export async function GET() {
  try {
    const cities = await prisma.city.findMany();
    return NextResponse.json(cities);
  } catch (error) {
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    await requireAdminFromRequest(request);
    const body = await request.json();
    const city = await prisma.city.create({
      data: body,
    });
    return NextResponse.json(city);
  } catch (error) {
    if (error instanceof Error && error.message === 'UNAUTHORIZED') {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }
    if (error instanceof Error && error.message === 'FORBIDDEN') {
      return NextResponse.json({ message: 'Forbidden' }, { status: 403 });
    }
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
