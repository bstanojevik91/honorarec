<?php

namespace App\Http\Requests\Admin;

use App\Models\JobListing;
use Illuminate\Validation\Rule;

class UpdateJobListingRequest extends StoreJobListingRequest
{
    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        /** @var JobListing $jobListing */
        $jobListing = $this->route('job');

        $rules = parent::rules();
        $rules['slug'] = ['nullable', 'string', 'max:255', Rule::unique('job_listings', 'slug')->ignore($jobListing->id)];

        return $rules;
    }
}
