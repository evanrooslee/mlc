<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackDiscountClickRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => [
                'required',
                'string',
                'min:10',
                'max:15',
                'regex:/^(\+62|62|0)[0-9]{8,13}$/'
            ],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_number.required' => 'Nomor HP wajib diisi.',
            'phone_number.min' => 'Nomor HP minimal 10 karakter.',
            'phone_number.max' => 'Nomor HP maksimal 15 karakter.',
            'phone_number.regex' => 'Format nomor HP tidak valid. Gunakan format Indonesia (contoh: 08123456789 atau +6281234567890).',
        ];
    }
}
