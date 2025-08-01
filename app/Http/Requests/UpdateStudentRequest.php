<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $userId = $this->route('student') ?? $this->student_id;
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone_number' => 'required|string|max:20',
            'parents_phone_number' => 'nullable|string|max:20',
            'school' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
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
            'name.required' => 'Nama siswa wajib diisi.',
            'name.max' => 'Nama siswa maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh siswa lain.',
            'phone_number.required' => 'Nomor HP siswa wajib diisi.',
            'phone_number.max' => 'Nomor HP siswa maksimal 20 karakter.',
            'parents_phone_number.max' => 'Nomor HP orang tua maksimal 20 karakter.',
            'school.required' => 'Sekolah wajib diisi.',
            'school.max' => 'Nama sekolah maksimal 255 karakter.',
            'grade.required' => 'Kelas wajib diisi.',
            'grade.max' => 'Kelas maksimal 50 karakter.',
        ];
    }
}