<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\BlogPost;
use App\Support\DefaultBlogPosts;

trait ImportsDefaultBlogPosts
{
    protected function importDefaultBlogPosts(): int
    {
        $created = 0;

        foreach (DefaultBlogPosts::database() as $post) {
            $existing = BlogPost::query()->where('slug', $post['slug'])->first();

            if ($existing !== null) {
                continue;
            }

            BlogPost::query()->create($post);
            $created++;
        }

        return $created;
    }
}
