import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function main() {
  console.log('🔧 Memperbaiki format price di slider dari string ke number...')
  
  // Get current tour_landing settings
  const setting = await prisma.setting.findUnique({
    where: { key: 'tour_landing' }
  })
  
  if (!setting) {
    console.log('❌ Setting tour_landing tidak ditemukan')
    return
  }
  
  // Value bisa berupa object atau string JSON
  const data = typeof setting.value === 'string' 
    ? JSON.parse(setting.value) 
    : setting.value as any
  
  // Convert price from string to number
  if (data.slider && Array.isArray(data.slider)) {
    data.slider = data.slider.map((slide: any) => ({
      ...slide,
      price: typeof slide.price === 'string' ? parseInt(slide.price) : slide.price
    }))
    
    console.log(`✅ Mengupdate ${data.slider.length} slider items...`)
    
    // Update database
    await prisma.setting.update({
      where: { key: 'tour_landing' },
      data: {
        value: JSON.stringify(data) as any
      }
    })
    
    console.log('✅ Berhasil! Price sekarang dalam format number.')
    console.log('\nSlider items:')
    data.slider.forEach((slide: any, i: number) => {
      console.log(`  ${i + 1}. ${slide.title} - Rp ${slide.price.toLocaleString('id-ID')}`)
    })
  } else {
    console.log('❌ Slider data tidak ditemukan atau format tidak valid')
  }
}

main()
  .catch((e) => {
    console.error('❌ Error:', e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
