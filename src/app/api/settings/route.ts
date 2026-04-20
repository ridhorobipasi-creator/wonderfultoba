import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

// GET /api/settings?key=xxx
export async function GET(req: NextRequest) {
  const { searchParams } = new URL(req.url);
  const key = searchParams.get('key');
  if (!key) return NextResponse.json({ error: 'Missing key' }, { status: 400 });
  const setting = await prisma.setting.findUnique({ where: { key } });
  if (!setting) return NextResponse.json(null);
  try {
    return NextResponse.json(JSON.parse(setting.value as string));
  } catch {
    return NextResponse.json(setting.value);
  }
}

// POST /api/settings
export async function POST(req: NextRequest) {
  const { key, value } = await req.json();
  if (!key) return NextResponse.json({ error: 'Missing key' }, { status: 400 });
  const valStr = typeof value === 'string' ? value : JSON.stringify(value);
  const setting = await prisma.setting.upsert({
    where: { key },
    update: { value: valStr as any },
    create: { key, value: valStr as any },
  });
  return NextResponse.json(setting);
}
