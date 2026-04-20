import { AxiosError } from 'axios';

interface ApiError {
  message?: string;
  errors?: Record<string, string[]>;
}

export const getErrorMessage = (error: unknown): string => {
  if (error instanceof AxiosError) {
    const apiError = error.response?.data as ApiError;

    // Field-specific errors
    if (apiError?.errors) {
      const errors = apiError.errors;
      return Object.entries(errors)
        .map(([field, messages]) => {
          const fieldName = field
            .replace(/_/g, ' ')
            .replace(/\b\w/g, (l) => l.toUpperCase());
          return `${fieldName}: ${messages[0]}`;
        })
        .join('\n');
    }

    // HTTP status code errors with helpful messages
    const statusCode = error.response?.status;
    switch (statusCode) {
      case 400:
        return 'Data yang Anda masukkan tidak valid. Periksa kembali form.';
      case 401:
        return 'Sesi Anda telah berakhir. Silakan login kembali.';
      case 403:
        return 'Anda tidak memiliki izin untuk melakukan aksi ini.';
      case 404:
        return 'Data tidak ditemukan. Mungkin sudah dihapus.';
      case 409:
        return 'Data sudah ada. Gunakan nama/slug yang berbeda.';
      case 422:
        return apiError?.message || 'Validasi gagal. Periksa kembali data yang Anda masukkan.';
      case 500:
        return 'Terjadi kesalahan di server. Tim kami sudah diberitahu.';
      default:
        return apiError?.message || 'Terjadi kesalahan. Silakan coba lagi.';
    }
  }

  // Generic error
  if (error instanceof Error) {
    return error.message;
  }

  return 'Terjadi kesalahan yang tidak diketahui.';
};

export const showErrorToast = (error: unknown, toast: any) => {
  const message = getErrorMessage(error);
  toast.error(message, { duration: 5000 });
};
