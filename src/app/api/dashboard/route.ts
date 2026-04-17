import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET() {
  try {
    const [
      totalBookings,
      pendingBookings,
      confirmedBookings,
      tourPackages,
      outboundPackages,
      totalCars,
      totalBlogs,
      bookings,
    ] = await Promise.all([
      prisma.booking.count(),
      prisma.booking.count({ where: { status: 'pending' } }),
      prisma.booking.count({ where: { status: 'confirmed' } }),
      prisma.package.count({ where: { isOutbound: false } }),
      prisma.package.count({ where: { isOutbound: true } }),
      prisma.car.count(),
      prisma.blog.count(),
      prisma.booking.findMany({
        take: 5,
        orderBy: { createdAt: 'desc' },
        include: {
          package: { select: { name: true, images: true } },
          car: { select: { name: true, images: true } },
        },
      }),
    ]);

    // Calculate total revenue from confirmed bookings
    const confirmedList = await prisma.booking.findMany({
      where: { status: 'confirmed' },
      select: { totalPrice: true },
    });
    
    const totalRevenue = confirmedList.reduce((acc, curr) => acc + curr.totalPrice, 0);

    const recentBookings = bookings.map(b => ({
      id: b.id,
      customer_name: b.customerName,
      type: b.type,
      total_price: b.totalPrice,
      status: b.status,
      start_date: b.startDate.toISOString().split('T')[0],
      item_name: b.package?.name || b.car?.name || 'N/A',
    }));

    return NextResponse.json({
      totalBookings,
      pendingBookings,
      confirmedBookings,
      tourPackages,
      outboundPackages,
      totalCars,
      totalBlogs,
      totalRevenue,
      recentBookings,
    });
  } catch (error) {
    console.error('Dashboard API Error:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
