import { FC, ChangeEvent } from 'react';

interface PhoneInputProps {
  value: string;
  onChange: (value: string) => void;
  className?: string;
  placeholder?: string;
  disabled?: boolean;
}

export const PhoneInput: FC<PhoneInputProps> = ({ value, onChange, className, placeholder, disabled }) => {
  const formatPhone = (val: string) => {
    if (!val) return '';
    // Only numbers
    const cleaned = val.replace(/\D/g, '');
    
    // Limit to 13 digits
    const limited = cleaned.substring(0, 13);
    
    // Format: 0812-3456-7890
    const parts = [];
    if (limited.length > 0) parts.push(limited.substring(0, 4));
    if (limited.length > 4) parts.push(limited.substring(4, 8));
    if (limited.length > 8) parts.push(limited.substring(8, 13));
    
    return parts.join('-');
  };

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const rawValue = e.target.value.replace(/\D/g, '');
    onChange(rawValue);
  };

  return (
    <div className="relative w-full">
      <input
        type="tel"
        value={formatPhone(value)}
        onChange={handleChange}
        className={className}
        placeholder={placeholder}
        disabled={disabled}
      />
    </div>
  );
};
