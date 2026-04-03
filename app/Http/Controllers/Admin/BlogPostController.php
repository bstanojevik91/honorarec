<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ImportsDefaultBlogPosts;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    use ImportsDefaultBlogPosts;

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $status = trim((string) $request->string('status'));
        $category = trim((string) $request->string('category'));

        $posts = BlogPost::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%' . $search . '%'))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.blog-posts.index', [
            'posts' => $posts,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'category' => $category,
            ],
            'statusOptions' => BlogPost::statusOptions(),
            'categories' => BlogPost::query()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'hasPosts' => BlogPost::query()->exists(),
        ]);
    }

    public function importDefaults(): RedirectResponse
    {
        $created = $this->importDefaultBlogPosts();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', $created > 0
                ? 'Стартните блог постови се внесени во админ системот.'
                : 'Стартните блог постови веќе постојат во админ системот.');
    }

    public function create(): View
    {
        return view('admin.blog-posts.create', [
            'statusOptions' => BlogPost::statusOptions(),
        ]);
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request->validated(), $request);

        BlogPost::create($data);

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', 'Блог постот е успешно додаден.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.blog-posts.edit', [
            'post' => $blogPost,
            'statusOptions' => BlogPost::statusOptions(),
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        $data = $this->prepareData($request->validated(), $request, $blogPost);

        $blogPost->update($data);

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', 'Блог постот е успешно ажуриран.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->featured_image && !filter_var($blogPost->featured_image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($blogPost->featured_image);
        }

        $blogPost->delete();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', 'Блог постот е избришан.');
    }

    public function toggleStatus(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->update([
            'status' => $blogPost->status === BlogPost::STATUS_PUBLISHED
                ? BlogPost::STATUS_DRAFT
                : BlogPost::STATUS_PUBLISHED,
            'published_at' => $blogPost->status === BlogPost::STATUS_PUBLISHED
                ? null
                : ($blogPost->published_at ?? now()),
        ]);

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', $blogPost->fresh()->status === BlogPost::STATUS_PUBLISHED
                ? 'Постот е објавен.'
                : 'Постот е вратен во нацрт.');
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function prepareData(array $data, Request $request, ?BlogPost $blogPost = null): array
    {
        $data['slug'] = $data['slug'] ?: Str::slug((string) $data['title']);
        $data['published_at'] = $data['status'] === BlogPost::STATUS_PUBLISHED
            ? ($data['published_at'] ?? ($blogPost?->published_at ?? now()))
            : null;

        if ($request->hasFile('featured_image')) {
            if ($blogPost?->featured_image && !filter_var($blogPost->featured_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($blogPost->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        } elseif ($blogPost !== null) {
            $data['featured_image'] = $blogPost->featured_image;
        }

        return $data;
    }
}
