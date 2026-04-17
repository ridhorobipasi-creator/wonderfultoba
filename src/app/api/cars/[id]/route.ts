import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { requireAdminFromRequest } from '@/lib/serverAuth';

export async function GET(
  _request: NextRequest,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const car = await prisma.car.findUnique({
      where: { id: Number(id) },
    });

    if (!car) {
      return NextResponse.json({ message: 'Car not found' }, { status: 404 });
    }

    return NextResponse.json(car);
  } catch (error) {
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function PUT(
  request: NextRequest,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    await requireAdminFromRequest(request);
    const data = await request.json();
    const car = await prisma.car.update({
      where: { id: Number(id) },
      data: {
        name: data.name,
        type: data.type,
        capacity: Number(data.capacity || data.seats || 0),
        transmission: data.transmission,
        fuel: data.fuel,
        price: Number(data.price || data.price_per_day || 0),
        priceWithDriver: Number(data.price_with_driver || 0),
        images: data.images || (data.image ? [data.image] : []),
        description: data.description,
        terms: data.terms,
        features: data.features || [],
        includes: data.includes || [],
        status: data.status || 'available',
        isFeatured: !!data.is_featured,
        sortOrder: Number(data.sort_order || 0),
        metaTitle: data.meta_title,
        metaDescription: data.meta_description,
        pricingDetails: data.pricing_details || [],
        translations: data.translations || {},
      },
    });

    return NextResponse.json(car);
  } catch (error) {
    if (error instanceof Error && error.message === 'UNAUTHORIZED') {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }
    if (error instanceof Error && error.message === 'FORBIDDEN') {
      return NextResponse.json({ message: 'Forbidden' }, { status: 403 });
    }
    console.error('Car PUT error:', error);
    return NextResponse.json({ message: 'Failed to update car' }, { status: 500 });
  }
}

export async function DELETE(
  request: NextRequest,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    await requireAdminFromRequest(request);
    await prisma.car.delete({
      where: { id: Number(id) },
    });
    return NextResponse.json({ message: 'Car deleted' });
  } catch (error) {
    if (error instanceof Error && error.message === 'UNAUTHORIZED') {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }
    if (error instanceof Error && error.message === 'FORBIDDEN') {
      return NextResponse.json({ message: 'Forbidden' }, { status: 403 });
    }
    return NextResponse.json({ message: 'Failed to delete car' }, { status: 500 });
  }
}
