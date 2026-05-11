<?php

namespace App\Http\Requests\Employer;

use App\Models\JobListing;
use App\Support\TagSystem;
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
            'featured' => $this->boolean('featured'),
            'daily_pay_mode' => $dailyPayMode,
            'daily_pay' => $dailyPay,
            'tag_ids' => collect((array) $this->input('tag_ids', []))
                ->map(fn (mixed $tagId): int => (int) $tagId)
                ->filter(fn (int $tagId): bool => $tagId > 0)
                ->unique()
                ->values()
                ->all(),
        ]);
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:job_listings,slug'],
            'description' => ['nullable', 'string'],
            'daily_pay_mode' => ['nullable', Rule::in(['amount', 'agreement'])],
            'daily_pay' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'engagement_type' => ['nullable', Rule::in(JobListing::engagementTypeOptions())],
            'featured' => ['nullable', 'boolean'],
        ];

        if (TagSystem::enabled()) {
            $rules['tag_ids'] = ['nullable', 'array', 'max:5'];
            $rules['tag_ids.*'] = ['integer', 'exists:tags,id'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tag_ids.max' => 'Може да изберете најмногу :max тагови.',
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
            'engagement_type' => 'вид на работен ангажман',
            'description' => 'опис',
            'tag_ids' => 'тагови',
            'tag_ids.*' => 'таг',
        ];
    }
}
