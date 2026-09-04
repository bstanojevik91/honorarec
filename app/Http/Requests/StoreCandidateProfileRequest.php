<?php

namespace App\Http\Requests;

use App\Models\CandidateProfile;
use App\Support\PhoneNormalizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreCandidateProfileRequest extends FormRequest
{
    private ?string $normalizedPhone = null;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(array_keys(CandidateProfile::genderOptions()))],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'neighbourhood' => ['required', 'string', 'max:100'],
            'exact_address' => ['nullable', 'string', 'max:255'],
            'preferred_radius' => ['required', Rule::in(array_keys(CandidateProfile::radiusOptions()))],
            'driving_status' => ['required', Rule::in(array_keys(CandidateProfile::drivingStatusOptions()))],
            'current_employment_status' => ['required', 'boolean'],
            'engagement_types' => ['required', 'array', 'min:1', 'max:7'],
            'engagement_types.*' => [Rule::in(array_keys(CandidateProfile::engagementTypeOptions()))],
            'work_categories' => ['required', 'array', 'min:1', 'max:15'],
            'work_categories.*' => [Rule::in(array_keys(CandidateProfile::workCategoryOptions()))],
            'other_work_preference' => ['nullable', 'string', 'max:255'],
            'additional_information' => ['nullable', 'string', 'max:2000'],
            'privacy_consent' => ['accepted'],
            'turnstile_token' => ['nullable', 'string', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'име', 'last_name' => 'презиме', 'gender' => 'пол', 'date_of_birth' => 'датум на раѓање',
            'phone' => 'телефон', 'email' => 'е-пошта', 'city' => 'град/општина', 'neighbourhood' => 'населба',
            'preferred_radius' => 'радиус', 'driving_status' => 'возачка дозвола',
            'current_employment_status' => 'работен статус', 'engagement_types' => 'видови ангажман',
            'work_categories' => 'области на работа', 'privacy_consent' => 'согласност за приватност',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $validator->errors()->has('phone')) {
                $normalizedPhone = PhoneNormalizer::normalize($this->input('phone'));

                if ($normalizedPhone === null) {
                    $validator->errors()->add('phone', 'Внесете валиден телефонски број.');
                } else {
                    $this->normalizedPhone = $normalizedPhone;
                }
            }

            if (in_array('other', (array) $this->input('work_categories'), true) && blank($this->input('other_work_preference'))) {
                $validator->errors()->add('other_work_preference', 'Наведете каква друга работа ви одговара.');
            }
        });
    }

    public function normalizedPhone(): string
    {
        return $this->normalizedPhone ?? throw new \LogicException('Phone has not been normalized.');
    }
}
