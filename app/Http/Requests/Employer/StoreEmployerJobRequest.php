<?php

namespace App\Http\Requests\Employer;

use App\Support\MacedonianPhone;
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
        $dailyPayMode = (string) $this->input('daily_pay_mode', '');
        $dailyPay = $this->input('daily_pay');

        if (blank($slug) && filled($this->input('title'))) {
            $slug = Str::slug((string) $this->input('title'));
        }

        if (! in_array($dailyPayMode, ['amount', 'agreement'], true)) {
            $dailyPayMode = filled($dailyPay) ? 'amount' : 'agreement';
        }

        if ($dailyPayMode === 'agreement') {
            $dailyPay = null;
        }

        $this->merge([
            'slug' => $slug,
            'contact_phone' => MacedonianPhone::sanitize($this->input('contact_phone')),
            'call_enabled' => $this->boolean('call_enabled'),
            'featured' => $this->boolean('featured'),
            'daily_pay_mode' => $dailyPayMode,
            'daily_pay' => $dailyPay,
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
            'daily_pay_mode' => ['nullable', Rule::in(['amount', 'agreement'])],
            'daily_pay' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'regex:'.MacedonianPhone::VALIDATION_REGEX],
            'call_enabled' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
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
            'contact_phone' => 'број за повикување',
            'call_enabled' => 'копче за повикување',
            'expires_at' => 'датум на истекување',
            'description' => 'опис',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_phone.regex' => 'Внесете валиден македонски број за повикување, на пример 070123456, +38970123456 или 021234567.',
        ];
    }
}
