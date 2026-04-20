"use client";

import { ReactNode } from 'react';
import { FieldError } from 'react-hook-form';

interface FormFieldProps {
  label: string;
  error?: FieldError;
  icon?: ReactNode;
  children: ReactNode;
  helperText?: string;
  required?: boolean;
  maxLength?: number;
  currentLength?: number;
}

export default function FormField({
  label,
  error,
  icon,
  children,
  helperText,
  required,
  maxLength,
  currentLength,
}: FormFieldProps) {
  const showCounter = maxLength && currentLength !== undefined;
  const isNearLimit = showCounter && currentLength > maxLength * 0.9;

  return (
    <div className="space-y-2">
      <div className="flex items-center justify-between">
        <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">
          {label}
          {required && <span className="text-rose-500 ml-1">*</span>}
        </label>
        {showCounter && (
          <span
            className={`text-xs font-bold ${
              isNearLimit ? 'text-rose-500' : 'text-slate-400'
            }`}
          >
            {currentLength}/{maxLength}
          </span>
        )}
      </div>

      <div className="relative">
        {icon && (
          <div className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none z-10">
            {icon}
          </div>
        )}
        {children}
      </div>

      {error && (
        <p className="text-rose-500 text-sm font-medium ml-1 flex items-center gap-1">
          <span className="inline-block w-1 h-1 rounded-full bg-rose-500"></span>
          {error.message}
        </p>
      )}

      {helperText && !error && (
        <p className="text-slate-400 text-xs ml-1">{helperText}</p>
      )}
    </div>
  );
}
