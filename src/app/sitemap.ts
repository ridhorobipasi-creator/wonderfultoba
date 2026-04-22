import { MetadataRoute } from 'next'
import { prisma } from '@/lib/prisma'

export const dynamic = 'force-dynamic'

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const baseUrl = process.env.NEXT_PUBLIC_BASE_URL || 'https://wonderfultoba.com'

  // Get dynamic slugs from DB
  const packages = await prisma.package.findMany({ select: { slug: true, updatedAt: true } })
  const blogs = await prisma.blog.findMany({ select: { slug: true, updatedAt: true } })

  const packageUrls = packages.map((pkg: any) => ({
    url: `${baseUrl}/packages/${pkg.slug}`,
    lastModified: pkg.updatedAt,
  }))

  const blogUrls = blogs.map((blog: any) => ({
    url: `${baseUrl}/blog/${blog.slug}`,
    lastModified: blog.updatedAt,
  }))

  return [
    {
      url: baseUrl,
      lastModified: new Date(),
      changeFrequency: 'daily',
      priority: 1,
    },
    {
      url: `${baseUrl}/packages`,
      lastModified: new Date(),
      changeFrequency: 'daily',
      priority: 0.8,
    },
    {
      url: `${baseUrl}/cars`,
      lastModified: new Date(),
      changeFrequency: 'weekly',
      priority: 0.7,
    },
    {
      url: `${baseUrl}/blog`,
      lastModified: new Date(),
      changeFrequency: 'daily',
      priority: 0.6,
    },
    ...packageUrls,
    ...blogUrls,
  ]
}
