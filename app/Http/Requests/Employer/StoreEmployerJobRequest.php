<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreEmployerJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');

        if (blank($slug) && filled($this->input('title'))) {
            $slug = Str::slug((string) $this->input('title'));
        }

        $this->merge([
            'slug' => $slug,
            'featured' => $this->boolean('featured'),
        ]);
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:job_listings,slug'],
            'description' => ['nullable', 'string'],
            'daily_pay' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['active', 'paused', 'filled'])],
            'expires_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'наслов',
            'daily_pay' => 'дневница / плата',
            'location' => 'локација',
            'category' => 'категорија',
            'status' => 'статус',
            'expires_at' => 'датум на истекување',
            'description' => 'опис',
        ];
    }
}
