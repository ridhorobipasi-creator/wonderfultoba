import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { requireAdminFromRequest } from '@/lib/serverAuth';

export async function GET(
  _request: NextRequest,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    const isId = !isNaN(Number(slug));
    const pkg = await prisma.package.findUnique({
      where: isId ? { id: Number(slug) } : { slug },
      include: { city: true },
    });

    if (!pkg) {
      return NextResponse.json({ message: 'Package not found' }, { status: 404 });
    }

    return NextResponse.json(pkg);
  } catch (error) {
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function PUT(
  request: NextRequest,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    await requireAdminFromRequest(request);
    const data = await request.json();
    const isId = !isNaN(Number(slug));
    
    const pkg = await prisma.package.update({
      where: isId ? { id: Number(slug) } : { slug },
      data: {
        name: data.name,
        slug: data.slug,
        shortDescription: data.short_description,
        description: data.description,
        locationTag: data.location_tag,
        price: Number(data.price || 0),
        childPrice: Number(data.child_price || 0),
        priceDisplay: data.price_display,
        duration: data.duration,
        images: data.images || [],
        includes: data.includes || [],
        excludes: data.excludes || [],
        pricingDetails: data.pricing_details || [],
        itinerary: data.itinerary || [],
        itineraryText: data.itinerary_text,
        dronePrice: Number(data.drone_price || 0),
        droneLocation: data.drone_location,
        notes: data.notes,
        status: data.status || 'active',
        isFeatured: !!data.is_featured,
        isOutbound: data.category === 'outbound',
        sortOrder: Number(data.sort_order || 0),
        metaTitle: data.meta_title,
        metaDescription: data.meta_description,
        translations: data.translations || {},
        cityId: data.city_id ? Number(data.city_id) : null,
      },
    });

    return NextResponse.json(pkg);
  } catch (error) {
    if (error instanceof Error && error.message === 'UNAUTHORIZED') {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }
    if (error instanceof Error && error.message === 'FORBIDDEN') {
      return NextResponse.json({ message: 'Forbidden' }, { status: 403 });
    }
    console.error('Package PUT error:', error);
    return NextResponse.json({ message: 'Failed to update package' }, { status: 500 });
  }
}

export async function DELETE(
  request: NextRequest,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    await requireAdminFromRequest(request);
    const isId = !isNaN(Number(slug));
    await prisma.package.delete({
      where: isId ? { id: Number(slug) } : { slug },
    });
    return NextResponse.json({ message: 'Package deleted' });
  } catch (error) {
    if (error instanceof Error && error.message === 'UNAUTHORIZED') {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }
    if (error instanceof Error && error.message === 'FORBIDDEN') {
      return NextResponse.json({ message: 'Forbidden' }, { status: 403 });
    }
    return NextResponse.json({ message: 'Failed to delete package' }, { status: 500 });
  }
}
