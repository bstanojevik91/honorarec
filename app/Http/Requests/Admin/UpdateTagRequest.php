<?php

namespace App\Http\Requests\Admin;

use App\Models\Tag;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends StoreTagRequest
{
    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        /** @var Tag $tag */
        $tag = $this->route('tag');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->ignore($tag->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($tag->id)],
        ];
    }
}
