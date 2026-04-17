import { SignJWT, jwtVerify } from 'jose';

export type AuthTokenPayload = {
  id: number;
  email: string;
  role: string;
};

const DEFAULT_SECRET = 'fallback-secret-key-12345';

function getJwtSecret() {
  const secret = process.env.JWT_SECRET || DEFAULT_SECRET;
  return new TextEncoder().encode(secret);
}

export async function signAuthToken(payload: AuthTokenPayload) {
  return new SignJWT(payload)
    .setProtectedHeader({ alg: 'HS256' })
    .setIssuedAt()
    .setExpirationTime('24h')
    .sign(getJwtSecret());
}

export async function verifyAuthToken(token: string) {
  const { payload } = await jwtVerify(token, getJwtSecret());

  return {
    id: Number(payload.id),
    email: String(payload.email),
    role: String(payload.role),
  } as AuthTokenPayload;
}
