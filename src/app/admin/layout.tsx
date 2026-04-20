import AdminSidebar from '@/components/admin/AdminSidebar';

export default function Layout({ children }: { children: React.ReactNode }) {
  return (
    <div className="bg-slate-50 flex min-h-screen">
      <AdminSidebar />
      <main className="flex-1 w-full lg:w-auto">
        <div className="p-4 lg:p-6 xl:p-10 pt-20 lg:pt-6">
          {children}
        </div>
      </main>
    </div>
  );
}
