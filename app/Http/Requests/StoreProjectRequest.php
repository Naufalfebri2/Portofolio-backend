<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Otorisasi admin sudah ditangani oleh middleware (auth:sanctum + IsAdmin)
     * di level route, jadi di sini cukup true.
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
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string'],
            'type'               => ['required', 'in:solo,team'],
            'role'               => ['nullable', 'string', 'max:255'],
            'repo_url'           => ['nullable', 'url', 'max:255'],
            'demo_url'           => ['nullable', 'url', 'max:255'],
            'is_featured'        => ['boolean'],
            'order'              => ['integer', 'min:0'],
            'technologies'       => ['array'],
            'technologies.*'     => ['integer', 'exists:technologies,id'],
            'thumbnail'          => ['nullable', 'image', 'max:2048'],
            'gallery'            => ['array'],
            'gallery.*'          => ['nullable', 'image', 'max:2048'],
        ];
    }
}
