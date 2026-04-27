import { useEffect } from 'react';

type Key = 's' | 'S' | 'Escape' | 'Enter';

export const useKeyboardShortcut = (
  key: Key,
  callback: (e: KeyboardEvent) => void,
  ctrlKey = false
) => {
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      const match = ctrlKey 
        ? (e.ctrlKey || e.metaKey) && e.key.toLowerCase() === key.toLowerCase()
        : e.key === key;

      if (match) {
        e.preventDefault();
        callback(e);
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [key, callback, ctrlKey]);
};
