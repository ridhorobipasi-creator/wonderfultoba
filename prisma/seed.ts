import { PrismaClient } from '@prisma/client'
import bcrypt from 'bcryptjs'

const prisma = new PrismaClient()

async function main() {
  const password = await bcrypt.hash('password123', 10)

  // Create Admin Umum
  await prisma.user.upsert({
    where: { email: 'admin@wonderfultoba.com' },
    update: {},
    create: {
      email: 'admin@wonderfultoba.com',
      name: 'Admin Umum',
      password: password,
      role: 'admin_umum',
    },
  })

  // Create Admin Tour
  await prisma.user.upsert({
    where: { email: 'tour@wonderfultoba.com' },
    update: {},
    create: {
      email: 'tour@wonderfultoba.com',
      name: 'Admin Tour',
      password: password,
      role: 'admin_tour',
    },
  })

  // Create Admin Outbound
  await prisma.user.upsert({
    where: { email: 'outbound@wonderfultoba.com' },
    update: {},
    create: {
      email: 'outbound@wonderfultoba.com',
      name: 'Admin Outbound',
      password: password,
      role: 'admin_outbound',
    },
  })

  // Create Cities
  const medan = await prisma.city.upsert({
    where: { slug: 'medan' },
    update: {},
    create: {
      name: 'Medan',
      slug: 'medan',
    },
  })

  const danauToba = await prisma.city.upsert({
    where: { slug: 'danau-toba' },
    update: {},
    create: {
      name: 'Danau Toba',
      slug: 'danau-toba',
    },
  })

  const berastagi = await prisma.city.upsert({
    where: { slug: 'berastagi' },
    update: {},
    create: {
      name: 'Berastagi',
      slug: 'berastagi',
    },
  })

  // Create Tour Packages
  await prisma.package.upsert({
    where: { slug: 'paket-danau-toba-3d2n' },
    update: {},
    create: {
      name: 'Paket Danau Toba 3D2N',
      slug: 'paket-danau-toba-3d2n',
      shortDescription: 'Jelajahi keindahan Danau Toba selama 3 hari 2 malam dengan fasilitas lengkap',
      description: 'Nikmati pengalaman tak terlupakan menjelajahi Danau Toba, danau vulkanik terbesar di Asia Tenggara. Paket ini mencakup kunjungan ke Pulau Samosir, desa tradisional Batak, dan berbagai spot foto instagramable.',
      locationTag: 'Danau Toba, Samosir',
      price: 1500000,
      childPrice: 1200000,
      priceDisplay: 'per orang (min. 4 pax)',
      duration: '3 Hari 2 Malam',
      images: [
        '/assets/images/2023/10/001-1.jpg',
        '/assets/images/2023/10/002-1.jpg',
        '/assets/images/2023/10/003-1.jpg',
      ],
      includes: [
        'Hotel bintang 3 (2 malam)',
        'Transportasi AC selama tour',
        'Makan 6x (2 breakfast, 2 lunch, 2 dinner)',
        'Tiket masuk objek wisata',
        'Tour guide berpengalaman',
        'Air mineral selama perjalanan',
      ],
      excludes: [
        'Tiket pesawat/kereta',
        'Pengeluaran pribadi',
        'Tips guide & driver',
        'Asuransi perjalanan',
      ],
      itineraryText: 'Hari 1: Penjemputan - Sipiso-piso - Tongging - Check-in Hotel\nHari 2: Pulau Samosir - Desa Tomok - Huta Bolon - Tuktuk\nHari 3: Pasar Tomok - Oleh-oleh - Drop off',
      status: 'active',
      isFeatured: true,
      isOutbound: false,
      sortOrder: 1,
      metaTitle: 'Paket Wisata Danau Toba 3D2N - Wonderful Toba',
      metaDescription: 'Paket tour Danau Toba 3 hari 2 malam dengan harga terjangkau. Termasuk hotel, transportasi, dan tour guide.',
      cityId: danauToba.id,
    },
  })

  await prisma.package.upsert({
    where: { slug: 'paket-berastagi-2d1n' },
    update: {},
    create: {
      name: 'Paket Berastagi 2D1N',
      slug: 'paket-berastagi-2d1n',
      shortDescription: 'Wisata sejuk ke Berastagi dengan kunjungan ke Gundaling dan Sipiso-piso',
      description: 'Rasakan kesejukan udara pegunungan Berastagi. Kunjungi berbagai objek wisata menarik seperti Gundaling Hill, Air Terjun Sipiso-piso, dan pasar buah Berastagi yang terkenal.',
      locationTag: 'Berastagi, Karo',
      price: 850000,
      childPrice: 700000,
      priceDisplay: 'per orang (min. 4 pax)',
      duration: '2 Hari 1 Malam',
      images: [
        '/assets/images/2023/10/004.jpg',
        '/assets/images/2023/10/005.jpg',
        '/assets/images/2023/10/006.jpg',
      ],
      includes: [
        'Hotel bintang 3 (1 malam)',
        'Transportasi AC',
        'Makan 3x',
        'Tiket masuk wisata',
        'Tour guide',
      ],
      excludes: [
        'Tiket transportasi ke Medan',
        'Pengeluaran pribadi',
        'Tips',
      ],
      itineraryText: 'Hari 1: Medan - Gundaling - Sipiso-piso - Check-in\nHari 2: Pasar Buah - Taman Alam Lumbini - Kembali ke Medan',
      status: 'active',
      isFeatured: true,
      isOutbound: false,
      sortOrder: 2,
      metaTitle: 'Paket Wisata Berastagi 2D1N - Wonderful Toba',
      metaDescription: 'Tour Berastagi 2 hari 1 malam. Kunjungi Gundaling, Sipiso-piso, dan pasar buah terkenal.',
      cityId: berastagi.id,
    },
  })

  await prisma.package.upsert({
    where: { slug: 'paket-medan-city-tour' },
    update: {},
    create: {
      name: 'Paket Medan City Tour',
      slug: 'paket-medan-city-tour',
      shortDescription: 'Jelajahi kota Medan dalam sehari dengan mengunjungi landmark bersejarah',
      description: 'Tour keliling kota Medan mengunjungi Istana Maimun, Masjid Raya, Tjong A Fie Mansion, dan kuliner khas Medan. Cocok untuk wisatawan yang ingin mengenal Medan lebih dekat.',
      locationTag: 'Kota Medan',
      price: 350000,
      childPrice: 300000,
      priceDisplay: 'per orang (min. 2 pax)',
      duration: '1 Hari (8 jam)',
      images: [
        '/assets/images/2023/10/008.jpg',
        '/assets/images/2023/10/009-1.jpg',
        '/assets/images/2023/10/0010.jpg',
      ],
      includes: [
        'Mobil + driver',
        'BBM',
        'Parkir',
        'Tour guide',
        'Air mineral',
      ],
      excludes: [
        'Makan',
        'Tiket masuk museum',
        'Belanja oleh-oleh',
      ],
      itineraryText: 'Istana Maimun - Masjid Raya - Tjong A Fie - Pasar Ikan Lama - Kuliner Merdeka Walk',
      status: 'active',
      isFeatured: false,
      isOutbound: false,
      sortOrder: 3,
      metaTitle: 'Medan City Tour - Wonderful Toba',
      metaDescription: 'City tour Medan sehari penuh. Kunjungi Istana Maimun, Masjid Raya, dan kuliner khas.',
      cityId: medan.id,
    },
  })

  // Create Outbound Packages
  await prisma.package.upsert({
    where: { slug: 'outbound-team-building-basic' },
    update: {},
    create: {
      name: 'Outbound Team Building - Basic',
      slug: 'outbound-team-building-basic',
      shortDescription: 'Paket outbound hemat untuk team building perusahaan Anda',
      description: 'Program team building dengan 3-5 jenis games outbound yang dirancang untuk meningkatkan kerjasama tim. Dipandu oleh trainer berpengalaman dengan perlengkapan keselamatan lengkap.',
      locationTag: 'Area Jabodetabek & Sumut',
      price: 250000,
      childPrice: 0,
      priceDisplay: 'per pax (min. 30 pax)',
      duration: 'Setengah Hari (4-5 Jam)',
      images: [
        '/assets/images/2023/10/A11-Team-Building.jpg',
        '/assets/images/2023/10/A12-Fun-Games.jpg',
        '/assets/images/2023/10/005.jpg',
      ],
      includes: [
        '3 – 5 Jenis Games Outbound',
        'Trainer & Moderator Berpengalaman',
        'Perlengkapan Keselamatan Lengkap',
        'Sertifikat Peserta',
        'Dokumentasi Foto',
        'Air Minum & Snack Sederhana',
      ],
      excludes: [
        'Transportasi',
        'Biaya Venue',
        'Makan Siang',
      ],
      itineraryText: 'Ice Breaking - Games 1-3 - Break - Games 4-5 - Closing & Sertifikat',
      status: 'active',
      isFeatured: false,
      isOutbound: true,
      sortOrder: 1,
      metaTitle: 'Paket Outbound Team Building Basic - Wonderful Toba Outbound',
      metaDescription: 'Paket outbound hemat untuk team building perusahaan. Mulai dari Rp 250.000/pax.',
      cityId: medan.id,
    },
  })

  await prisma.package.upsert({
    where: { slug: 'outbound-team-building-standard' },
    update: {},
    create: {
      name: 'Outbound Team Building - Standard',
      slug: 'outbound-team-building-standard',
      shortDescription: 'Paket paling populer dengan full day activities dan konsumsi lengkap',
      description: 'Program outbound full day dengan 5-8 jenis games, senior trainer bersertifikasi, MC profesional, dan konsumsi lengkap. Paket terpopuler untuk corporate gathering.',
      locationTag: 'Seluruh Sumatera Utara',
      price: 450000,
      childPrice: 0,
      priceDisplay: 'per pax (min. 30 pax)',
      duration: 'Full Day (8–9 Jam)',
      images: [
        '/assets/images/2023/10/A12-Gathering.jpg',
        '/assets/images/2023/10/006.jpg',
        '/assets/images/2023/10/008.jpg',
      ],
      includes: [
        '5 – 8 Jenis Games Outbound',
        'Senior Trainer Bersertifikasi',
        'Master of Ceremony (MC) Profesional',
        'Perlengkapan Outbound Premium',
        'Dokumentasi Foto & Video',
        'Konsep Acara Custom',
        'Sertifikat & Souvenir Peserta',
        'Makan Siang & Coffee Break 2x',
      ],
      excludes: [
        'Transportasi',
        'Biaya Venue / Akomodasi',
      ],
      itineraryText: 'Registrasi - Opening - Ice Breaking - Games Session 1 - Coffee Break - Games Session 2 - Lunch - Games Session 3 - Closing Ceremony',
      status: 'active',
      isFeatured: true,
      isOutbound: true,
      sortOrder: 2,
      metaTitle: 'Paket Outbound Standard Full Day - Wonderful Toba Outbound',
      metaDescription: 'Paket outbound full day paling populer. Termasuk MC, konsumsi, dan dokumentasi.',
      cityId: medan.id,
    },
  })

  await prisma.package.upsert({
    where: { slug: 'outbound-team-building-premium' },
    update: {},
    create: {
      name: 'Outbound Team Building - Premium',
      slug: 'outbound-team-building-premium',
      shortDescription: 'Paket all-inclusive dengan akomodasi resort dan aktivitas eksklusif',
      description: 'Program outbound premium 2D1N di resort berbintang dengan 8-12+ aktivitas eksklusif, transportasi group, full board meals, dan sesi motivasi. Cocok untuk perusahaan yang menginginkan pengalaman terbaik.',
      locationTag: 'Resort Premium di Sumut',
      price: 1500000,
      childPrice: 0,
      priceDisplay: 'Custom (hubungi kami)',
      duration: '2 Hari 1 Malam / Custom',
      images: [
        '/assets/images/2023/10/A13a-Outbound-Kids.jpg',
        '/assets/images/2023/10/009-1.jpg',
        '/assets/images/2023/10/0010.jpg',
      ],
      includes: [
        '8 – 12+ Aktivitas Outbound Eksklusif',
        'Head Trainer + Tim Profesional Lengkap',
        'MC + Sound System + Dekorasi Event',
        'Konsep Tema Event Fully Customized',
        'Dokumentasi Profesional (Foto + Video Edit)',
        'Transportasi Group (Bus / ELF)',
        'Akomodasi Resort Berbintang',
        'Seluruh Konsumsi (Makan + Snack)',
        'Suvenir Eksklusif & Merchandise',
        'Sesi Motivasi & Leadership Talk',
      ],
      excludes: [],
      itineraryText: 'Day 1: Penjemputan - Perjalanan - Check-in - Lunch - Games Session - Dinner - Gathering Night\nDay 2: Breakfast - Morning Games - Lunch - Closing - Kembali',
      status: 'active',
      isFeatured: true,
      isOutbound: true,
      sortOrder: 3,
      metaTitle: 'Paket Outbound Premium All-Inclusive - Wonderful Toba Outbound',
      metaDescription: 'Paket outbound premium 2D1N dengan akomodasi resort dan fasilitas lengkap.',
      cityId: danauToba.id,
    },
  })

  await prisma.package.upsert({
    where: { slug: 'outbound-fun-games' },
    update: {},
    create: {
      name: 'Outbound Fun Games',
      slug: 'outbound-fun-games',
      shortDescription: 'Games seru dan menghibur untuk acara gathering perusahaan',
      description: 'Program fun games yang dirancang untuk menciptakan suasana ceria dan kebersamaan. Cocok untuk family gathering, anniversary perusahaan, atau acara santai lainnya.',
      locationTag: 'Flexible Location',
      price: 300000,
      childPrice: 250000,
      priceDisplay: 'per pax (min. 50 pax)',
      duration: '4-6 Jam',
      images: [
        '/assets/images/2023/10/A12-Fun-Games.jpg',
        '/assets/images/2023/10/004.jpg',
        '/assets/images/2023/10/006.jpg',
      ],
      includes: [
        '5-7 Jenis Fun Games',
        'Game Master Profesional',
        'Moderator & Crew',
        'Perlengkapan Games',
        'Hadiah untuk Pemenang',
        'Dokumentasi Foto',
        'Snack & Air Minum',
      ],
      excludes: [
        'Transportasi',
        'Venue',
        'Makan Berat',
      ],
      itineraryText: 'Opening - Ice Breaking - Fun Games Round 1 - Break - Fun Games Round 2 - Awarding - Closing',
      status: 'active',
      isFeatured: false,
      isOutbound: true,
      sortOrder: 4,
      metaTitle: 'Outbound Fun Games - Wonderful Toba Outbound',
      metaDescription: 'Program fun games seru untuk gathering perusahaan dan acara keluarga.',
      cityId: medan.id,
    },
  })

  await prisma.package.upsert({
    where: { slug: 'outbound-kids' },
    update: {},
    create: {
      name: 'Outbound Kids',
      slug: 'outbound-kids',
      shortDescription: 'Program outbound khusus anak-anak usia 5-15 tahun',
      description: 'Program outbound yang dirancang khusus untuk mengembangkan kepercayaan diri, keberanian, dan kreativitas anak-anak. Dengan games yang aman dan edukatif.',
      locationTag: 'Kid-Friendly Venues',
      price: 200000,
      childPrice: 200000,
      priceDisplay: 'per anak (min. 20 anak)',
      duration: '3-4 Jam',
      images: [
        '/assets/images/2023/10/A13a-Outbound-Kids.jpg',
        '/assets/images/2023/10/005.jpg',
        '/assets/images/2023/10/008.jpg',
      ],
      includes: [
        '4-6 Games Edukatif',
        'Trainer Khusus Anak',
        'Perlengkapan Safety',
        'Sertifikat Keberanian',
        'Snack & Minum',
        'Dokumentasi',
      ],
      excludes: [
        'Transportasi',
        'Pendampingan Orang Tua',
        'Makan Berat',
      ],
      itineraryText: 'Perkenalan - Warm Up - Games Edukatif - Break - Games Petualangan - Awarding',
      status: 'active',
      isFeatured: false,
      isOutbound: true,
      sortOrder: 5,
      metaTitle: 'Outbound Kids - Program Outbound Anak - Wonderful Toba',
      metaDescription: 'Program outbound khusus anak usia 5-15 tahun. Edukatif, aman, dan menyenangkan.',
      cityId: medan.id,
    },
  })

  // Create CMS Settings for Tour Landing Page
  await prisma.setting.upsert({
    where: { key: 'tour_landing' },
    update: {
      value: {
        hero: {
          title: 'Explore the Beauty of Lake Toba',
          subtitle: 'Nikmati pengalaman tak terlupakan menjelajahi keindahan Sumatera Utara bersama kami',
          image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000'
        },
        slider: [
          {
            title: 'Pesona Danau Toba & Budaya Batak',
            region: 'Tobasa, Sumatera Utara',
            description: 'Jelajahi keajaiban danau vulkanik terbesar di dunia, nikmati keindahan alam dan kekayaan budaya Batak yang memukau.',
            duration: '4 Hari 3 Malam',
            price: '3500000',
            image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Air Terjun Sipiso-piso yang Megah',
            region: 'Samosir, Sumatera Utara',
            description: 'Saksikan kemegahan Air Terjun Sipiso-piso setinggi 120m yang langsung mengalir ke Danau Toba — pemandangan paling ikonik Sumatra Utara.',
            duration: '3 Hari 2 Malam',
            price: '2800000',
            image: 'https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Berastagi & Gunung Sinabung',
            region: 'Karo, Sumatera Utara',
            description: 'Nikmati kesegaran udara pegunungan Karo, pasar buah segar, dan pemandangan Gunung Sinabung yang megah.',
            duration: '2 Hari 1 Malam',
            price: '1900000',
            image: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Tangkahan & Gajah Sumatera',
            region: 'Langkat, Sumatera Utara',
            description: 'Petualangan seru bersama gajah Sumatera di tepi sungai, mandi lumpur, dan trekking hutan tropis yang menakjubkan.',
            duration: '3 Hari 2 Malam',
            price: '2500000',
            image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Bukit Lawang & Orangutan Liar',
            region: 'Langkat, Sumatera Utara',
            description: 'Trekking di hutan Gunung Leuser, bertemu orangutan liar di habitat aslinya, dan menikmati keindahan alam yang masih terjaga.',
            duration: '4 Hari 3 Malam',
            price: '3200000',
            image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=600'
          }
        ],
        whyUs: {
          title: 'Mengapa Memilih Wonderful Toba?',
          items: [
            {
              title: 'Pemandu Berpengalaman',
              icon: 'Shield',
              desc: 'Tim guide profesional dengan sertifikasi resmi dan pengalaman lebih dari 10 tahun'
            },
            {
              title: 'Harga Terjangkau',
              icon: 'DollarSign',
              desc: 'Paket wisata dengan harga kompetitif tanpa mengurangi kualitas pelayanan'
            },
            {
              title: 'Layanan 24/7',
              icon: 'Clock',
              desc: 'Customer service siap membantu Anda kapan saja selama perjalanan'
            },
            {
              title: 'Asuransi Perjalanan',
              icon: 'Heart',
              desc: 'Setiap paket dilengkapi dengan asuransi perjalanan untuk keamanan Anda'
            }
          ]
        },
        testimonials: [
          {
            name: 'Budi Santoso',
            role: 'Traveler dari Jakarta',
            avatar: 'https://i.pravatar.cc/150?img=1',
            rating: 5,
            text: 'Pengalaman luar biasa! Guide sangat ramah dan profesional. Danau Toba benar-benar memukau!'
          },
          {
            name: 'Siti Nurhaliza',
            role: 'Wisatawan dari Malaysia',
            avatar: 'https://i.pravatar.cc/150?img=5',
            rating: 5,
            text: 'Paket tour yang sangat lengkap dan terorganisir dengan baik. Highly recommended!'
          },
          {
            name: 'Ahmad Rizki',
            role: 'Backpacker dari Bandung',
            avatar: 'https://i.pravatar.cc/150?img=3',
            rating: 4,
            text: 'Harga terjangkau dengan fasilitas yang memuaskan. Pasti akan kembali lagi!'
          }
        ],
        stats: [
          {
            icon: 'Users',
            value: '1000+',
            label: 'Wisatawan Puas'
          },
          {
            icon: 'Star',
            value: '4.9/5',
            label: 'Rating Kepuasan'
          },
          {
            icon: 'MapPin',
            value: '50+',
            label: 'Destinasi Tersedia'
          },
          {
            icon: 'Award',
            value: '10+',
            label: 'Penghargaan'
          }
        ]
      }
    },
    create: {
      key: 'tour_landing',
      value: {
        hero: {
          title: 'Explore the Beauty of Lake Toba',
          subtitle: 'Nikmati pengalaman tak terlupakan menjelajahi keindahan Sumatera Utara bersama kami',
          image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000'
        },
        slider: [
          {
            title: 'Pesona Danau Toba & Budaya Batak',
            region: 'Tobasa, Sumatera Utara',
            description: 'Jelajahi keajaiban danau vulkanik terbesar di dunia, nikmati keindahan alam dan kekayaan budaya Batak yang memukau.',
            duration: '4 Hari 3 Malam',
            price: '3500000',
            image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Air Terjun Sipiso-piso yang Megah',
            region: 'Samosir, Sumatera Utara',
            description: 'Saksikan kemegahan Air Terjun Sipiso-piso setinggi 120m yang langsung mengalir ke Danau Toba — pemandangan paling ikonik Sumatra Utara.',
            duration: '3 Hari 2 Malam',
            price: '2800000',
            image: 'https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Berastagi & Gunung Sinabung',
            region: 'Karo, Sumatera Utara',
            description: 'Nikmati kesegaran udara pegunungan Karo, pasar buah segar, dan pemandangan Gunung Sinabung yang megah.',
            duration: '2 Hari 1 Malam',
            price: '1900000',
            image: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Tangkahan & Gajah Sumatera',
            region: 'Langkat, Sumatera Utara',
            description: 'Petualangan seru bersama gajah Sumatera di tepi sungai, mandi lumpur, dan trekking hutan tropis yang menakjubkan.',
            duration: '3 Hari 2 Malam',
            price: '2500000',
            image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Bukit Lawang & Orangutan Liar',
            region: 'Langkat, Sumatera Utara',
            description: 'Trekking di hutan Gunung Leuser, bertemu orangutan liar di habitat aslinya, dan menikmati keindahan alam yang masih terjaga.',
            duration: '4 Hari 3 Malam',
            price: '3200000',
            image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=600'
          }
        ],
        whyUs: {
          title: 'Mengapa Memilih Wonderful Toba?',
          items: [
            {
              title: 'Pemandu Berpengalaman',
              icon: 'Shield',
              desc: 'Tim guide profesional dengan sertifikasi resmi dan pengalaman lebih dari 10 tahun'
            },
            {
              title: 'Harga Terjangkau',
              icon: 'DollarSign',
              desc: 'Paket wisata dengan harga kompetitif tanpa mengurangi kualitas pelayanan'
            },
            {
              title: 'Layanan 24/7',
              icon: 'Clock',
              desc: 'Customer service siap membantu Anda kapan saja selama perjalanan'
            },
            {
              title: 'Asuransi Perjalanan',
              icon: 'Heart',
              desc: 'Setiap paket dilengkapi dengan asuransi perjalanan untuk keamanan Anda'
            }
          ]
        },
        testimonials: [
          {
            name: 'Budi Santoso',
            role: 'Traveler dari Jakarta',
            avatar: 'https://i.pravatar.cc/150?img=1',
            rating: 5,
            text: 'Pengalaman luar biasa! Guide sangat ramah dan profesional. Danau Toba benar-benar memukau!'
          },
          {
            name: 'Siti Nurhaliza',
            role: 'Wisatawan dari Malaysia',
            avatar: 'https://i.pravatar.cc/150?img=5',
            rating: 5,
            text: 'Paket tour yang sangat lengkap dan terorganisir dengan baik. Highly recommended!'
          },
          {
            name: 'Ahmad Rizki',
            role: 'Backpacker dari Bandung',
            avatar: 'https://i.pravatar.cc/150?img=3',
            rating: 4,
            text: 'Harga terjangkau dengan fasilitas yang memuaskan. Pasti akan kembali lagi!'
          }
        ],
        stats: [
          {
            icon: 'Users',
            value: '1000+',
            label: 'Wisatawan Puas'
          },
          {
            icon: 'Star',
            value: '4.9/5',
            label: 'Rating Kepuasan'
          },
          {
            icon: 'MapPin',
            value: '50+',
            label: 'Destinasi Tersedia'
          },
          {
            icon: 'Award',
            value: '10+',
            label: 'Penghargaan'
          }
        ]
      }
    }
  })

  // Create CMS Settings for Outbound Landing Page
  await prisma.setting.upsert({
    where: { key: 'outbound_landing' },
    update: {
      value: {
        hero: {
          title: 'Corporate Outbound & Team Building',
          subtitle: 'Tingkatkan solidaritas dan produktivitas tim melalui program outbound profesional',
          image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=2000'
        },
        slider: [
          {
            title: 'Corporate Team Building',
            region: 'Toba & Samosir',
            description: 'Tingkatkan solidaritas dan komunikasi tim melalui permainan yang dirancang secara profesional di tepi Danau Toba.',
            duration: '2 Hari 1 Malam',
            price: '1500000',
            image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Jungle Survival Camp',
            region: 'Bukit Lawang',
            description: 'Uji batas kemampuan tim dalam kondisi ekstrim namun aman. Ekspedisi yang ditujukan untuk top level management.',
            duration: '3 Hari 2 Malam',
            price: '2100000',
            image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Fun Family Gathering',
            region: 'Berastagi',
            description: 'Permainan edukatif dan outbond ringan bagi karyawan bersama keluarga. Menciptakan momen kebersamaan tak ternilai.',
            duration: '1 Hari',
            price: '450000',
            image: 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=600'
          }
        ],
        whyUs: {
          title: 'Mengapa Memilih Kami untuk Outbound?',
          items: [
            {
              title: 'Trainer Bersertifikat',
              icon: 'Award',
              desc: 'Tim trainer profesional dengan sertifikasi internasional dan pengalaman puluhan tahun'
            },
            {
              title: 'Program Custom',
              icon: 'Settings',
              desc: 'Setiap program dirancang khusus sesuai kebutuhan dan tujuan perusahaan Anda'
            },
            {
              title: 'Safety First',
              icon: 'Shield',
              desc: 'Perlengkapan keselamatan lengkap dan standar operasional yang ketat'
            },
            {
              title: 'Full Support',
              icon: 'Headphones',
              desc: 'Tim support siap membantu dari perencanaan hingga eksekusi acara'
            }
          ]
        },
        testimonials: [
          {
            name: 'PT Maju Bersama',
            role: 'HRD Manager',
            avatar: 'https://i.pravatar.cc/150?img=10',
            rating: 5,
            text: 'Program outbound yang sangat profesional! Tim kami jadi lebih solid dan komunikasi meningkat drastis.'
          },
          {
            name: 'Bank Sejahtera',
            role: 'Corporate Secretary',
            avatar: 'https://i.pravatar.cc/150?img=12',
            rating: 5,
            text: 'Trainer sangat berpengalaman dan program disesuaikan dengan kebutuhan kami. Sangat puas!'
          },
          {
            name: 'CV Sukses Mandiri',
            role: 'Owner',
            avatar: 'https://i.pravatar.cc/150?img=8',
            rating: 4,
            text: 'Harga kompetitif dengan kualitas yang excellent. Recommended untuk perusahaan kecil hingga besar!'
          }
        ],
        stats: [
          {
            icon: 'Building',
            value: '200+',
            label: 'Perusahaan Klien'
          },
          {
            icon: 'Users',
            value: '5000+',
            label: 'Peserta Terlatih'
          },
          {
            icon: 'Trophy',
            value: '15+',
            label: 'Tahun Pengalaman'
          },
          {
            icon: 'Star',
            value: '4.9/5',
            label: 'Rating Kepuasan'
          }
        ]
      }
    },
    create: {
      key: 'outbound_landing',
      value: {
        hero: {
          title: 'Corporate Outbound & Team Building',
          subtitle: 'Tingkatkan solidaritas dan produktivitas tim melalui program outbound profesional',
          image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=2000'
        },
        slider: [
          {
            title: 'Corporate Team Building',
            region: 'Toba & Samosir',
            description: 'Tingkatkan solidaritas dan komunikasi tim melalui permainan yang dirancang secara profesional di tepi Danau Toba.',
            duration: '2 Hari 1 Malam',
            price: '1500000',
            image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Jungle Survival Camp',
            region: 'Bukit Lawang',
            description: 'Uji batas kemampuan tim dalam kondisi ekstrim namun aman. Ekspedisi yang ditujukan untuk top level management.',
            duration: '3 Hari 2 Malam',
            price: '2100000',
            image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=600'
          },
          {
            title: 'Fun Family Gathering',
            region: 'Berastagi',
            description: 'Permainan edukatif dan outbond ringan bagi karyawan bersama keluarga. Menciptakan momen kebersamaan tak ternilai.',
            duration: '1 Hari',
            price: '450000',
            image: 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=2000',
            cardImage: 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=600'
          }
        ],
        whyUs: {
          title: 'Mengapa Memilih Kami untuk Outbound?',
          items: [
            {
              title: 'Trainer Bersertifikat',
              icon: 'Award',
              desc: 'Tim trainer profesional dengan sertifikasi internasional dan pengalaman puluhan tahun'
            },
            {
              title: 'Program Custom',
              icon: 'Settings',
              desc: 'Setiap program dirancang khusus sesuai kebutuhan dan tujuan perusahaan Anda'
            },
            {
              title: 'Safety First',
              icon: 'Shield',
              desc: 'Perlengkapan keselamatan lengkap dan standar operasional yang ketat'
            },
            {
              title: 'Full Support',
              icon: 'Headphones',
              desc: 'Tim support siap membantu dari perencanaan hingga eksekusi acara'
            }
          ]
        },
        testimonials: [
          {
            name: 'PT Maju Bersama',
            role: 'HRD Manager',
            avatar: 'https://i.pravatar.cc/150?img=10',
            rating: 5,
            text: 'Program outbound yang sangat profesional! Tim kami jadi lebih solid dan komunikasi meningkat drastis.'
          },
          {
            name: 'Bank Sejahtera',
            role: 'Corporate Secretary',
            avatar: 'https://i.pravatar.cc/150?img=12',
            rating: 5,
            text: 'Trainer sangat berpengalaman dan program disesuaikan dengan kebutuhan kami. Sangat puas!'
          },
          {
            name: 'CV Sukses Mandiri',
            role: 'Owner',
            avatar: 'https://i.pravatar.cc/150?img=8',
            rating: 4,
            text: 'Harga kompetitif dengan kualitas yang excellent. Recommended untuk perusahaan kecil hingga besar!'
          }
        ],
        stats: [
          {
            icon: 'Building',
            value: '200+',
            label: 'Perusahaan Klien'
          },
          {
            icon: 'Users',
            value: '5000+',
            label: 'Peserta Terlatih'
          },
          {
            icon: 'Trophy',
            value: '15+',
            label: 'Tahun Pengalaman'
          },
          {
            icon: 'Star',
            value: '4.9/5',
            label: 'Rating Kepuasan'
          }
        ]
      }
    }
  })

  console.log('Seed completed successfully with packages and CMS data!')

  // ========================================
  // SEED DATA UNTUK CMS CONTENT MODELS
  // ========================================

  console.log('🌱 Seeding Outbound Services...')
  const services = [
    {
      title: 'TEAM BUILDING',
      shortDesc: 'Sinergi dan kolaborasi personal',
      detailDesc: 'Lewat team building, setiap personal bisa memberi respek dan menghargai perbedaan. Selanjutnya mereka bisa berbagi tujuan dan meraih ekspektasi bersama.',
      icon: 'Users',
      image: '/assets/images/2023/10/A11-Team-Building.jpg',
      orderPriority: 1,
      isActive: true
    },
    {
      title: 'FUN GAMES',
      shortDesc: 'Bermain seru dengan tujuan',
      detailDesc: 'Dipimpin oleh seorang Game Master dan beberapa orang moderator agar tercipta suasana "seru" tanpa meninggalkan tujuan dari outbound sendiri.',
      icon: 'Smile',
      image: '/assets/images/2023/10/A12-Fun-Games.jpg',
      orderPriority: 2,
      isActive: true
    },
    {
      title: 'GATHERING',
      shortDesc: 'Acara kebersamaan perusahaan',
      detailDesc: 'Ragam kegiatan seru yang disusun profesional oleh tim kreatif. Acara gathering dapat diisi dengan kegiatan rewards untuk pegawai terbaik.',
      icon: 'Sparkles',
      image: '/assets/images/2023/10/A12-Gathering.jpg',
      orderPriority: 3,
      isActive: true
    },
    {
      title: 'OUTBOUND KIDS',
      shortDesc: 'Kreativitas dan keberanian anak',
      detailDesc: 'Bertujuan mengembangkan kepercayaan diri, keberanian dan daya kreatifitas anak-anak mulai dari umur 5 hingga 15 tahun keatas.',
      icon: 'Compass',
      image: '/assets/images/2023/10/A13a-Outbound-Kids.jpg',
      orderPriority: 4,
      isActive: true
    },
    {
      title: 'ARCHERY',
      shortDesc: 'Fokus dan kesabaran emosi',
      detailDesc: 'Selain membutuhkan kekuatan fisik, panahan juga membutuhkan kedisiplinan, fokus, dan kesabaran emosi.',
      icon: 'Target',
      image: '/assets/images/2023/10/A14a-Archery.jpg',
      orderPriority: 5,
      isActive: true
    },
    {
      title: 'PAINTBALL',
      shortDesc: 'Simulasi tempur & strategi',
      detailDesc: 'Melatih kedisiplinan, sportifitas, kepemimpinan, strategi, dan kreatifitas, serta mengendurkan syaraf yang tegang karena rutinitas kerja.',
      icon: 'Swords',
      image: '/assets/images/2023/10/A15a-Paintball.jpg',
      orderPriority: 6,
      isActive: true
    }
  ]

  for (const service of services) {
    await prisma.outboundService.upsert({
      where: { id: services.indexOf(service) + 1 },
      update: service,
      create: service
    })
  }

  console.log('🌱 Seeding Outbound Videos...')
  const videos = [
    { title: 'Highlight', youtubeUrl: 'https://www.youtube.com/embed/J59m32QV0rM', orderPriority: 1, isActive: true },
    { title: 'Hutama Karya', youtubeUrl: 'https://www.youtube.com/embed/4x8p17rAqSA', orderPriority: 2, isActive: true },
    { title: 'Pelindo 1', youtubeUrl: 'https://www.youtube.com/embed/0iQn9cHj1Sk', orderPriority: 3, isActive: true },
    { title: 'Lions Club', youtubeUrl: 'https://www.youtube.com/embed/r6G6GBDQq_Q', orderPriority: 4, isActive: true },
    { title: 'BBPPTP', youtubeUrl: 'https://www.youtube.com/embed/Ilk5Lb8RLr4', orderPriority: 5, isActive: true },
    { title: 'Charoen Pokphand', youtubeUrl: 'https://www.youtube.com/embed/sZ4E2tpKMaY', orderPriority: 6, isActive: true }
  ]

  for (const video of videos) {
    await prisma.outboundVideo.upsert({
      where: { id: videos.indexOf(video) + 1 },
      update: video,
      create: video
    })
  }

  console.log('🌱 Seeding Outbound Locations...')
  const locations = [
    { name: 'Marianna Resort, Samosir', image: '/assets/images/2023/10/00-Marianna-Resort-Samosir-wonderfultoba_outbound-outbound_medan.jpg', region: 'Samosir', isFeatured: true, orderPriority: 1 },
    { name: 'The Hill Resort, Sibolangit', image: '/assets/images/2023/10/01-The-Hill-Resort-Sibolangit-wonderfultoba_outbound-outbound_medan.jpg', region: 'Sibolangit', isFeatured: false, orderPriority: 2 },
    { name: 'Grand Mutiara Hotel, Berastagi', image: '/assets/images/2023/10/02-Grand-Mutiara-Hotel-Berastagi-wonderfultoba_outbound-outbound_medan.jpg', region: 'Berastagi', isFeatured: true, orderPriority: 3 },
    { name: 'Mikie Holiday Hotel, Berastagi', image: '/assets/images/2023/10/03-Mikie-Holiday-Hotel-Resort-Berastagi-wonderfultoba_outbound-outbound_medan.jpg', region: 'Berastagi', isFeatured: false, orderPriority: 4 },
    { name: 'Sinabung Hills Hotel, Berastagi', image: '/assets/images/2023/10/04-Sinabung-Hills-Hotel-Berastagi-wonderfultoba_outbound-outbound_medan.jpg', region: 'Berastagi', isFeatured: false, orderPriority: 5 },
    { name: 'Sibayak Hotel, Berastagi', image: '/assets/images/2023/10/05-Sibayak-Hotel-Berastagi-wonderfultoba_outbound-outbound_medan.jpg', region: 'Berastagi', isFeatured: false, orderPriority: 6 },
    { name: 'Taman Simalem Resort', image: '/assets/images/2023/10/06-Taman-Simalem-Resort-Sidikalang-wonderfultoba_outbound-outbound_medan.jpg', region: 'Sidikalang', isFeatured: true, orderPriority: 7 },
    { name: 'Hotel Niagara, Parapat', image: '/assets/images/2023/10/07-Hotel-Niagara-Parapat-wonderfultoba_outbound-outbound_medan.jpg', region: 'Parapat', isFeatured: false, orderPriority: 8 },
    { name: 'KHAS Parapat Hotel', image: '/assets/images/2023/10/08-KHAS-Parapat-Hotel-Parapat-wonderfultoba_outbound-outbound_medan.jpeg', region: 'Parapat', isFeatured: false, orderPriority: 9 },
    { name: 'Labersa Toba Hotel, Balige', image: '/assets/images/2023/10/09-Labersa-Toba-Hotel-Balige-wonderfultoba_outbound-outbound_medan.jpg', region: 'Balige', isFeatured: false, orderPriority: 10 },
    { name: 'Pancur Gading Resort', image: '/assets/images/2023/10/10-Pancur-Gading-Hotel-Resort-Delitua-wonderfultoba_outbound-outbound_medan.jpg', region: 'Delitua', isFeatured: false, orderPriority: 11 },
    { name: 'Kampung Outbound, Pancur Batu', image: '/assets/images/2023/10/12-Kampung-Outbound-Pancur-Batu-wonderfultoba_outbound-outbound_medan.jpg', region: 'Pancur Batu', isFeatured: false, orderPriority: 12 },
    { name: 'Singapore Land, Batubara', image: '/assets/images/2023/10/13-Singapore-Land-Batubara-wonderfultoba_outbound-outbound_medan.jpg', region: 'Batubara', isFeatured: false, orderPriority: 13 },
    { name: 'Bukit Lawang', image: '/assets/images/2023/10/15-Bukit-Lawang-wonderfultoba_outbound-outbound_medan.jpg', region: 'Langkat', isFeatured: true, orderPriority: 14 },
    { name: 'Hillpark Sibolangit', image: '/assets/images/2023/10/17-Hillpark-Sibolangit-wonderfultoba_outbound-outbound_medan.jpg', region: 'Sibolangit', isFeatured: false, orderPriority: 15 },
    { name: 'Samosir Villa Resort', image: '/assets/images/2023/10/20-Samosir-Villa-Resort-Samosir-wonderfultoba_outbound-outbound_medan.jpg', region: 'Samosir', isFeatured: false, orderPriority: 16 }
  ]

  for (const location of locations) {
    await prisma.outboundLocation.upsert({
      where: { id: locations.indexOf(location) + 1 },
      update: location,
      create: location
    })
  }

  console.log('🌱 Seeding Clients...')
  const clients = [
    { name: 'Mandiri Taspen', logo: '/assets/images/2023/10/Mandiri-taspen-wondefultoba-outbound-medan.png', websiteUrl: null, orderPriority: 1, isActive: true },
    { name: 'Universitas Sumatera Utara', logo: '/assets/images/2023/10/USU-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 2, isActive: true },
    { name: 'Charoen Pokphand', logo: '/assets/images/2023/10/Charoen-pokphand-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 3, isActive: true },
    { name: 'Lions Club', logo: '/assets/images/2023/10/Lions-club-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 4, isActive: true },
    { name: 'Pelindo 1', logo: '/assets/images/2023/10/Pelindo-1-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 5, isActive: true },
    { name: 'TPI', logo: '/assets/images/2023/10/tpi-wonderfultoba-outbound-medan0.png', websiteUrl: null, orderPriority: 6, isActive: true },
    { name: 'Oriflame', logo: '/assets/images/2023/10/Oriflame-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 7, isActive: true },
    { name: 'Otsuka', logo: '/assets/images/2023/10/Otsuka-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 8, isActive: true },
    { name: 'Al Amjad', logo: '/assets/images/2023/10/Al-amjad-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 9, isActive: true },
    { name: 'High Scope', logo: '/assets/images/2023/10/High-scope-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 10, isActive: true },
    { name: 'Hyundai', logo: '/assets/images/2023/10/Hyundai-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 11, isActive: true },
    { name: 'PKK Dairi', logo: '/assets/images/2023/10/PKK-dairi-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 12, isActive: true },
    { name: 'BBPPTP', logo: '/assets/images/2023/10/bbpptp-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 13, isActive: true },
    { name: 'BMW', logo: '/assets/images/2023/10/bmw-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 14, isActive: true },
    { name: 'Hutama Karya', logo: '/assets/images/2023/10/hutama-karya-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 15, isActive: true },
    { name: 'MAN Insan Cendekia', logo: '/assets/images/2023/10/man-insan-cendekia-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 16, isActive: true },
    { name: 'Nirvana Memorial Park', logo: '/assets/images/2023/10/nirvana-memorial-park-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 17, isActive: true },
    { name: 'Nissan', logo: '/assets/images/2023/10/nissan-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 18, isActive: true },
    { name: 'Samudera Lautan Luas', logo: '/assets/images/2023/10/samudera-lautan-luas-wonderfultoba-outbound-medan.png', websiteUrl: null, orderPriority: 19, isActive: true }
  ]

  for (const client of clients) {
    await prisma.client.upsert({
      where: { id: clients.indexOf(client) + 1 },
      update: client,
      create: client
    })
  }

  console.log('🌱 Seeding Gallery Images...')
  const galleryImages = [
    'wonderfultoba_0922.jpg', 'wonderfultoba_0924.jpg', 'wonderfultoba_0925.jpg', 'wonderfultoba_0926.jpg',
    'wonderfultoba_0927.jpg', 'wonderfultoba_0935.jpg', 'wonderfultoba_0939.jpg', 'wonderfultoba_0940.jpg',
    'wonderfultoba_0942.jpg', 'wonderfultoba_0944.jpg', 'wonderfultoba_0946.jpg', 'wonderfultoba_0948.jpg',
    'wonderfultoba_0950.jpg', 'wonderfultoba_0954.jpg', 'wonderfultoba_0963.jpg', 'wonderfultoba_0965.jpg',
    'wonderfultoba_0970.jpg', 'wonderfultoba_0976.jpg', 'wonderfultoba_0977.jpg', 'wonderfultoba_0978.jpg',
    'wonderfultoba_0979.jpg', 'wonderfultoba_0981.jpg', 'wonderfultoba_0982.jpg', 'wonderfultoba_0984.jpg'
  ]

  for (let i = 0; i < galleryImages.length; i++) {
    await prisma.galleryImage.upsert({
      where: { id: i + 1 },
      update: {
        imageUrl: `/assets/images/2023/10/${galleryImages[i]}`,
        caption: `Event Documentation ${i + 1}`,
        category: 'outbound',
        tags: ['outbound', 'team building', 'corporate'],
        orderPriority: i + 1,
        isActive: true
      },
      create: {
        imageUrl: `/assets/images/2023/10/${galleryImages[i]}`,
        caption: `Event Documentation ${i + 1}`,
        category: 'outbound',
        tags: ['outbound', 'team building', 'corporate'],
        orderPriority: i + 1,
        isActive: true
      }
    })
  }

  console.log('🌱 Seeding Package Tiers...')
  const packageTiers = [
    {
      category: 'outbound',
      tierName: 'Basic',
      tagline: 'Paket Hemat Terbaik',
      badge: null,
      colorTheme: 'from-slate-700 to-slate-800',
      priceLabel: 'Mulai dari',
      price: 'Rp 250.000',
      unit: '/ pax (min. 30 pax)',
      duration: 'Setengah Hari (4-5 Jam)',
      capacity: '30 – 100 Pax',
      location: 'Area Jabodetabek & Sumut',
      features: [
        '3 – 5 Jenis Games Outbound',
        'Trainer & Moderator Berpengalaman',
        'Perlengkapan Keselamatan Lengkap',
        'Sertifikat Peserta',
        'Dokumentasi Foto',
        'Air Minum & Snack Sederhana'
      ],
      excludes: ['Transportasi', 'Biaya Venue', 'Makan Siang'],
      orderPriority: 1,
      isActive: true
    },
    {
      category: 'outbound',
      tierName: 'Standard',
      tagline: 'Paling Banyak Dipilih',
      badge: 'TERPOPULER',
      colorTheme: 'from-toba-green to-emerald-600',
      priceLabel: 'Mulai dari',
      price: 'Rp 450.000',
      unit: '/ pax (min. 30 pax)',
      duration: 'Full Day (8–9 Jam)',
      capacity: '30 – 300 Pax',
      location: 'Seluruh Sumatera Utara',
      features: [
        '5 – 8 Jenis Games Outbound',
        'Senior Trainer Bersertifikasi',
        'Master of Ceremony (MC) Profesional',
        'Perlengkapan Outbound Premium',
        'Dokumentasi Foto & Video',
        'Konsep Acara Custom',
        'Sertifikat & Souvenir Peserta',
        'Makan Siang & Coffee Break 2x'
      ],
      excludes: ['Transportasi', 'Biaya Venue / Akomodasi'],
      orderPriority: 2,
      isActive: true
    },
    {
      category: 'outbound',
      tierName: 'Premium',
      tagline: 'Paket All-Inclusive',
      badge: 'ALL-IN',
      colorTheme: 'from-slate-900 to-slate-800',
      priceLabel: 'Hubungi untuk',
      price: 'Custom Quote',
      unit: 'proposal eksklusif',
      duration: '2 Hari 1 Malam / Custom',
      capacity: '50 – 500+ Pax',
      location: 'Resort Premium di Sumut',
      features: [
        '8 – 12+ Aktivitas Outbound Eksklusif',
        'Head Trainer + Tim Profesional Lengkap',
        'MC + Sound System + Dekorasi Event',
        'Konsep Tema Event Fully Customized',
        'Dokumentasi Profesional (Foto + Video Edit)',
        'Transportasi Group (Bus / ELF)',
        'Akomodasi Resort Berbintang',
        'Seluruh Konsumsi (Makan + Snack)',
        'Suvenir Eksklusif & Merchandise',
        'Sesi Motivasi & Leadership Talk'
      ],
      excludes: [],
      orderPriority: 3,
      isActive: true
    }
  ]

  for (const tier of packageTiers) {
    await prisma.packageTier.upsert({
      where: { id: packageTiers.indexOf(tier) + 1 },
      update: tier,
      create: tier
    })
  }

  console.log('✅ Seeding completed successfully!')
}

main()
  .catch((e) => {
    console.error(e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
