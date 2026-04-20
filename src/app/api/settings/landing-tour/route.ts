import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET() {
  try {
    const setting = await prisma.setting.findUnique({
      where: { key: 'landing-tour' },
    });

    if (!setting) {
      // Return default data
      return NextResponse.json({
        hero: {
          id: 'hero',
          title: 'Eksplorasi Keindahan Pesona Sumatera Utara',
          subtitle: 'Nikmati pengalaman tak terlupakan bersama pemandu wisata profesional kami.',
          imageUrl: '/assets/images/2023/10/003-1.jpg',
        },
        slider: { id: 'slider', title: '', items: [] },
        features: { id: 'features', title: '', items: [] },
        testimonials: { id: 'testimonials', title: '', items: [] },
      });
    }

    return NextResponse.json(setting.value);
  } catch (error) {
    console.error('Error fetching landing-tour settings:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();

    const setting = await prisma.setting.upsert({
      where: { key: 'landing-tour' },
      update: { value: body },
      create: {
        key: 'landing-tour',
        value: body,
      },
    });

    return NextResponse.json({ success: true, data: setting });
  } catch (error) {
    console.error('Error saving landing-tour settings:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
