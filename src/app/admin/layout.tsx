import AdminSidebar from '@/components/admin/AdminSidebar';

export default function Layout({ children }: { children: React.ReactNode }) {
  return (
    <div className="bg-slate-50 flex min-h-screen">
      <AdminSidebar />
      <main className="flex-1">
        {children}
      </main>
    </div>
  );
}
