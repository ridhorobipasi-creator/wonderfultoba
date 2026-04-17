import AdminBlogs from '@/pages/AdminBlogs';

export default async function Page({ params }: { params: Promise<{ scope: string }> }) {
  const { scope } = await params;
  const category = scope === 'outbound' ? 'outbound' : 'tour';
  return <AdminBlogs category={category} />;
}