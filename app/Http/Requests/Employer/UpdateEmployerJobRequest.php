<?php

namespace App\Http\Requests\Employer;

use App\Models\JobListing;
use Illuminate\Validation\Rule;

class UpdateEmployerJobRequest extends StoreEmployerJobRequest
{
    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        /** @var JobListing $job */
        $job = $this->route('job');

        $rules = parent::rules();
        $rules['slug'] = ['nullable', 'string', 'max:255', Rule::unique('job_listings', 'slug')->ignore($job->id)];

        return $rules;
    }
}
