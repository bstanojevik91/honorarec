<?php

namespace App\Http\Requests;

use App\Support\PhoneNormalizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreJobApplicationRequest extends FormRequest
{
    private ?string $normalizedPhone = null;

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
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->has('phone')) {
                return;
            }

            $normalizedPhone = PhoneNormalizer::normalize($this->input('phone'));

            if ($normalizedPhone === null) {
                $validator->errors()->add('phone', 'Внесете валиден телефонски број.');

                return;
            }

            $this->normalizedPhone = $normalizedPhone;
        });
    }

    public function normalizedPhone(): string
    {
        return $this->normalizedPhone
            ?? throw new \LogicException('The phone number has not been normalized yet.');
    }
}
