import type { Metadata } from "next";
import { Plus_Jakarta_Sans } from "next/font/google";
import { Toaster } from "sonner";
import { HelmetWrapper } from "@/components/HelmetWrapper";
import StaticMockInitializer from "@/components/StaticMockInitializer";
import "./globals.css";

const plusJakartaSans = Plus_Jakarta_Sans({
  subsets: ["latin"],
  variable: "--font-sans",
});

export const metadata: Metadata = {
  title: "Wonderful Toba | Premium Tour & Corporate Outbound",
  description: "Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id" className={`${plusJakartaSans.variable} antialiased`}>
      <body>
        <StaticMockInitializer />
        <HelmetWrapper>
          {children}
        </HelmetWrapper>
        <Toaster position="top-right" richColors />
      </body>
    </html>
  );
}

