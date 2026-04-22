import { PrismaClient } from '@prisma/client'

// Check if we are in static preview mode
const IS_STATIC_PREVIEW = true; // Force true for preview

const prismaClient = new PrismaClient({
  log: ['query'],
});

// Proxy to avoid DB connection errors during static preview
export const prisma = IS_STATIC_PREVIEW 
  ? new Proxy({} as any, {
      get: (target, prop) => {
        // Mock common prisma methods
        return {
          findMany: async () => [],
          findUnique: async () => null,
          findFirst: async () => null,
          count: async () => 0,
          create: async (args: any) => args.data,
          update: async (args: any) => args.data,
          delete: async () => ({}),
          upsert: async (args: any) => args.update || args.create,
        };
      }
    })
  : ( (global as any).prisma || prismaClient );

if (process.env.NODE_ENV !== 'production' && !IS_STATIC_PREVIEW) (global as any).prisma = prisma;

