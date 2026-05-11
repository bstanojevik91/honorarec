@php(
    $selectedTagIds = collect(old('tag_ids', $selectedTagIds ?? []))
        ->map(fn (mixed $tagId): int => (int) $tagId)
        ->filter(fn (int $tagId): bool => $tagId > 0)
        ->values()
        ->all()
)

@if (($availableTags ?? collect())->count() > 0)
    <div class="{{ $wrapperClass ?? 'lg:col-span-2' }}">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <label class="block text-sm font-semibold text-slate-700">{{ $label ?? 'Тагови' }}</label>
                @if (! empty($helpText ?? null))
                    <p class="mt-1 text-xs text-slate-500">{{ $helpText }}</p>
                @endif
            </div>
            <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-400">Максимум 5</p>
        </div>

        <div class="mt-3 flex flex-wrap gap-2.5">
            @foreach ($availableTags as $tagOption)
                <label class="inline-flex cursor-pointer items-center">
                    <input
                        type="checkbox"
                        name="tag_ids[]"
                        value="{{ $tagOption->id }}"
                        class="peer sr-only"
                        @checked(in_array($tagOption->id, $selectedTagIds, true))
                    >
                    <span class="inline-flex rounded-full border border-slate-200 px-3.5 py-2 text-sm font-semibold text-slate-600 transition peer-checked:border-emerald-200 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 hover:border-slate-300 hover:bg-slate-50 sm:px-4">
                        {{ $tagOption->name }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
@endif
