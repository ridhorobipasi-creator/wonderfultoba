export const mockTours = [
  {
    id: 1,
    slug: 'danau-toba-eksklusif',
    name: 'Danau Toba Eksklusif',
    category: 'Relaksasi',
    price: 4500000,
    images: ['/storage/2026/04/lake-toba-premium.png', '/storage/2026/04/simalem-resort.png'],
    image: '/storage/2026/04/lake-toba-premium.png',
    description: 'Nikmati kemewahan mengitari Danau Toba dengan kapal pesiar pribadi dan menginap di resort terbaik dengan pemandangan langsung ke danau.',
    shortDescription: 'Wisata premium di danau vulkanik terbesar dunia.',
    duration: '3 Hari 2 Malam',
    rating: 4.9,
    locationTag: 'Danau Toba',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Kedatangan & Transfer ke Samosir', description: 'Penjemputan di Bandara Kualanamu, perjalanan menuju Parapat, dan menyeberang ke Pulau Samosir.' },
      { day: 2, title: 'Eksplorasi Budaya Samosir', description: 'Mengunjungi Desa Tomok, Ambarita, dan menikmati pertunjukan boneka Sigale-gale.' },
      { day: 3, title: 'Relaksasi & Kepulangan', description: 'Menikmati fasilitas resort, belanja oleh-oleh, dan transfer kembali ke bandara.' }
    ],
    includes: ['Hotel Bintang 5', 'Transportasi Private', 'Makan Sesuai Program', 'Tiket Masuk Wisata', 'Pemandu Profesional'],
    excludes: ['Tiket Pesawat', 'Pengeluaran Pribadi', 'Tips Guide/Driver'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 2,
    slug: 'safari-bukit-lawang',
    name: 'Safari Bukit Lawang',
    category: 'Petualangan',
    price: 3500000,
    images: ['/storage/2026/04/bukit-lawang-jungle.png'],
    image: '/storage/2026/04/bukit-lawang-jungle.png',
    description: 'Jelajahi Taman Nasional Gunung Leuser dan temui Orangutan Sumatera di habitat aslinya. Petualangan yang tak terlupakan di jantung rimba.',
    shortDescription: 'Trekking hutan hujan tropis & bertemu Orangutan.',
    duration: '2 Hari 1 Malam',
    rating: 4.8,
    locationTag: 'Bukit Lawang',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Trekking Orangutan', description: 'Memasuki hutan Leuser untuk mencari Orangutan dan satwa liar lainnya, makan siang di dalam hutan.' },
      { day: 2, title: 'River Tubing Bahorok', description: 'Kembali ke penginapan dengan menyusuri sungai Bahorok menggunakan ban (tubing) yang seru.' }
    ],
    includes: ['Izin Masuk Taman Nasional', 'Pemandu Lokal Berlisensi', 'Makan Siang di Hutan', 'Alat Tubing'],
    excludes: ['Transportasi ke Bukit Lawang', 'Makan Malam', 'Asuransi'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 3,
    slug: 'berastagi-highland',
    name: 'Berastagi Highland Tour',
    category: 'Budaya',
    price: 1500000,
    images: ['/storage/2026/04/berastagi-highland.png', '/storage/2026/04/sibayak-trekking.png'],
    image: '/storage/2026/04/berastagi-highland.png',
    description: 'Kunjungi pasar buah Berastagi, Pagoda Taman Alam Lumbini, dan nikmati udara sejuk pegunungan Tanah Karo.',
    shortDescription: 'Menikmati udara sejuk & pemandangan gunung api.',
    duration: 'Full Day Tour',
    rating: 4.7,
    locationTag: 'Berastagi',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Wisata Karo & Berastagi', description: 'Mengunjungi Air Terjun Sipiso-piso, Desa Lingga, Bukit Gundaling, dan Pasar Buah.' }
    ],
    includes: ['Mobil Private & Driver', 'BBM & Parkir', 'Tiket Masuk Wisata'],
    excludes: ['Makan Siang', 'Belanja Pribadi'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 4,
    slug: 'tangkahan-hidden-paradise',
    name: 'Tangkahan Hidden Paradise',
    category: 'Ekowisata',
    price: 2800000,
    images: ['/storage/2026/04/tangkahan-paradise.png'],
    image: '/storage/2026/04/tangkahan-paradise.png',
    description: 'Mandi bersama gajah di sungai yang jernih dan jelajahi hutan Tangkahan yang asri. Destinasi tenang jauh dari keramaian.',
    shortDescription: 'Interaksi gajah & alam liar Tangkahan.',
    duration: '2 Hari 1 Malam',
    rating: 4.9,
    locationTag: 'Tangkahan',
    is_published: true,
    itinerary: [
      { day: 1, title: ' Elephant Wash & Jungle Walk', description: 'Aktivitas memandikan gajah di sungai dan trekking ringan menyusuri pinggiran hutan.' },
      { day: 2, title: 'Hot Springs & Waterfall', description: 'Mengunjungi sumber air panas alami dan air terjun tersembunyi di sekitar Tangkahan.' }
    ],
    includes: ['Aktivitas Memandikan Gajah', 'Guide Lokal', 'Penginapan Eco-Lodge'],
    excludes: ['Transportasi PP Medan-Tangkahan', 'Tips'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 5,
    slug: 'medan-heritage-tour',
    name: 'Medan Heritage & Foodie',
    category: 'Kuliner',
    price: 950000,
    images: ['/storage/2026/04/medan-heritage.png'],
    image: '/storage/2026/04/medan-heritage.png',
    description: 'Menelusuri sejarah Kota Medan di Istana Maimun dan Rumah Tjong A Fie, serta mencicipi kuliner legendaris seperti Durian Ucok.',
    shortDescription: 'Eksplorasi sejarah & rasa di Kota Medan.',
    duration: 'Full Day',
    rating: 4.6,
    locationTag: 'Medan',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Medan City Tour', description: 'Mengunjungi Masjid Raya, Istana Maimun, Kantor Pos Besar, dan makan siang Soto Medan.' }
    ],
    includes: ['Transportasi & Driver', 'Tiket Masuk Museum', 'Air Mineral'],
    excludes: ['Makan Siang & Jajan', 'Oleh-oleh'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 6,
    slug: 'simalem-resort-luxury',
    name: 'Simalem Resort Luxury',
    category: 'Relaksasi',
    price: 5200000,
    images: ['/storage/2026/04/simalem-resort.png'],
    image: '/storage/2026/04/simalem-resort.png',
    description: 'Menginap di resort terbaik di atas punck bukit dengan pandangan 360 derajat ke Danau Toba. Dilengkapi fasilitas agrowisata.',
    shortDescription: 'Pengalaman menginap mewah di atas awan.',
    duration: '3 Hari 2 Malam',
    rating: 5.0,
    locationTag: 'Merek',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Check-in & Sunset View', description: 'Perjalanan menuju Merek dan check-in di Taman Simalem Resort.' },
      { day: 2, title: 'Agrotourism & Pearl of Lake Toba', description: 'Mengunjungi kebun organik, trekking ke air terjun kembar, dan melihat view One Tree Hill.' }
    ],
    includes: ['Akomodasi Simalem Resort', 'Makan Pagi & Malam', 'Fasilitas Agrowisata'],
    excludes: ['Makan Siang', 'Transportasi Kedatangan'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 7,
    slug: 'sibayak-volcano-adventure',
    name: 'Sibayak Volcano Adventure',
    category: 'Petualangan',
    price: 1250000,
    images: ['/storage/2026/04/sibayak-trekking.png'],
    image: '/storage/2026/04/sibayak-trekking.png',
    description: 'Trekking pagi buta menuju puncak Gunung Sibayak untuk melihat sunrise dan kawah belerang yang aktif.',
    shortDescription: 'Pendakian gunung api aktif & sunrise.',
    duration: '2 Hari 1 Malam',
    rating: 4.8,
    locationTag: 'Berastagi',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Sunrise Trekking', description: 'Mulai mendaki jam 4 pagi, menikmati fajar di puncak, dan kembali untuk berendam di kolam air panas.' }
    ],
    includes: ['Guide Pendakian', 'Makan Pagi', 'Pemandian Air Panas Sidebuk-debuk'],
    excludes: ['Peralatan Camping (jika bermalam)', 'Tips'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 8,
    slug: 'medan-city-night-tour',
    name: 'Medan City Night Tour',
    category: 'Kuliner',
    price: 650000,
    images: ['/storage/2026/04/medan-night.png'],
    image: '/storage/2026/04/medan-night.png',
    description: 'Nikmati keindahan gemerlap lampu malam Kota Medan sambil mencicipi kuliner malam legendaris.',
    shortDescription: 'Gemerlap malam & kuliner malam Medan.',
    duration: '6 Jam (Malam)',
    rating: 4.7,
    locationTag: 'Medan',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Dinner & Heritage Walk', description: 'Makan malam di Merdeka Walk, mengunjungi Kesawan Square, dan hunting foto di Bangunan Tua.' }
    ],
    includes: ['Makan Malam', 'Transportasi & Driver', 'Biaya Parkir'],
    excludes: ['Belanja Pribadi', 'Tips'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 9,
    slug: 'berastagi-fruit-market-exp',
    name: 'Berastagi Market Experience',
    category: 'Budaya',
    price: 850000,
    images: ['/storage/2026/04/berastagi-market.png'],
    image: '/storage/2026/04/berastagi-market.png',
    description: 'Rasakan pengalaman berbelanja buah segar langsung dari petani di pasar buah Berastagi yang ikonik.',
    shortDescription: 'Belanja buah segar & interaksi lokal.',
    duration: 'Full Day',
    rating: 4.8,
    locationTag: 'Berastagi',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Market & Farm Visit', description: 'Eksplorasi pasar buah, mengunjungi kebun stroberi, dan mampir ke Pagoda Lumbini.' }
    ],
    includes: ['Transportasi', 'Tiket Masuk', 'Welcome Drink'],
    excludes: ['Makan Siang', 'Belanja Buah'],
    createdAt: new Date().toISOString(),
  },
  {
    id: 10,
    slug: 'lake-toba-spiritual-journey',
    name: 'Lake Toba Spiritual Journey',
    category: 'Relaksasi',
    price: 3200000,
    images: ['/storage/2026/04/lake-toba-spiritual.png'],
    image: '/storage/2026/04/lake-toba-spiritual.png',
    description: 'Perjalanan menenangkan jiwa untuk mengenal lebih dekat adat istiadat dan kepercayaan spiritual Batak Toba.',
    shortDescription: 'Ketenangan jiwa & budaya leluhur.',
    duration: '3 Hari 2 Malam',
    rating: 4.9,
    locationTag: 'Samosir',
    is_published: true,
    itinerary: [
      { day: 1, title: 'Ritual Selamat Datang', description: 'Menyeberang ke Pulau Samosir dan mengikuti ritual penyambutan adat.' },
      { day: 2, title: 'Tracing Ancestors', description: 'Mengunjungi situs makam raja-raja dan tempat bersejarah di Samosir.' },
      { day: 3, title: 'Meditation by the Lake', description: 'Sesi meditasi pagi hari di tepi danau sebelum kembali.' }
    ],
    includes: ['Ritual Adat', 'Akomodasi Tepi Danau', 'Makan Organik', 'Guide Spesialis Budaya'],
    excludes: ['Transportasi ke Parapat', 'Tips'],
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
        image: '/storage/2026/04/lake-toba-premium.png',
        cardImage: '/storage/2026/04/lake-toba-premium.png'
      },
      {
        id: 2,
        region: 'Bukit Lawang',
        title: 'Petualangan Rimba Leuser',
        description: 'Temui Orangutan Sumatera di habitat aslinya dan nikmati sensasi river tubing di sungai Bahorok.',
        duration: '2D1N',
        price: 3200000,
        image: '/storage/2026/04/bukit-lawang-jungle.png',
        cardImage: '/storage/2026/04/bukit-lawang-jungle.png'
      },
      {
        id: 3,
        region: 'Berastagi',
        title: 'Udara Sejuk Pegunungan Karo',
        description: 'Nikmati panorama Gunung Sibayak dan Sinabung, serta keramaian pasar buah legendaris Berastagi.',
        duration: 'Full Day',
        price: 1500000,
        image: '/storage/2026/04/berastagi-highland.png',
        cardImage: '/storage/2026/04/berastagi-highland.png'
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
      '/storage/2026/04/sumatra-panorama.png',
      '/storage/2026/04/lake-toba-premium.png',
      '/storage/2026/04/berastagi-highland.png'
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
