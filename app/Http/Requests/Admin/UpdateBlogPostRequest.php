<?php

namespace App\Http\Requests\Admin;

use App\Models\BlogPost;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends StoreBlogPostRequest
{
    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        /** @var BlogPost $blogPost */
        $blogPost = $this->route('blog_post');

        $rules = parent::rules();
        $rules['slug'] = ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($blogPost->id)];

        return $rules;
    }
}
