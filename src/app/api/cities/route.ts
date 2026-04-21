import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { requireAdminFromRequest } from '@/lib/serverAuth';

export async function GET() {
  try {
    const cities = await prisma.city.findMany({
      // Setelah restart dev server + prisma generate, bisa pakai multi-field orderBy:
      // orderBy: [{ type: 'asc' }, { region: 'asc' }, { district: 'asc' }, { name: 'asc' }],
      orderBy: { name: 'asc' },
    });
    return NextResponse.json(cities);
  } catch (error) {
    console.error('Cities GET error:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    await requireAdminFromRequest(request);
    const body = await request.json();

    const slug = (body.slug || body.name)
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '');

    // Ensure unique slug
    const existing = await prisma.city.findUnique({ where: { slug } });
    const finalSlug = existing ? `${slug}-${Date.now()}` : slug;

    const city = await prisma.city.create({
      data: {
        name: body.name,
        slug: finalSlug,
        type: body.type || 'domestic',
        country: body.country || 'Indonesia',
        region: body.region || null,
        district: body.district || null,
        place: body.place || null,
        description: body.description || null,
      },
    });
    return NextResponse.json(city);
  } catch (error) {
    if (error instanceof Error && (error.message === 'UNAUTHORIZED' || error.message === 'FORBIDDEN')) {
      return NextResponse.json({ message: error.message }, { status: 403 });
    }
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
