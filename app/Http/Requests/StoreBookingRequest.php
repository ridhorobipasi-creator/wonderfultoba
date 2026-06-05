<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'packageId' => 'required|exists:packages,id',
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email',
            'customerPhone' => 'required|string',
            'startDate' => 'required|date|after_or_equal:today',
            'pax' => 'required|integer|min:1',
            'paxChildren' => 'nullable|integer|min:0',
            'selected_services' => 'nullable|array',
            'selected_services.*' => 'string',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'packageId.exists' => 'Paket wisata yang dipilih tidak tersedia.',
            'startDate.after_or_equal' => 'Tanggal keberangkatan tidak boleh di masa lalu.',
            'customerEmail.email' => 'Format email tidak valid.',
        ];
    }
}
