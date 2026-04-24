import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET() {
  try {
    const setting = await prisma.setting.findUnique({
      where: { key: 'landing-outbound' },
    });

    if (!setting) {
      // Return default data
      return NextResponse.json({
        hero: {
          id: 'hero',
          title: 'Corporate Outbound Training',
          subtitle: 'Bentuk pembelajaran ilmu terapan yang disimulasikan di alam terbuka dengan memadukan unsur intelegensia, fisik, dan mental.',
          imageUrl: '/assets/images/2023/10/A11-Team-Building.jpg',
        },
        slider: { id: 'slider', title: '', items: [] },
        features: { id: 'features', title: '', items: [] },
        testimonials: { id: 'testimonials', title: '', items: [] },
      });
    }

    return NextResponse.json(setting.value);
  } catch (error) {
    console.error('Error fetching landing-outbound settings:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();

    const setting = await prisma.setting.upsert({
      where: { key: 'landing-outbound' },
      update: { value: body },
      create: {
        key: 'landing-outbound',
        value: body,
      },
    });

    return NextResponse.json({ success: true, data: setting });
  } catch (error) {
    console.error('Error saving landing-outbound settings:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
