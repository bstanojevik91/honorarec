<?php

namespace App\Http\Requests\Admin;

class UpdateCompanyRequest extends StoreCompanyRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'remove_logo' => ['nullable', 'boolean'],
        ]);
    }
}
