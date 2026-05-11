<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;
use App\Models\Tag;
use App\Support\TagSystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $this->ensureTagSystemIsEnabled();

        return view('admin.tags.index', [
            'tags' => Tag::query()
                ->withCount('jobListings')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): View
    {
        $this->ensureTagSystemIsEnabled();

        return view('admin.tags.create');
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        $this->ensureTagSystemIsEnabled();

        Tag::create($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Тагот е успешно додаден.');
    }

    public function edit(Tag $tag): View
    {
        $this->ensureTagSystemIsEnabled();

        return view('admin.tags.edit', [
            'tag' => $tag,
        ]);
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $this->ensureTagSystemIsEnabled();

        $tag->update($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Тагот е успешно ажуриран.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->ensureTagSystemIsEnabled();

        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Тагот е избришан.');
    }

    private function ensureTagSystemIsEnabled(): void
    {
        abort_unless(TagSystem::enabled(), 404);
    }
}
