"use client";

import { HelmetProvider } from 'react-helmet-async';

export function HelmetWrapper({ children }: { children: React.ReactNode }) {
  return <HelmetProvider>{children}</HelmetProvider>;
}
