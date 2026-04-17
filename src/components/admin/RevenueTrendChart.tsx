"use client";

import {
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  AreaChart,
  Area,
} from 'recharts';

type RevenuePoint = {
  name: string;
  value: number;
};

export default function RevenueTrendChart({ data }: { data: RevenuePoint[] }) {
  return (
    <ResponsiveContainer width="100%" height="100%">
      <AreaChart data={data}>
        <defs>
          <linearGradient id="colorValue" x1="0" y1="0" x2="0" y2="1">
            <stop offset="5%" stopColor="#005696" stopOpacity={0.15} />
            <stop offset="95%" stopColor="#005696" stopOpacity={0} />
          </linearGradient>
        </defs>
        <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
        <XAxis
          dataKey="name"
          axisLine={false}
          tickLine={false}
          tick={{ fill: '#94a3b8', fontSize: 11, fontWeight: 700 }}
          dy={15}
        />
        <YAxis
          axisLine={false}
          tickLine={false}
          tick={{ fill: '#94a3b8', fontSize: 11, fontWeight: 700 }}
          dx={-10}
        />
        <Tooltip
          contentStyle={{
            backgroundColor: '#fff',
            borderRadius: '24px',
            border: 'none',
            boxShadow: '0 25px 50px -12px rgb(0 0 0 / 0.15)',
            padding: '20px',
          }}
          itemStyle={{ fontWeight: '900', color: '#005696', fontSize: '14px' }}
          labelStyle={{
            fontWeight: '900',
            color: '#0f172a',
            marginBottom: '8px',
            fontSize: '14px',
            letterSpacing: '0.025em',
          }}
        />
        <Area
          type="monotone"
          dataKey="value"
          stroke="#005696"
          strokeWidth={5}
          fillOpacity={1}
          fill="url(#colorValue)"
          animationDuration={2000}
        />
      </AreaChart>
    </ResponsiveContainer>
  );
}
