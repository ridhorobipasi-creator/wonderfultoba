import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { requireAdminFromRequest } from '@/lib/serverAuth';

export async function GET(request: Request) {
  try {
    const { searchParams } = new URL(request.url);
    const category = searchParams.get('category'); 
    const isFeatured = searchParams.get('featured');
    const status = searchParams.get('status') || 'active';
    
    const where: Record<string, unknown> = {};
    if (category) where.isOutbound = (category === 'outbound');
    if (isFeatured === 'true') where.isFeatured = true;
    if (status !== 'all') where.status = status;

    const packages = await prisma.package.findMany({
      where,
      include: {
        city: true,
      },
      orderBy: {
        sortOrder: 'asc',
      },
    });

    return NextResponse.json(packages);
  } catch (error) {
    console.error('Packages GET error:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    await requireAdminFromRequest(request);
    const data = await request.json();
    
    const pkg = await prisma.package.create({
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
    console.error('Packages POST error:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
