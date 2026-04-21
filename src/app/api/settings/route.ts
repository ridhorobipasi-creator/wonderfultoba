import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

// GET /api/settings?key=xxx
export async function GET(req: NextRequest) {
  const { searchParams } = new URL(req.url);
  const key = searchParams.get('key');
  if (!key) return NextResponse.json({ error: 'Missing key' }, { status: 400 });
  const setting = await prisma.setting.findUnique({ where: { key } });
  if (!setting) return NextResponse.json(null);

  // Prisma Json field may return a string (double-encoded) or already-parsed object
  // Handle both cases safely
  const raw = setting.value;
  if (typeof raw === 'string') {
    try {
      // Double-encoded: stored as JSON string inside Json field
      const parsed = JSON.parse(raw);
      // If still a string after first parse, parse again
      if (typeof parsed === 'string') {
        return NextResponse.json(JSON.parse(parsed));
      }
      return NextResponse.json(parsed);
    } catch {
      return NextResponse.json(raw);
    }
  }
  // Already an object (Prisma parsed it)
  return NextResponse.json(raw);
}

// POST /api/settings
export async function POST(req: NextRequest) {
  const { key, value } = await req.json();
  if (!key) return NextResponse.json({ error: 'Missing key' }, { status: 400 });
  // Always store as plain object in Json field (not double-encoded)
  const setting = await prisma.setting.upsert({
    where: { key },
    update: { value: value as any },
    create: { key, value: value as any },
  });
  return NextResponse.json(setting);
}
