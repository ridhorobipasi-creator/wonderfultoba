import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Standalone output for cPanel deployment
  output: 'standalone',
  
  serverExternalPackages: ['@prisma/client'],
  
  // Turbopack config (untuk Next.js 16)
  turbopack: {},
  
  // Image optimization
  images: {
    remotePatterns: [
      {
        protocol: 'https',
        hostname: '**',
      },
      {
        protocol: 'http',
        hostname: 'localhost',
      },
    ],
    unoptimized: process.env.NODE_ENV === 'production',
  },
  
  // Compress output
  compress: true,
  
  // Production source maps (disable untuk performa)
  productionBrowserSourceMaps: false,
};

export default nextConfig;
