import { FC, ChangeEvent, forwardRef } from 'react';

interface PhoneInputProps extends React.InputHTMLAttributes<HTMLInputElement> {}

export const PhoneInput = forwardRef<HTMLInputElement, PhoneInputProps>(({ className, placeholder, onChange, ...props }, ref) => {
  const formatPhone = (val: string) => {
    if (!val) return '';
    let numericStr = val.replace(/\D/g, '');
    if (numericStr.startsWith('0')) {
      numericStr = '62' + numericStr.substring(1);
    }
    if (numericStr.length <= 2) return `+${numericStr}`;
    let formatted = `+${numericStr.substring(0, 2)}`;
    if (numericStr.length > 2) formatted += ` ${numericStr.substring(2, 5)}`;
    if (numericStr.length > 5) formatted += `-${numericStr.substring(5, 9)}`;
    if (numericStr.length > 9) formatted += `-${numericStr.substring(9, 13)}`;
    return formatted;
  };

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const rawValue = e.target.value.replace(/\D/g, '');
    let finalValue = rawValue;
    if (rawValue.startsWith('0')) finalValue = '62' + rawValue.substring(1);
    
    // Create a synthetic event
    const event = {
      ...e,
      target: {
        ...e.target,
        value: finalValue,
        name: e.target.name
      }
    };
    
    if (onChange) {
      onChange(event as any);
    }
  };

  return (
    <input
      type="tel"
      ref={ref}
      {...props}
      value={formatPhone(props.value as string || '')}
      onChange={handleChange}
      className={className}
      placeholder={placeholder || "+62 812-3456-7890"}
      maxLength={19}
    />
  );
});

PhoneInput.displayName = 'PhoneInput';
