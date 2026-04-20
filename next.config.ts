import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  serverExternalPackages: ['@prisma/client'],
  
  // Output standalone untuk deployment
  output: 'standalone',
  
  // Webpack configuration untuk cPanel compatibility
  webpack: (config, { isServer }) => {
    if (!isServer) {
      config.resolve.fallback = {
        ...config.resolve.fallback,
        fs: false,
        net: false,
        tls: false,
        crypto: false,
      };
    }
    return config;
  },
  
  // Image optimization
  images: {
    domains: ['localhost'],
    unoptimized: process.env.NODE_ENV === 'production', // Disable optimization di shared hosting
  },
  
  // Compress output
  compress: true,
  
  // Production source maps (optional, disable untuk performa)
  productionBrowserSourceMaps: false,
};

export default nextConfig;
