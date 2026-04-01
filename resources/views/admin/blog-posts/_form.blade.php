@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div class="lg:col-span-2">
        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Наслов</label>
        <input id="title" name="title" type="text" value="{{ old('title', $post->title ?? '') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $post->slug ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
        <p class="mt-2 text-xs text-slate-500">Ако го оставите празно, ќе се генерира автоматски од насловот.</p>
    </div>

    <div>
        <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Категорија</label>
        <input id="category" name="category" type="text" value="{{ old('category', $post->category ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Статус</label>
        <select id="status" name="status" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $post->status ?? 'draft') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="published_at" class="mb-2 block text-sm font-semibold text-slate-700">Датум на објава</label>
        <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div class="lg:col-span-2">
        <label for="excerpt" class="mb-2 block text-sm font-semibold text-slate-700">Краток опис</label>
        <textarea id="excerpt" name="excerpt" rows="3" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    </div>

    <div class="lg:col-span-2">
        <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">Содржина</label>
        <div class="mb-3 flex flex-wrap gap-2">
            <button type="button" data-editor-insert="h2" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">H2</button>
            <button type="button" data-editor-insert="h3" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">H3</button>
            <button type="button" data-editor-insert="p" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Параграф</button>
            <button type="button" data-editor-insert="ul" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Листа</button>
            <button type="button" data-editor-insert="a" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Линк</button>
        </div>
        <textarea id="content" name="content" rows="16" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 font-mono text-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('content', $post->content ?? '') }}</textarea>
        <div class="mt-3 rounded-[1.25rem] bg-slate-50 px-4 py-4 text-xs leading-6 text-slate-500">
            Поддржан е основен HTML за полесно форматирање: <code>&lt;h2&gt;</code>, <code>&lt;h3&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;strong&gt;</code>.
        </div>
    </div>

    <div>
        <label for="featured_image" class="mb-2 block text-sm font-semibold text-slate-700">Истакната слика</label>
        <input id="featured_image" name="featured_image" type="file" accept="image/*" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        @if (!empty($post?->featured_image))
            <p class="mb-2 block text-sm font-semibold text-slate-700">Тековна слика</p>
            <div class="overflow-hidden rounded-[1.2rem] border border-slate-200">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="h-40 w-full object-cover">
            </div>
        @else
            <div class="flex h-full items-center rounded-[1.2rem] border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-sm text-slate-500">
                Нема поставена слика.
            </div>
        @endif
    </div>

    <div>
        <label for="meta_title" class="mb-2 block text-sm font-semibold text-slate-700">Meta title</label>
        <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title', $post->meta_title ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="meta_description" class="mb-2 block text-sm font-semibold text-slate-700">Meta description</label>
        <textarea id="meta_description" name="meta_description" rows="3" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-4">
    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('admin.blog-posts.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
        Откажи
    </a>
</div>

<script>
    document.querySelectorAll('[data-editor-insert]').forEach((button) => {
        button.addEventListener('click', () => {
            const textarea = document.getElementById('content');
            if (!textarea) return;

            const templates = {
                h2: '<h2>Наслов на секција</h2>\n<p>Текст на секцијата...</p>\n',
                h3: '<h3>Поднаслов</h3>\n<p>Дополнителен текст...</p>\n',
                p: '<p>Нов параграф...</p>\n',
                ul: '<ul>\n    <li>Ставка 1</li>\n    <li>Ставка 2</li>\n</ul>\n',
                a: '<p><a href=\"https://example.com\" target=\"_blank\" rel=\"noopener noreferrer\">Текст на линк</a></p>\n',
            };

            const snippet = templates[button.dataset.editorInsert] ?? '';
            const start = textarea.selectionStart ?? textarea.value.length;
            const end = textarea.selectionEnd ?? textarea.value.length;
            textarea.value = textarea.value.slice(0, start) + snippet + textarea.value.slice(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + snippet.length;
        });
    });
</script>
