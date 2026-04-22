export const mockTours = [
  {
    id: 1,
    slug: 'danau-toba-eksklusif',
    name: 'Danau Toba Eksklusif',
    category: 'Relaksasi',
    price: 2500000,
    images: ['https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1200'],
    image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1200',
    description: 'Nikmati kemewahan mengitari Danau Toba dengan kapal pesiar pribadi dan menginap di resort terbaik.',
    shortDescription: 'Wisata premium di danau vulkanik terbesar dunia.',
    duration: '3 Hari 2 Malam',
    rating: 4.9,
    locationTag: 'Danau Toba',
    is_published: true,
    createdAt: new Date().toISOString(),
  },
  {
    id: 2,
    slug: 'safari-bukit-lawang',
    name: 'Safari Bukit Lawang',
    category: 'Petualangan',
    price: 3500000,
    images: ['https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&q=80&w=1200'],
    image: 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&q=80&w=1200',
    description: 'Jelajahi Taman Nasional Gunung Leuser dan temui Orangutan Sumatera di habitat aslinya.',
    shortDescription: 'Trekking hutan hujan tropis & bertemu Orangutan.',
    duration: '2 Hari 1 Malam',
    rating: 4.8,
    locationTag: 'Bukit Lawang',
    is_published: true,
    createdAt: new Date().toISOString(),
  },
  {
    id: 3,
    slug: 'berastagi-highland',
    name: 'Berastagi Highland Tour',
    category: 'Budaya',
    price: 1500000,
    images: ['https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=1200'],
    image: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=1200',
    description: 'Kunjungi pasar buah Berastagi, Pagoda Taman Alam Lumbini, dan nikmati udara sejuk pegunungan.',
    shortDescription: 'Menikmati udara sejuk & pemandangan gunung api.',
    duration: 'Full Day Tour',
    rating: 4.7,
    locationTag: 'Berastagi',
    is_published: true,
    createdAt: new Date().toISOString(),
  },
  {
    id: 4,
    slug: 'tangkahan-hidden-paradise',
    name: 'Tangkahan Hidden Paradise',
    category: 'Ekowisata',
    price: 2800000,
    images: ['https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=1200'],
    image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=1200',
    description: 'Mandi bersama gajah di sungai yang jernih dan jelajahi hutan Tangkahan.',
    shortDescription: 'Interaksi gajah & alam liar Tangkahan.',
    duration: '2 Hari 1 Malam',
    rating: 4.9,
    locationTag: 'Tangkahan',
    is_published: true,
    createdAt: new Date().toISOString(),
  },
  {
    id: 5,
    slug: 'medan-heritage-tour',
    name: 'Medan Heritage & Foodie',
    category: 'Kuliner',
    price: 950000,
    images: ['https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&q=80&w=1200'],
    image: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&q=80&w=1200',
    description: 'Menelusuri sejarah Kota Medan dan mencicipi berbagai kuliner legendarisnya.',
    shortDescription: 'Eksplorasi sejarah & rasa di Kota Medan.',
    duration: 'Full Day',
    rating: 4.6,
    locationTag: 'Medan',
    is_published: true,
    createdAt: new Date().toISOString(),
  }
];

export const mockBlogs = [
  {
    id: 1,
    title: '5 Spot Foto Terbaik di Pulau Samosir',
    category: 'Travel Tips',
    image: 'https://images.unsplash.com/photo-1615460547219-7419e6231a41?auto=format&fit=crop&q=80&w=800',
    excerpt: 'Temukan sudut-sudut tersembunyi di Samosir yang akan mempercantik feed Instagram Anda.',
    is_published: true,
    view_count: 1250,
    createdAt: new Date().toISOString(),
    author: { name: 'Admin' }
  },
  {
    id: 2,
    title: 'Panduan Pertama Kali ke Bukit Lawang',
    category: 'Guide',
    image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800',
    excerpt: 'Semua yang perlu Anda ketahui sebelum melakukan jungle trekking di Gunung Leuser.',
    is_published: true,
    view_count: 850,
    createdAt: new Date().toISOString(),
    author: { name: 'Admin' }
  },
  {
    id: 3,
    title: 'Kuliner Legendaris di Kota Medan',
    category: 'Kuliner',
    image: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&q=80&w=800',
    excerpt: 'Dari Mie Balap hingga Durian Ucok, siapkan perut Anda untuk petualangan rasa.',
    is_published: true,
    view_count: 2100,
    createdAt: new Date().toISOString(),
    author: { name: 'Admin' }
  },
  {
    id: 4,
    title: 'Pakaian Terbaik untuk Trekking di Hutan',
    category: 'Tips',
    image: 'https://images.unsplash.com/photo-1501555088652-021faa106b9b?auto=format&fit=crop&q=80&w=800',
    excerpt: 'Jangan salah kostum saat masuk hutan. Inilah daftar perlengkapan yang wajib Anda bawa.',
    is_published: true,
    view_count: 450,
    createdAt: new Date().toISOString(),
    author: { name: 'Admin' }
  }
];

export const mockCars = [
  { id: 1, name: 'Toyota Innova Zenix', type: 'MPV', price: 850000, image: 'https://images.unsplash.com/photo-1621135802920-133df287f89c?auto=format&fit=crop&q=80&w=800', is_available: true },
  { id: 2, name: 'Toyota Hiace Premio', type: 'Van', price: 1500000, image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800', is_available: true },
  { id: 3, name: 'Toyota Fortuner GR', type: 'SUV', price: 1800000, image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800', is_available: true },
  { id: 4, name: 'Mitsubishi Xpander', type: 'MPV', price: 550000, image: 'https://images.unsplash.com/photo-1606148632349-4348123df7a6?auto=format&fit=crop&q=80&w=800', is_available: true }
];

export const mockBookings = [
  { id: 'BK-001', customer_name: 'John Doe', tour_name: 'Danau Toba Eksklusif', status: 'Confirmed', total_price: 2500000, booking_date: new Date().toISOString() },
  { id: 'BK-002', customer_name: 'Jane Smith', tour_name: 'Toyota Innova Zenix Rent', status: 'Pending', total_price: 850000, booking_date: new Date().toISOString() },
  { id: 'BK-003', customer_name: 'Robert Brown', tour_name: 'Safari Bukit Lawang', status: 'Confirmed', total_price: 3500000, booking_date: new Date().toISOString() },
  { id: 'BK-004', customer_name: 'Michael Wilson', tour_name: 'Medan Heritage', status: 'Cancelled', total_price: 950000, booking_date: new Date().toISOString() },
  { id: 'BK-005', customer_name: 'Sarah Lee', tour_name: 'Berastagi Highland', status: 'Confirmed', total_price: 1500000, booking_date: new Date().toISOString() }
];

export const mockUser = { id: 1, name: 'Demo Admin', email: 'admin@demo.com', role: 'ADMIN' };

export const mockOutboundServices = [
  { title: 'Team Building', shortDesc: 'Membangun Kerjasama', icon: 'Users', image: 'https://images.unsplash.com/photo-1522071823991-b580970ad00d?auto=format&fit=crop&q=80&w=800', detailDesc: 'Program pembentukan tim yang solid melalui simulasi permainan lapangan.' },
  { title: 'Fun Games', shortDesc: 'Keceriaan Bersama', icon: 'Smile', image: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=800', detailDesc: 'Aktivitas ringan yang mengedepankan tawa dan kebahagiaan peserta.' },
  { title: 'Leadership Training', shortDesc: 'Kepemimpinan', icon: 'Target', image: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=800', detailDesc: 'Melatih jiwa kepemimpinan dan pengambilan keputusan di bawah tekanan.' },
  { title: 'Paintball Combat', shortDesc: 'Strategi Tim', icon: 'Swords', image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800', detailDesc: 'Simulasi tempur untuk melatih koordinasi dan strategi kelompok.' },
  { title: 'River Rafting', shortDesc: 'Pacu Adrenalin', icon: 'Compass', image: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=800', detailDesc: 'Mengarungi sungai dengan tim untuk melatih kekompakan dan nyali.' }
];

export const mockVideos = [
  { title: 'Highlight Event BI', youtubeUrl: 'https://www.youtube.com/embed/dQw4w9WgXcQ' },
  { title: 'Outbound Pelindo', youtubeUrl: 'https://www.youtube.com/embed/dQw4w9WgXcQ' },
  { title: 'Family Gathering Telkom', youtubeUrl: 'https://www.youtube.com/embed/dQw4w9WgXcQ' }
];

export const mockLocations = [
  { name: 'Niagara Hotel Parapat', image: 'https://images.unsplash.com/photo-1445013543409-ad997cd5e96d?auto=format&fit=crop&q=80&w=800' },
  { name: 'Simalem Resort', image: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800' },
  { name: 'The Hill Sibolangit', image: 'https://images.unsplash.com/photo-1510798831971-661eb04b3739?auto=format&fit=crop&q=80&w=800' },
  { name: 'Mikie Holiday Berastagi', image: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=800' }
];

export const mockClients = [
  { logo: 'https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png' },
  { logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/2560px-Bank_Mandiri_logo_2016.svg.png' },
  { logo: 'https://upload.wikimedia.org/wikipedia/id/thumb/a/a2/Logo_Pertamina.svg/1200px-Logo_Pertamina.svg.png' },
  { logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png' },
  { logo: 'https://upload.wikimedia.org/wikipedia/id/thumb/0/04/Logo_Telkomsel_2021.svg/1200px-Logo_Telkomsel_2021.svg.png' }
];

export const mockGallery = [
  { imageUrl: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1510798831971-661eb04b3739?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1522071823991-b580970ad00d?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=800' },
  { imageUrl: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=800' }
];

export const mockCities = [
  { id: 1, name: 'Medan', province: 'Sumatera Utara' },
  { id: 2, name: 'Parapat', province: 'Sumatera Utara' },
  { id: 3, name: 'Berastagi', province: 'Sumatera Utara' },
  { id: 4, name: 'Bukit Lawang', province: 'Sumatera Utara' },
  { id: 5, name: 'Tangkahan', province: 'Sumatera Utara' }
];

export const mockPackageTiers = [
  { id: 1, name: 'Basic', description: 'Fasilitas standar hemat biaya' },
  { id: 2, name: 'Standard', description: 'Pilihan paling populer' },
  { id: 3, name: 'Premium', description: 'Fasilitas mewah & eksklusif' }
];

export const mockSettings = {
  tour_landing: {
    hero: {
      title: 'Wonderful Toba – Wisata Sumatera Utara',
      subtitle: 'Temukan keindahan Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang bersama Wonderful Toba. Paket wisata terpercaya di Sumatera Utara.'
    },
    slider: [
      {
        id: 1,
        region: 'Samosir',
        title: 'Pesona Negeri Indah Kepingan Surga',
        description: 'Jelajahi budaya Batak yang kaya dan pemandangan Danau Toba yang megah langsung dari Pulau Samosir.',
        duration: '3D2N',
        price: 2500000,
        image: 'https://images.unsplash.com/photo-1615460547219-7419e6231a41?auto=format&fit=crop&q=80&w=2000',
        cardImage: 'https://images.unsplash.com/photo-1615460547219-7419e6231a41?auto=format&fit=crop&q=80&w=800'
      },
      {
        id: 2,
        region: 'Bukit Lawang',
        title: 'Petualangan Rimba Leuser',
        description: 'Temui Orangutan Sumatera di habitat aslinya dan nikmati sensasi river tubing di sungai Bahorok.',
        duration: '2D1N',
        price: 3200000,
        image: 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&q=80&w=2000',
        cardImage: 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&q=80&w=800'
      },
      {
        id: 3,
        region: 'Berastagi',
        title: 'Udara Sejuk Pegunungan Karo',
        description: 'Nikmati panorama Gunung Sibayak dan Sinabung, serta keramaies pasar buah legendaris Berastagi.',
        duration: 'Full Day',
        price: 1500000,
        image: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=2000',
        cardImage: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=800'
      }
    ],
    whyUs: {
      title: 'Pengalaman Wisata Terbaik di Sumut',
      items: [
        { icon: 'Shield', title: 'Aman & Terpercaya', desc: 'Prioritas kami adalah keamanan Anda dengan dukungan asuransi perjalanan penuh.' },
        { icon: 'Clock', title: 'Dukungan 24/7', desc: 'Kami siap menemani perjalanan Anda kapanpun Anda butuhkan bantuan.' },
        { icon: 'Globe', title: 'Destinasi Eksklusif', desc: 'Akses ke tempat-tempat tersembunyi yang jarang didatangi wisatawan umum.' }
      ]
    },
    whyUsImages: [
      'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=1200',
      'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=1200',
      'https://images.unsplash.com/photo-1555400038-63f5ba517a47?auto=format&fit=crop&q=80&w=1200'
    ],
    testimonials: [
      { name: 'Andi Pratama', role: 'Solo Traveler', text: 'Pelayanan luar biasa! Guide sangat berpengetahuan dan membantu saya menemukan spot foto terbaik di Danau Toba.', rating: 5, avatar: 'https://i.pravatar.cc/150?u=andi' },
      { name: 'Siska Amelia', role: 'Family Trip', text: 'Anak-anak sangat senang melihat Orangutan di Bukit Lawang. Itinerary disusun dengan sangat nyaman untuk keluarga.', rating: 5, avatar: 'https://i.pravatar.cc/150?u=siska' }
    ],
    cta: {
      title: 'Siap Menjelajahi Keindahan Sumut?',
      subtitle: 'Pilih paket yang sesuai dengan keinginan Anda dan ciptakan momen tak terlupakan.',
      backgroundImage: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000',
      buttonText: 'Lihat Semua Paket',
      buttonLink: '/tour/packages'
    }
  },
  landing_page: {
    outbound: {
      backgroundImage: 'https://images.unsplash.com/photo-1522071823991-b580970ad00d?auto=format&fit=crop&q=80&w=2000',
      title: 'Corporate\nOutbound.',
      subtitle: 'Solusi team building & gathering profesional untuk instansi Anda. Tersedia di puluhan hotel premium Sumut.',
      ctaText: 'Jelajahi Outbound',
    },
    tour: {
      backgroundImage: 'https://images.unsplash.com/photo-1615460547219-7419e6231a41?auto=format&fit=crop&q=80&w=2000',
      title: 'Tour &\nTravel.',
      subtitle: 'Eksplorasi keindahan Danau Toba, Berastagi, dan alam liar Bukit Lawang dengan paket liburan eksklusif kami.',
      ctaText: 'Jelajahi Wisata',
    },
    brand: { name: 'Wonderful Toba', tagline: 'Sumatera Utara' },
  },
  outbound_landing: {
    about: {
      title: 'Apa itu Outbound?',
      description: 'Metode pembelajaran berbasis pengalaman di alam terbuka yang dirancang untuk membangun karakter, kepemimpinan, dan kerjasama tim.',
      vision: 'Menjadi provider outbound terpercaya di Sumatera Utara.',
      mission: 'Memberikan layanan outbound berkualitas tinggi.',
      statsLabel: 'Tahun',
      statsValue: '12+',
    },
    heroImages: [
      'https://images.unsplash.com/photo-1522071823991-b580970ad00d?auto=format&fit=crop&q=80&w=2000',
      'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=2000',
    ]
  },
  general: {
    site_name: 'Wonderful Toba',
    contact_email: 'info@wonderfultoba.com',
    phone: '+62 812 3456 7890',
  }
};

export const mockStats = {
  packages: 12,
  happyClients: 1540,
};
