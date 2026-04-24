/**
 * Kontak terpusat - ubah di sini atau via env variable
 * Semua halaman menggunakan konstanta ini
 */
export const CONTACT = {
  wa: process.env.NEXT_PUBLIC_WA_NUMBER || '6281323888207',
  phone: process.env.NEXT_PUBLIC_PHONE || '+62 813-2388-8207',
  emailTour: process.env.NEXT_PUBLIC_EMAIL_TOUR || 'tour@wonderfultoba.com',
  emailOutbound: process.env.NEXT_PUBLIC_EMAIL_OUTBOUND || 'outbound@wonderfultoba.com',
};

export const waLink = (message?: string) =>
  `https://wa.me/${CONTACT.wa}${message ? `?text=${encodeURIComponent(message)}` : ''}`;
