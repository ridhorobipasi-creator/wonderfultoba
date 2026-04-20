"use client";

import { useEffect } from 'react';

export function useUnsavedChanges(isDirty: boolean, message = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?') {
  useEffect(() => {
    const handleBeforeUnload = (e: BeforeUnloadEvent) => {
      if (isDirty) {
        e.preventDefault();
        e.returnValue = message;
        return message;
      }
    };

    window.addEventListener('beforeunload', handleBeforeUnload);

    return () => {
      window.removeEventListener('beforeunload', handleBeforeUnload);
    };
  }, [isDirty, message]);
}
