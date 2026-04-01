<?php

namespace App\Http\Requests\Admin;

use App\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');

        if (blank($slug) && filled($this->input('title'))) {
            $slug = Str::slug((string) $this->input('title'));
        }

        $this->merge([
            'slug' => $slug,
        ]);
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(array_keys(BlogPost::statusOptions()))],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'наслов',
            'slug' => 'slug',
            'excerpt' => 'краток опис',
            'content' => 'содржина',
            'featured_image' => 'истакната слика',
            'category' => 'категорија',
            'status' => 'статус',
            'published_at' => 'датум на објава',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
        ];
    }
}
