import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getAuthFromRequest } from '@/lib/serverAuth';
import { Prisma } from '@prisma/client';

function firstImage(value: Prisma.JsonValue | null | undefined): string | undefined {
  if (!Array.isArray(value)) return undefined;
  const first = value[0];
  return typeof first === 'string' ? first : undefined;
}

// GET all bookings (Admin)
export async function GET(request: Request) {
  try {
    const payload = await getAuthFromRequest(request);
    const { searchParams } = new URL(request.url);
    const category = searchParams.get('category');

    if (!payload) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const isAdmin = payload.role === 'admin';
    const where: Prisma.BookingWhereInput = isAdmin
      ? {}
      : {
          OR: [
            { userId: payload.id },
            { customerEmail: payload.email },
          ],
        };

    if (category === 'outbound') {
      const existingAnd = where.AND
        ? (Array.isArray(where.AND) ? where.AND : [where.AND])
        : [];
      where.AND = [
        ...existingAnd,
        {
          type: 'package',
          package: {
            is: {
              isOutbound: true,
            },
          },
        },
      ];
    }

    if (category === 'tour') {
      const existingAnd = where.AND
        ? (Array.isArray(where.AND) ? where.AND : [where.AND])
        : [];
      where.AND = [
        ...existingAnd,
        {
          OR: [
            { type: 'car' },
            {
              type: 'package',
              package: {
                is: {
                  isOutbound: false,
                },
              },
            },
          ],
        },
      ];
    }

    const bookings = await prisma.booking.findMany({
      where,
      include: {
        package: {
          select: { name: true, images: true }
        },
        car: {
          select: { name: true, images: true }
        },
      },
      orderBy: {
        createdAt: 'desc',
      },
    });

    // Map to frontend expected structure
    const mapped = bookings.map(b => ({
      ...b,
      packageId: b.packageId,
      carId: b.carId,
      itemName: b.type === 'package' ? b.package?.name : b.car?.name,
      itemImage: b.type === 'package' ? firstImage(b.package?.images) : firstImage(b.car?.images),
      item_details: {
        name: b.type === 'package' ? b.package?.name : b.car?.name,
        image: b.type === 'package' ? firstImage(b.package?.images) : firstImage(b.car?.images),
      },
      customerDetails: {
        name: b.customerName,
        email: b.customerEmail,
        phone: b.customerPhone
      }
    }));

    return NextResponse.json(mapped);
  } catch (error) {
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

// POST new booking (User)
export async function POST(request: Request) {
  try {
    const payload = await getAuthFromRequest(request);
    const body = await request.json();
    const { 
      type, item_id, start_date, end_date, total_price,
      customer_name, customer_email, customer_phone, notes, custom_fields
    } = body;

    if (!type || !item_id || !start_date || !end_date || !customer_name || !customer_phone) {
      return NextResponse.json({ message: 'Incomplete booking payload' }, { status: 400 });
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
        customerEmail: payload?.email || customer_email || '',
        customerPhone: customer_phone,
        notes: notes,
        metadata: custom_fields ?? null,
        userId: payload?.id,
        status: 'pending',
      },
    });

    return NextResponse.json(booking);
  } catch (error) {
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
