<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'publish_call_phone' => ['nullable', 'boolean'],
            'email' => ['required', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
        ];
    }
}
