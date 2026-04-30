<?php

namespace App\Http\Requests\Admin;

use App\Models\JobListing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreJobListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');
        $companyId = $this->input('company_id');
        $newCompanyName = trim((string) $this->input('new_company_name', ''));
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
            'company_id' => blank($companyId) ? null : $companyId,
            'new_company_name' => $newCompanyName === '' ? null : $newCompanyName,
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
            'company_id' => ['nullable', 'exists:companies,id', 'required_without:new_company_name'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:job_listings,slug'],
            'description' => ['nullable', 'string'],
            'daily_pay_mode' => ['nullable', Rule::in(['amount', 'agreement'])],
            'daily_pay' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(array_keys(JobListing::statusOptions()))],
            'expires_at' => ['nullable', 'date'],
            'new_company_name' => ['nullable', 'string', 'max:255', 'required_without:company_id'],
            'new_company_phone' => ['nullable', 'string', 'max:255'],
            'new_company_email' => ['nullable', 'email', 'max:255'],
            'new_company_logo' => ['nullable', 'image', 'max:2048'],
            'new_company_description' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_id.required_without' => 'Изберете постоечка компанија или внесете нова компанија подолу.',
            'company_id.exists' => 'Избраната компанија не постои.',
            'new_company_name.required_without' => 'Внесете нова компанија или изберете постоечка од листата.',
            'new_company_email.email' => 'Внесете валидна е-пошта за новата компанија.',
            'new_company_logo.image' => 'Логото на новата компанија мора да биде слика.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'company_id' => 'компанија',
            'new_company_name' => 'име на нова компанија',
            'new_company_email' => 'е-пошта на нова компанија',
            'new_company_logo' => 'лого на нова компанија',
        ];
    }
}
