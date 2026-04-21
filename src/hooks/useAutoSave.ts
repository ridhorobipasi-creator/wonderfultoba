"use client";

import { useEffect, useRef } from 'react';
import { toast } from 'sonner';

interface UseAutoSaveOptions {
  data: any;
  key: string;
  delay?: number;
  enabled?: boolean;
}

export function useAutoSave({ data, key, delay = 5000, enabled = true }: UseAutoSaveOptions) {
  const timerRef = useRef<NodeJS.Timeout | undefined>(undefined);
  const previousDataRef = useRef<string | undefined>(undefined);

  useEffect(() => {
    if (!enabled) return;

    const currentData = JSON.stringify(data);

    // Skip if data hasn't changed
    if (currentData === previousDataRef.current) return;

    // Clear previous timer
    if (timerRef.current) {
      clearTimeout(timerRef.current);
    }

    // Set new timer
    timerRef.current = setTimeout(() => {
      try {
        localStorage.setItem(key, currentData);
        previousDataRef.current = currentData;
        toast.success('Draft tersimpan otomatis', {
          duration: 2000,
          position: 'bottom-right',
        });
      } catch (error) {
        console.error('Failed to save draft:', error);
      }
    }, delay);

    return () => {
      if (timerRef.current) {
        clearTimeout(timerRef.current);
      }
    };
  }, [data, key, delay, enabled]);

  const restoreDraft = (): any | null => {
    try {
      const draft = localStorage.getItem(key);
      return draft ? JSON.parse(draft) : null;
    } catch (error) {
      console.error('Failed to restore draft:', error);
      return null;
    }
  };

  const clearDraft = () => {
    try {
      localStorage.removeItem(key);
    } catch (error) {
      console.error('Failed to clear draft:', error);
    }
  };

  return { restoreDraft, clearDraft };
}
