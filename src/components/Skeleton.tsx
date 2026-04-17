"use client";

// Reusable skeleton shimmer components
export function SkeletonBox({ className = '' }: { className?: string }) {
  return (
    <div className={`relative overflow-hidden bg-slate-200 rounded-2xl ${className}`}>
      <div className="absolute inset-0 -translate-x-full animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/60 to-transparent" />
    </div>
  );
}

export function BlogCardSkeleton() {
  return (
    <div className="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm">
      <SkeletonBox className="h-52 rounded-none" />
      <div className="p-6 space-y-3">
        <SkeletonBox className="h-3 w-24 rounded-full" />
        <SkeletonBox className="h-5 w-full rounded-xl" />
        <SkeletonBox className="h-5 w-3/4 rounded-xl" />
        <SkeletonBox className="h-4 w-full rounded-xl" />
        <SkeletonBox className="h-4 w-2/3 rounded-xl" />
        <div className="flex items-center gap-3 pt-2">
          <SkeletonBox className="w-8 h-8 rounded-full" />
          <SkeletonBox className="h-3 w-32 rounded-full" />
        </div>
      </div>
    </div>
  );
}

export function PackageCardSkeleton() {
  return (
    <div className="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm">
      <SkeletonBox className="h-64 rounded-none" />
      <div className="p-6 space-y-3">
        <SkeletonBox className="h-3 w-20 rounded-full" />
        <SkeletonBox className="h-6 w-full rounded-xl" />
        <SkeletonBox className="h-4 w-full rounded-xl" />
        <SkeletonBox className="h-4 w-4/5 rounded-xl" />
        <div className="flex justify-between items-center pt-2">
          <SkeletonBox className="h-7 w-32 rounded-xl" />
          <SkeletonBox className="h-10 w-28 rounded-2xl" />
        </div>
      </div>
    </div>
  );
}

export function BlogDetailSkeleton() {
  return (
    <div className="bg-slate-50 min-h-screen pb-24 animate-pulse">
      <SkeletonBox className="h-[50vh] rounded-none" />
      <div className="max-w-4xl mx-auto px-4 mt-10 space-y-6">
        <SkeletonBox className="h-5 w-32 rounded-full" />
        <div className="bg-white rounded-[2rem] p-8 md:p-12 space-y-5">
          <SkeletonBox className="h-4 w-48 rounded-full" />
          <SkeletonBox className="h-6 w-full rounded-xl" />
          <SkeletonBox className="h-6 w-4/5 rounded-xl" />
          <SkeletonBox className="h-6 w-3/5 rounded-xl" />
          <div className="pt-4 space-y-4">
            {[...Array(5)].map((_, i) => (
              <SkeletonBox key={i} className={`h-4 rounded-xl ${i % 3 === 2 ? 'w-3/4' : 'w-full'}`} />
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
