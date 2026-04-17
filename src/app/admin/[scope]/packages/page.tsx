import AdminPackages from '@/pages/AdminPackages';

export default async function Page({ params }: { params: Promise<{ scope: string }> }) {
  const { scope } = await params;
  const category = scope === 'outbound' ? 'outbound' : 'tour';
  return <AdminPackages category={category} />;
}