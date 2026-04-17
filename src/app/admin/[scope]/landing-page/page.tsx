"use client";

import AdminOutboundLanding from '@/pages/AdminOutboundLanding';
import AdminTourLanding from '@/pages/AdminTourLanding';
import { useParams } from 'next/navigation';

export default function Page() {
  const params = useParams();
  const rawScope = params?.scope;
  const scope = Array.isArray(rawScope) ? rawScope[0] : rawScope ?? '';

  if (scope === 'outbound') {
    return <AdminOutboundLanding />;
  }

  return <AdminTourLanding />;
}
