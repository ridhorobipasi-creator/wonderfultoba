import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { requireAdminFromRequest } from '@/lib/serverAuth';

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const { 
      type, item_id, start_date, end_date, total_price,
      customer_name, customer_email, customer_phone, notes, custom_fields
    } = body;

    if (!type || !item_id || !start_date || !end_date || !customer_name || !customer_phone) {
      return NextResponse.json({ message: 'Missing contact information' }, { status: 400 });
    }

    const booking = await prisma.booking.create({
      data: {
        type,
        packageId: type === 'package' ? Number(item_id) : null,
        carId: type === 'car' ? Number(item_id) : null,
        startDate: new Date(start_date),
        endDate: new Date(end_date),
        totalPrice: Number(total_price),
        customerName: customer_name,
        customerEmail: customer_email,
        customerPhone: customer_phone,
        notes: notes,
        metadata: custom_fields ?? null,
        status: 'pending',
      },
    });

    return NextResponse.json(booking);
  } catch (error) {
    console.error('Booking Guest error:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function GET(request: Request) {
  // Admin view for all bookings
  try {
    await requireAdminFromRequest(request);
    const bookings = await prisma.booking.findMany({
      include: {
        package: true,
        car: true,
      },
      orderBy: {
        createdAt: 'desc',
      },
    });
    return NextResponse.json(bookings);
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
