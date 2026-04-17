import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { verifyAuthToken } from '@/lib/auth';

export async function GET(request: Request) {
  try {
    const cookieHeader = request.headers.get('cookie') || '';
    const authCookie = cookieHeader
      .split(';')
      .map((v) => v.trim())
      .find((v) => v.startsWith('auth_token='));

    const token = authCookie?.split('=')[1];

    if (!token) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const payload = await verifyAuthToken(token);

    const user = await prisma.user.findUnique({
      where: { id: payload.id },
      select: {
        id: true,
        email: true,
        name: true,
        role: true,
        phone: true,
        photoURL: true,
      },
    });

    if (!user) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    return NextResponse.json({
      user: {
        ...user,
        uid: String(user.id),
      },
    });
  } catch {
    return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
  }
}

export async function PUT(request: Request) {
  try {
    const cookieHeader = request.headers.get('cookie') || '';
    const authCookie = cookieHeader
      .split(';')
      .map((v) => v.trim())
      .find((v) => v.startsWith('auth_token='));

    const token = authCookie?.split('=')[1];

    if (!token) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const payload = await verifyAuthToken(token);
    const body = await request.json();

    const user = await prisma.user.update({
      where: { id: payload.id },
      data: {
        name: body.name ?? undefined,
        phone: body.phone ?? undefined,
        photoURL: body.photoURL ?? undefined,
      },
      select: {
        id: true,
        email: true,
        name: true,
        role: true,
        phone: true,
        photoURL: true,
      },
    });

    return NextResponse.json({
      message: 'Profile updated',
      user: {
        ...user,
        uid: String(user.id),
      },
    });
  } catch {
    return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
  }
}
