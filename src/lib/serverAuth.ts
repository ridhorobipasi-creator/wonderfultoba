import { verifyAuthToken, type AuthTokenPayload } from '@/lib/auth';

function readCookieToken(cookieHeader: string): string | null {
  const authCookie = cookieHeader
    .split(';')
    .map((v) => v.trim())
    .find((v) => v.startsWith('auth_token='));

  if (!authCookie) return null;
  const [, token] = authCookie.split('=');
  return token || null;
}

export function getTokenFromRequest(request: Request): string | null {
  const authHeader = request.headers.get('authorization');
  if (authHeader?.startsWith('Bearer ')) {
    return authHeader.slice(7).trim();
  }

  const cookieHeader = request.headers.get('cookie') || '';
  return readCookieToken(cookieHeader);
}

export async function getAuthFromRequest(request: Request): Promise<AuthTokenPayload | null> {
  const token = getTokenFromRequest(request);
  if (!token) return null;

  try {
    return await verifyAuthToken(token);
  } catch {
    return null;
  }
}

export async function requireAuthFromRequest(request: Request): Promise<AuthTokenPayload> {
  const payload = await getAuthFromRequest(request);
  if (!payload) {
    throw new Error('UNAUTHORIZED');
  }
  return payload;
}

export async function requireAdminFromRequest(request: Request): Promise<AuthTokenPayload> {
  const payload = await requireAuthFromRequest(request);
  if (payload.role !== 'admin') {
    throw new Error('FORBIDDEN');
  }
  return payload;
}
