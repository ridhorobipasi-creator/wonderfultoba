"use client";

export function SkeletonCard({ className = '' }: { className?: string }) {
  return (
    <div className={`bg-white rounded-[2rem] overflow-hidden border border-slate-100 animate-pulse ${className}`}>
      <div className="h-56 bg-slate-100" />
      <div className="p-6 space-y-3">
        <div className="h-3 bg-slate-100 rounded-full w-1/3" />
        <div className="h-5 bg-slate-100 rounded-full w-3/4" />
        <div className="h-4 bg-slate-100 rounded-full w-full" />
        <div className="h-4 bg-slate-100 rounded-full w-2/3" />
        <div className="flex justify-between items-center pt-4">
          <div className="h-6 bg-slate-100 rounded-full w-1/3" />
          <div className="w-12 h-12 bg-slate-100 rounded-2xl" />
        </div>
      </div>
    </div>
  );
}

export function SkeletonGrid({ count = 6 }: { count?: number }) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {Array.from({ length: count }).map((_, i) => (
        <SkeletonCard key={i} />
      ))}
    </div>
  );
}
