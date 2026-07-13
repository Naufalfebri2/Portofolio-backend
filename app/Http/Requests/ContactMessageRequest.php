<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'company'    => ['nullable', 'string', 'max:255'],
            'subject'    => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'message'    => ['required', 'string', 'min:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_date.required'       => 'Tanggal wajib diisi.',
            'event_date.date'           => 'Format tanggal tidak valid.',
            'event_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
        ];
    }
}
