import { FC, ChangeEvent } from 'react';

interface CurrencyInputProps {
  value: number | string;
  onChange: (value: number) => void;
  className?: string;
  placeholder?: string;
  disabled?: boolean;
}

export const CurrencyInput: FC<CurrencyInputProps> = ({ value, onChange, className, placeholder, disabled }) => {
  const formatCurrency = (val: number | string) => {
    if (val === undefined || val === null || val === '') return '';
    const numericStr = String(val).replace(/\D/g, '');
    if (!numericStr) return '';
    return `Rp ${new Intl.NumberFormat('id-ID').format(Number(numericStr))}`;
  };

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    // Remove everything except numbers
    const rawValue = e.target.value.replace(/\D/g, '');
    onChange(rawValue ? Number(rawValue) : 0);
  };

  return (
    <div className="relative w-full">
      <input
        type="text"
        value={formatCurrency(value)}
        onChange={handleChange}
        className={className}
        placeholder={placeholder}
        disabled={disabled}
      />
    </div>
  );
};
