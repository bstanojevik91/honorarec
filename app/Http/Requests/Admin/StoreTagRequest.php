<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $slug = trim((string) $this->input('slug', ''));

        if ($slug === '' && filled($this->input('name'))) {
            $slug = Str::slug((string) $this->input('name'));
        }

        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'slug' => $slug,
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:tags,slug'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'име на таг',
            'slug' => 'slug на таг',
        ];
    }
}
