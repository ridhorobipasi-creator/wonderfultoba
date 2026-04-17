interface FallbackCity {
  id: string;
  provinceId: string;
  name: string;
  description: string;
}

interface FallbackPackage {
  id: string;
  name: string;
  slug: string;
  description: string;
  price: number;
  duration: string;
  cityId: string;
  location: string;
  rating: number;
  image: string;
  includes: string[];
  excludes: string[];
  images: string[];
  status: 'active' | 'inactive';
  category?: 'tour' | 'outbound';
}

interface FallbackCar {
  id: string;
  name: string;
  type: string;
  pricePerDay: number;
  status: 'available' | 'booked';
  image: string;
  seats: number;
  fuel: string;
  transmission: string;
  rating: number;
}

export const fallbackProvinces = [
  { id: 'prov-1', name: 'Sumatera Utara', description: 'Danau Toba & Budaya Batak', image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd' },
];

export const fallbackCities: FallbackCity[] = [
  { id: 'city-1', provinceId: 'prov-1', name: 'Danau Toba', description: 'Danau vulkanik terbesar di dunia' },
  { id: 'city-2', provinceId: 'prov-1', name: 'Samosir', description: 'Pulau di tengah Danau Toba' },
  { id: 'city-3', provinceId: 'prov-1', name: 'Berastagi', description: 'Kota wisata di kaki Gunung Sinabung' },
  { id: 'city-4', provinceId: 'prov-1', name: 'Tangkahan', description: 'Surga tersembunyi di Langkat' },
  { id: 'city-5', provinceId: 'prov-1', name: 'Bukit Lawang', description: 'Pintu masuk Taman Nasional Gunung Leuser' },
  { id: 'city-6', provinceId: 'prov-1', name: 'Medan', description: 'Ibukota Sumatera Utara' },
];

export const fallbackPackages: FallbackPackage[] = [
  {
    id: 'pkg-1',
    name: 'Pesona Danau Toba & Budaya Batak',
    slug: 'pesona-danau-toba-budaya-batak',
    description: 'Jelajahi keindahan Danau Toba yang memukau dan rasakan kekayaan budaya Batak yang autentik. Nikmati pemandangan danau vulkanik terbesar di dunia dari berbagai sudut pandang.',
    price: 3500000,
    duration: '4 Hari 3 Malam',
    cityId: 'city-1',
    location: 'Danau Toba',
    rating: 4.9,
    image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800',
    includes: ['Hotel Bintang 3 di Parapat', 'Makan 3x Sehari (Masakan Batak)', 'Transport AC Medan–Toba PP', 'Guide Lokal Berpengalaman', 'Tiket Masuk Destinasi', 'Naik Kapal ke Samosir'],
    excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Tip Guide', 'Oleh-oleh'],
    images: ['https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
  },
  {
    id: 'pkg-2',
    name: 'Eksplorasi Pulau Samosir',
    slug: 'eksplorasi-pulau-samosir',
    description: 'Kunjungi Pulau Samosir yang eksotis di tengah Danau Toba. Temukan rumah adat Batak, patung Sigale-gale, dan keindahan alam yang tiada duanya.',
    price: 2800000,
    duration: '3 Hari 2 Malam',
    cityId: 'city-2',
    location: 'Samosir',
    rating: 4.8,
    image: 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800',
    includes: ['Penginapan Tepi Danau Toba', 'Makan 2x Sehari', 'Kapal Feri Ajibata–Tomok', 'Guide Lokal Samosir', 'Kunjungan Desa Adat Batak'],
    excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Souvenir'],
    images: ['https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
  },
  {
    id: 'pkg-3',
    name: 'Petualangan Berastagi & Gunung Sinabung',
    slug: 'petualangan-berastagi-gunung-sinabung',
    description: 'Rasakan kesegaran udara pegunungan Berastagi sambil menikmati pemandangan Gunung Sinabung yang megah. Kunjungi pasar buah dan sayur yang terkenal.',
    price: 2200000,
    duration: '2 Hari 1 Malam',
    cityId: 'city-3',
    location: 'Berastagi',
    rating: 4.7,
    image: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800',
    includes: ['Hotel di Berastagi', 'Makan 2x Sehari', 'Transport AC', 'Guide Lokal', 'Kunjungan Pasar Buah Berastagi'],
    excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Oleh-oleh'],
    images: ['https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
  },
  {
    id: 'pkg-4',
    name: 'Ekowisata Tangkahan & Gajah Sumatera',
    slug: 'ekowisata-tangkahan-gajah-sumatera',
    description: 'Pengalaman tak terlupakan berinteraksi dengan gajah Sumatera jinak di Tangkahan. Mandi bersama gajah di sungai jernih dan jelajahi hutan tropis yang masih alami.',
    price: 3200000,
    duration: '3 Hari 2 Malam',
    cityId: 'city-4',
    location: 'Tangkahan',
    rating: 4.9,
    image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800',
    includes: ['Penginapan Eco Lodge Tangkahan', 'Makan 3x Sehari', 'Aktivitas Mandi Gajah', 'Guide Ranger TNGL', 'Trekking Hutan Tropis'],
    excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Tip Ranger'],
    images: ['https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
  },
  {
    id: 'pkg-5',
    name: 'Trekking Bukit Lawang & Orangutan',
    slug: 'trekking-bukit-lawang-orangutan',
    description: 'Petualangan trekking di hutan hujan tropis Bukit Lawang untuk melihat orangutan Sumatera liar dalam habitat aslinya. Pengalaman yang akan selalu dikenang.',
    price: 2900000,
    duration: '3 Hari 2 Malam',
    cityId: 'city-5',
    location: 'Bukit Lawang',
    rating: 4.8,
    image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800',
    includes: ['Penginapan di Bukit Lawang', 'Makan 3x Sehari', 'Guide Trekking Bersertifikat', 'Perlengkapan Trekking', 'Kunjungan Pusat Rehabilitasi Orangutan'],
    excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Tip Guide'],
    images: ['https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
    category: 'tour'
  },
  {
    id: 'outbound-1',
    name: 'Corporate Team Building',
    slug: 'corporate-team-building',
    description: 'Program khusus dirancang untuk perusahaan yang ingin meningkatkan kerjasama tim, komunikasi, dan leadership di alam terbuka Danau Toba.',
    price: 1500000,
    duration: '2 Hari 1 Malam',
    cityId: 'city-1',
    location: 'Parapat & Samosir',
    rating: 4.8,
    image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=800',
    includes: ['Penginapan', 'Fasilitator Utama', 'Makan 3x Sehari', 'Instruktur Outbound Profesional', 'Sertifikat Tim'],
    excludes: ['Tiket Pesawat', 'Pengeluaran Pribadi'],
    images: ['https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
    category: 'outbound'
  },
  {
    id: 'outbound-2',
    name: 'Jungle Survival Adventure',
    slug: 'jungle-survival-adventure',
    description: 'Ekspedisi mendebarkan menjelajahi hutan hujan tropis. Pelajari cara bertahan hidup, navigasi darat, dan kerjasama dalam kondisi ekstrim.',
    price: 2100000,
    duration: '3 Hari 2 Malam',
    cityId: 'city-5',
    location: 'Bukit Lawang',
    rating: 4.9,
    image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800',
    includes: ['Camp Hutan', 'Makan 3x Sehari', 'Peralatan Bertahan Hidup', 'Guide Ranger', 'Materi Outbound Ekstrem'],
    excludes: ['Tiket Pesawat', 'Pengeluaran Pribadi'],
    images: ['https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
    category: 'outbound'
  },
  {
    id: 'outbound-3',
    name: 'Fun Family Gathering',
    slug: 'fun-family-gathering',
    description: 'Aktivitas outbound ringan yang menyenangkan untuk keluarga besar atau komunitas. Cocok untuk semua umur dengan permainan interaktif.',
    price: 450000,
    duration: '1 Hari',
    cityId: 'city-3',
    location: 'Berastagi',
    rating: 4.7,
    image: 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=800',
    includes: ['Tenda Istirahat', 'Makan Siang', 'Fasilitator Anak & Dewasa', 'Souvenir', 'Sesi Pertukaran Kado'],
    excludes: ['Tiket PP Berastagi', 'Pengeluaran Tambahan'],
    images: ['https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=800'],
    status: 'active',
    category: 'outbound'
  }
];

export const fallbackCars: FallbackCar[] = [
  { id: 'car-1', name: 'Toyota Avanza', type: 'MPV', pricePerDay: 450000, status: 'available', image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800', seats: 7, fuel: 'Bensin', transmission: 'Manual', rating: 4.8 },
  { id: 'car-2', name: 'Mitsubishi Pajero Sport', type: 'SUV', pricePerDay: 1200000, status: 'available', image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800', seats: 7, fuel: 'Solar', transmission: 'Otomatis', rating: 4.9 },
  { id: 'car-3', name: 'Toyota Innova Reborn', type: 'MPV', pricePerDay: 750000, status: 'available', image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800', seats: 8, fuel: 'Solar', transmission: 'Manual', rating: 4.7 },
  { id: 'car-4', name: 'Honda CR-V', type: 'SUV', pricePerDay: 900000, status: 'available', image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800', seats: 5, fuel: 'Bensin', transmission: 'Otomatis', rating: 4.8 },
  { id: 'car-5', name: 'Toyota Alphard', type: 'Luxury', pricePerDay: 2500000, status: 'available', image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800', seats: 7, fuel: 'Bensin', transmission: 'Otomatis', rating: 5.0 },
];

export const fallbackBlogs = [
  {
    id: 'blog-1',
    title: '5 Alasan Mengapa Danau Toba Adalah Destinasi Sempurna untuk Outbound',
    excerpt: 'Menyatukan keindahan alam dan fasilitas lengkap, Danau Toba selalu menjadi pilihan utama untuk team building korporasi.',
    category: 'outbound',
    image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=800',
    date: '12 Okt 2023',
  },
  {
    id: 'blog-2',
    title: 'Tips Memilih Paket Wisata Keluarga di Berastagi',
    excerpt: 'Berastagi menawarkan suhu yang sejuk dan agrowisata yang menarik. Berikut cara memilih aktivitas yang ramah anak.',
    category: 'tour',
    image: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800',
    date: '08 Sep 2023',
  },
  {
    id: 'blog-3',
    title: 'Pentingnya Certified First-Aider dalam Kegiatan Hutan',
    excerpt: 'Standar keselamatan kami mewajibkan keberadaan ahli P3K di setiap aktivitas jungle trekking dan survival.',
    category: 'outbound',
    image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800',
    date: '21 Agu 2023',
  }
];
