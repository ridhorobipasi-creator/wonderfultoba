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
    if (!val) return '';
    const numericStr = String(val).replace(/\D/g, '');
    if (!numericStr) return '';
    return new Intl.NumberFormat('id-ID').format(Number(numericStr));
  };

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const rawValue = e.target.value.replace(/\D/g, '');
    onChange(rawValue ? Number(rawValue) : 0);
  };

  return (
    <input
      type="text"
      value={formatCurrency(value)}
      onChange={handleChange}
      className={className}
      placeholder={placeholder}
      disabled={disabled}
    />
  );
};
