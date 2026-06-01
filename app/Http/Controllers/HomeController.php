<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\Tag;
use App\Support\DefaultBlogPosts;
use App\Support\LocationOptions;
use App\Support\PublicUrl;
use App\Support\TagSystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private const NO_PUBLIC_CALL_TOKEN = '__NO_PUBLIC_CALL__';
    private const SITEMAP_CACHE_KEY = 'public-sitemap.xml.v2';

    private const ENGAGEMENT_TYPES = [
        'На дневница',
        'Part-time',
        'Викенд работа',
        'Сезонска работа',
        'Полно работно време',
    ];

    private const LEGACY_ENGAGEMENT_TYPE_MAP = [
        'за викенди' => 'Викенд работа',
        'викенд' => 'Викенд работа',
        'скратено работно време' => 'Part-time',
        'part time' => 'Part-time',
        'part-time' => 'Part-time',
        'full time' => 'Полно работно време',
        'full-time' => 'Полно работно време',
    ];

    public function index(): View
    {
        $jobs = $this->frontendJobs();

        $hero = [
            'title' => 'Хонорарец.мк',
            'subtitle' => 'Најди работа на дневница',
            'image' => 'https://images.pexels.com/photos/4481260/pexels-photo-4481260.jpeg?auto=compress&cs=tinysrgb&w=1600',
        ];

        $promo = [
            'title' => 'Зошто пребарувањето е полесно со Honorarec.mk',
            'points' => [
                'Брзо филтрирање по категорија, локација и тип на ангажман',
                'Проверени огласи што лесно се скенираат и споредуваат',
                'Јасен пат од пребарување до аплицирање без непотребни чекори',
            ],
            'primary_image' => 'https://images.pexels.com/photos/30411827/pexels-photo-30411827.jpeg?auto=compress&cs=tinysrgb&w=1200',
            'secondary_image' => 'https://images.pexels.com/photos/16647493/pexels-photo-16647493.jpeg?auto=compress&cs=tinysrgb&w=900',
        ];

        $testimonials = [
            [
                'name' => 'Ана Т.',
                'role' => 'Студент',
                'quote' => 'Преку Хонорарец најдов викенд ангажман за само неколку дена и успеав да си покријам дел од трошоците.',
                'highlighted' => false,
            ],
            [
                'name' => 'Игор К.',
                'role' => 'Фриленсер',
                'quote' => 'Многу едноставна платформа, прегледни огласи и брз контакт со компаниите. Ова е токму тоа што му недостигаше на пазарот.',
                'highlighted' => true,
            ],
            [
                'name' => 'Марија С.',
                'role' => 'Сезонски работник',
                'quote' => 'Најдов сезонска работа без непотребно губење време. Категориите и пребарувањето ми помогнаа веднаш да се снајдам.',
                'highlighted' => false,
            ],
        ];

        $posts = $this->publicBlogPosts();
        $searchCategories = $jobs
            ->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('pages.home', [
            'hero' => $hero,
            'jobs' => $jobs->take(9)->all(),
            'categories' => $this->homepageCategories($jobs),
            'searchCategories' => $searchCategories,
            'engagementTypes' => self::ENGAGEMENT_TYPES,
            'promo' => $promo,
            'testimonials' => $testimonials,
            'posts' => collect($posts)->take(2)->all(),
            'footerStats' => $this->footerStats($jobs),
            ...$this->locationFilterViewData(trim((string) request()->string('city'))),
        ]);
    }

    public function faq(): View
    {
        $faqs = [
            [
                'question' => 'Како да објавам оглас и да стапам во контакт со хонорарец',
                'answer' => 'За сите прашања и информации, можете да не контактирате на телефонскиот број 070 214 325.',
            ],
            [
                'question' => 'Што е Honorarec.mk и како функционира?',
                'answer' => 'Honorarec.mk е платформа за лица кои бараат втора работа, работа на дневница или краткорочни хонорарни ангажмани. Работодавачите објавуваат огласи, а кандидатите аплицираат директно преку сајтот.',
            ],
            [
                'question' => 'Дали мора да направам профил за да аплицирам?',
                'answer' => 'Не мора. Кај поголемиот дел од огласите можеш да контактираш директно преку телефон, е-маил или порака.',
            ],
            [
                'question' => 'Дали огласите се проверени и валидни?',
                'answer' => 'Секој оглас го проверуваме пред објава. Ако нешто изгледа сомнително, не се објавува. Ако корисник пријави проблем, огласот веднаш се брише.',
            ],
            [
                'question' => 'Во кои градови има огласи?',
                'answer' => 'Огласите може да бидат од цела Македонија, со фокус на поголемите градови како Скопје, Битола, Тетово, Охрид, Прилеп и други.',
            ],
            [
                'question' => 'Какви категории на работа можам да најдам?',
                'answer' => 'Може да најдете промоции, теренска работа, администрација, магацин, угостителство, сезонски ангажмани и многу други категории.',
            ]
        ];

        return view('pages.faq', [
            'faqs' => $faqs,
            'footerStats' => $this->footerStats($this->frontendJobs()),
        ]);
    }

    public function blog(): View
    {
        $posts = collect($this->publicBlogPosts());

        return view('pages.blog-index', [
            'posts' => $posts->all(),
            'featuredPost' => $posts->first(),
            'footerStats' => $this->footerStats($this->frontendJobs()),
        ]);
    }

    public function jobs(Request $request): View
    {
        $allJobs = $this->frontendJobs();
        $selectedTags = collect(explode(',', trim((string) $request->string('tag'))))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->merge(
                collect((array) $request->input('tags', []))
                    ->map(fn (mixed $tag): string => trim((string) $tag))
                    ->filter()
            )
            ->unique()
            ->values()
            ->all();

        $filters = [
            'city' => trim((string) $request->string('city')),
            'category' => trim((string) $request->string('category')),
            'engagement_type' => trim((string) $request->string('engagement_type')),
            'tags' => $selectedTags,
        ];

        $jobs = $this->filterJobs($allJobs, $filters);
        $availableCategories = $allJobs
            ->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
        $availableTags = $allJobs
            ->pipe(fn (Collection $jobs): array => $this->availablePublicTags($jobs));

        return view('pages.jobs', [
            'jobs' => $jobs->all(),
            'filters' => $filters,
            'availableCategories' => $availableCategories,
            'engagementTypes' => self::ENGAGEMENT_TYPES,
            'availableTags' => $availableTags,
            'selectedTags' => collect($availableTags)
                ->filter(fn (array $tag): bool => in_array($tag['slug'], $filters['tags'], true))
                ->values()
                ->all(),
            'footerStats' => $this->footerStats($allJobs),
            ...$this->locationFilterViewData($filters['city']),
        ]);
    }

    public function honorarnaRabota(): View
    {
        $jobs = $this->frontendJobs();
        $canonical = 'https://honorarec.mk/honorarna-rabota';

        return view('pages.honorarna-rabota', [
            'title' => 'Хонорарна работа во Македонија | Honorarec.mk',
            'description' => 'Бараш хонорарна работа или работа на дневница? Пребарај part-time, сезонски и флексибилни огласи на Honorarec.mk.',
            'canonical' => $canonical,
            'ogTitle' => 'Хонорарна работа во Македонија | Honorarec.mk',
            'ogDescription' => 'Бараш хонорарна работа или работа на дневница? Пребарај part-time, сезонски и флексибилни огласи на Honorarec.mk.',
            'ogUrl' => $canonical,
            'ogImage' => asset('images/honorarec-logo.png'),
            'jobTypes' => [
                [
                    'title' => 'Хонорарна работа',
                    'text' => 'Краткорочни и флексибилни ангажмани за дополнителен приход, проекти или сезонски потреби.',
                ],
                [
                    'title' => 'Работа на дневница',
                    'text' => 'Огласи за брз почеток, јасно дефиниран ангажман и исплата по ден или по смена.',
                ],
                [
                    'title' => 'Part-time работа',
                    'text' => 'Позиции за неколку часа дневно, викенд смени или комбинирање со редовна обврска.',
                ],
                [
                    'title' => 'Сезонска работа',
                    'text' => 'Ангажмани поврзани со туристичка сезона, настани, продажба и зголемени оперативни потреби.',
                ],
                [
                    'title' => 'Флексибилни ангажмани',
                    'text' => 'Работи со прилагодливо работно време, теренски активности и краткорочен договор.',
                ],
            ],
            'latestJobs' => $jobs->take(6)->values()->all(),
            'footerStats' => $this->footerStats($jobs),
        ]);
    }

    public function sitemap(): Response
    {
        $payload = app()->environment('testing')
            ? $this->buildSitemapPayload()
            : Cache::remember(self::SITEMAP_CACHE_KEY, now()->addMinutes(30), fn (): array => $this->buildSitemapPayload());

        $response = response()
            ->view('sitemap.xml', ['urls' => $payload['urls']])
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=1800');

        if (! empty($payload['lastModified'])) {
            $response->header('Last-Modified', $payload['lastModified']);
        }

        return $response;
    }

    public function showJob(string $slug): View
    {
        abort_unless(Schema::hasTable('job_listings') && Schema::hasTable('companies'), 404);

        $jobListing = $this->publicJobListingsQuery()
            ->where('slug', $slug)
            ->firstOrFail();
        $job = $this->mapFrontendJob($jobListing);
        $jobs = $this->frontendJobs();

        return view('pages.job-show', [
            'job' => $job,
            'relatedJobs' => $this->relatedJobsFor($job, $jobs),
            'applicationEnabled' => Schema::hasTable('job_applications'),
            'callPhone' => $this->normalizeCallPhone($jobListing?->company?->phone),
            'footerStats' => $this->footerStats($jobs),
        ]);
    }

    public function showBlogPost(string $slug): View
    {
        $posts = collect($this->publicBlogPosts());
        $post = $posts->firstWhere('slug', $slug);

        abort_if($post === null, 404);

        $relatedPosts = $posts
            ->reject(fn (array $blogPost): bool => $blogPost['slug'] === $slug)
            ->take(3)
            ->values()
            ->all();

        $recentPosts = $posts
            ->reject(fn (array $blogPost): bool => $blogPost['slug'] === $slug)
            ->take(4)
            ->values()
            ->all();

        $categories = $posts
            ->pluck('category')
            ->filter()
            ->countBy()
            ->map(fn (int $count, string $name): array => ['name' => $name, 'count' => $count])
            ->values()
            ->all();

        return view('pages.blog-show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'footerStats' => $this->footerStats($this->frontendJobs()),
        ]);
    }

    public function apply(StoreJobApplicationRequest $request, string $slug): RedirectResponse
    {
        abort_unless(
            Schema::hasTable('job_listings') &&
            Schema::hasTable('job_applications') &&
            Schema::hasTable('companies'),
            404
        );

        $jobListing = $this->publicJobListingsQuery()
            ->where('slug', $slug)
            ->firstOrFail();
        $data = $request->validated();

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('applications/cv', 'public');
        }

        unset($data['cv']);

        $jobListing->applications()->create($data);

        return redirect()
            ->route('jobs.show', $slug)
            ->with('application_status', 'Вашата апликација е успешно испратена.')
            ->withFragment('apply-form');
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function frontendJobs(): Collection
    {
        if (! Schema::hasTable('job_listings') || ! Schema::hasTable('companies')) {
            return collect();
        }

        return $this->publicJobListingsQuery()
            ->get()
            ->map(fn (JobListing $job): array => $this->mapFrontendJob($job));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function publicBlogPosts(): array
    {
        if (Schema::hasTable('blog_posts') && BlogPost::query()->where('status', BlogPost::STATUS_PUBLISHED)->exists()) {
            return BlogPost::query()
                ->where('status', BlogPost::STATUS_PUBLISHED)
                ->latest('published_at')
                ->latest()
                ->get()
                ->map(fn (BlogPost $post): array => $this->mapBlogPost($post))
                ->all();
        }

        return $this->fallbackBlogPosts();
    }

    /**
     * @return array<int, array{loc:string,lastmod:string,changefreq:string,priority:string}>
     */
    private function sitemapStaticEntries(?string $jobLatest, ?string $blogLatest): array
    {
        $homeLastmod = $this->sitemapLastmod(
            $this->latestTimestamp(
                $this->viewLastModified('home.blade.php'),
                $this->parseSitemapDate($jobLatest),
                $this->parseSitemapDate($blogLatest)
            )
        );
        $jobsLastmod = $this->sitemapLastmod(
            $this->latestTimestamp(
                $this->viewLastModified('jobs.blade.php'),
                $this->parseSitemapDate($jobLatest)
            )
        );
        $seoLastmod = $this->sitemapLastmod(
            $this->latestTimestamp(
                $this->viewLastModified('honorarna-rabota.blade.php'),
                $this->parseSitemapDate($jobLatest)
            )
        );
        $faqLastmod = $this->sitemapLastmod($this->viewLastModified('faq.blade.php'));
        $blogIndexLastmod = $this->sitemapLastmod(
            $this->latestTimestamp(
                $this->viewLastModified('blog-index.blade.php'),
                $this->parseSitemapDate($blogLatest)
            )
        );

        return array_values(array_filter([
            [
                'loc' => $this->publicRoute('home'),
                'lastmod' => $homeLastmod,
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => $this->publicRoute('jobs.index'),
                'lastmod' => $jobsLastmod,
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => $this->publicRoute('seo.honorarna-rabota'),
                'lastmod' => $seoLastmod,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => $this->publicRoute('faq'),
                'lastmod' => $faqLastmod,
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => $this->publicRoute('blog.index'),
                'lastmod' => $blogIndexLastmod,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
        ]));
    }

    /**
     * @return array<int, array{loc:string,lastmod:string,changefreq:string,priority:string}>
     */
    private function sitemapJobEntries(): array
    {
        if (! Schema::hasTable('job_listings') || ! Schema::hasTable('companies')) {
            return [];
        }

        return $this->publicJobListingsQuery()
            ->get(['slug', 'updated_at', 'created_at'])
            ->map(fn (JobListing $job): array => [
                'loc' => $this->publicRoute('jobs.show', $job->slug),
                'lastmod' => $this->sitemapLastmod($job->updated_at ?? $job->created_at),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ])
            ->all();
    }

    /**
     * @return array<int, array{loc:string,lastmod:string,changefreq:string,priority:string}>
     */
    private function sitemapBlogEntries(): array
    {
        if (! Schema::hasTable('blog_posts')) {
            return [];
        }

        return BlogPost::query()
            ->where('status', BlogPost::STATUS_PUBLISHED)
            ->latest('published_at')
            ->latest()
            ->get(['slug', 'published_at', 'updated_at', 'created_at'])
            ->map(fn (BlogPost $post): array => [
                'loc' => $this->publicRoute('blog.show', $post->slug),
                'lastmod' => $this->sitemapLastmod($post->updated_at ?? $post->published_at ?? $post->created_at),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ])
            ->all();
    }

    /**
     * @return array{urls:array<int, array{loc:string,lastmod:string,changefreq:string,priority:string}>,lastModified:?string}
     */
    private function buildSitemapPayload(): array
    {
        $jobEntries = $this->sitemapJobEntries();
        $blogEntries = $this->sitemapBlogEntries();
        $jobLatest = collect($jobEntries)->pluck('lastmod')->filter()->max();
        $blogLatest = collect($blogEntries)->pluck('lastmod')->filter()->max();

        $urls = array_merge(
            $this->sitemapStaticEntries($jobLatest, $blogLatest),
            $jobEntries,
            $blogEntries
        );

        return [
            'urls' => $urls,
            'lastModified' => collect($urls)->pluck('lastmod')->filter()->max(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapBlogPost(BlogPost $post): array
    {
        return [
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'meta_description' => $post->meta_description ?: $post->excerpt,
            'category' => $post->category ?: 'Блог',
            'reading_time' => $this->estimateReadingTime($post->content),
            'published_at' => ($post->published_at ?? $post->created_at)?->format('d.m.Y'),
            'image' => $post->featuredImageUrl()
                ?: 'https://images.pexels.com/photos/4481260/pexels-photo-4481260.jpeg?auto=compress&cs=tinysrgb&w=1400',
            'intro' => $post->excerpt,
            'content' => $post->content,
            'author' => 'Тимот на Honorarec.mk',
            'sections' => [],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fallbackBlogPosts(): array
    {
        return DefaultBlogPosts::frontend();
    }

    private function estimateReadingTime(string $content): string
    {
        $wordCount = max(1, str_word_count(strip_tags($content)));
        $minutes = max(1, (int) ceil($wordCount / 180));

        return $minutes . ' минути читање';
    }

    /**
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $jobs
     * @param array{city:string,category:string,engagement_type:string,tags:array<int, string>} $filters
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function filterJobs(Collection $jobs, array $filters): Collection
    {
        return $jobs
            ->filter(function (array $job) use ($filters): bool {
                $matchesCity = LocationOptions::matches($job['location'] ?? null, $filters['city']);

                $matchesCategory = $filters['category'] === '' || mb_strtolower((string) ($job['category'] ?? '')) === mb_strtolower($filters['category']);
                $matchesEngagementType = $filters['engagement_type'] === '' || mb_strtolower((string) ($job['engagement_type'] ?? '')) === mb_strtolower($filters['engagement_type']);
                $jobTags = collect($job['tags'] ?? [])
                    ->map(fn (array $tag): string => mb_strtolower((string) ($tag['slug'] ?? '')));
                $matchesTags = $filters['tags'] === [] || collect($filters['tags'])
                    ->every(fn (string $tag): bool => $jobTags->contains(mb_strtolower($tag)));

                return $matchesCity && $matchesCategory && $matchesEngagementType && $matchesTags;
            })
            ->values();
    }

    /**
     * @param array<string, mixed> $job
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $jobs
     * @return array<int, array<string, mixed>>
     */
    private function relatedJobsFor(array $job, Collection $jobs): array
    {
        return $jobs
            ->reject(fn (array $candidate): bool => $candidate['slug'] === $job['slug'])
            ->values()
            ->map(function (array $candidate, int $index) use ($job): array {
                return [
                    'job' => $candidate,
                    'index' => $index,
                    'score' => $this->relatedJobScore($job, $candidate),
                ];
            })
            ->sort(function (array $left, array $right): int {
                return $right['score'] <=> $left['score']
                    ?: $left['index'] <=> $right['index'];
            })
            ->take(3)
            ->pluck('job')
            ->values()
            ->all();
    }

    /**
     * @param array<string, mixed> $job
     * @param array<string, mixed> $candidate
     */
    private function relatedJobScore(array $job, array $candidate): int
    {
        $score = 0;

        if (
            filled($job['category'] ?? null) &&
            mb_strtolower((string) $candidate['category']) === mb_strtolower((string) $job['category'])
        ) {
            $score += 4;
        }

        if (
            filled($job['location'] ?? null) &&
            mb_strtolower((string) $candidate['location']) === mb_strtolower((string) $job['location'])
        ) {
            $score += 3;
        }

        if (
            filled($job['engagement_type'] ?? null) &&
            mb_strtolower((string) $candidate['engagement_type']) === mb_strtolower((string) $job['engagement_type'])
        ) {
            $score += 2;
        }

        if (
            filled($job['company'] ?? null) &&
            mb_strtolower((string) $candidate['company']) === mb_strtolower((string) $job['company'])
        ) {
            $score += 1;
        }

        return $score;
    }

    private function inferEngagementType(JobListing $job): string
    {
        $haystack = mb_strtolower(trim(implode(' ', array_filter([
            $job->title,
            $job->category,
            $job->description,
        ]))));

        if (str_contains($haystack, 'сезон')) {
            return 'Сезонска работа';
        }

        if (str_contains($haystack, 'викенд')) {
            return 'Викенд работа';
        }

        if (
            str_contains($haystack, 'скратено работно време') ||
            str_contains($haystack, 'part time') ||
            str_contains($haystack, 'part-time')
        ) {
            return 'Part-time';
        }

        if (
            str_contains($haystack, 'полно работно време') ||
            str_contains($haystack, 'full time') ||
            in_array(mb_strtolower((string) $job->category), ['администрација', 'продажба'], true)
        ) {
            return 'Полно работно време';
        }

        return 'На дневница';
    }

    private function resolveEngagementType(JobListing $job): string
    {
        $storedValue = trim((string) ($job->engagement_type ?? ''));

        if (in_array($storedValue, self::ENGAGEMENT_TYPES, true)) {
            return $storedValue;
        }

        $normalizedStoredValue = mb_strtolower($storedValue);

        if (array_key_exists($normalizedStoredValue, self::LEGACY_ENGAGEMENT_TYPE_MAP)) {
            return self::LEGACY_ENGAGEMENT_TYPE_MAP[$normalizedStoredValue];
        }

        return $this->inferEngagementType($job);
    }

    private function normalizeCallPhone(null|string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $candidates = collect(preg_split('/(?:\r\n|\r|\n|,|;|\|)+/', $phone) ?: [])
            ->map(fn (string $candidate): string => trim($candidate))
            ->filter();

        // If publishing is disabled, hide the call button regardless of any stored phone number.
        if ($candidates->map(fn (string $candidate): string => mb_strtoupper($candidate))->contains(self::NO_PUBLIC_CALL_TOKEN)) {
            return null;
        }

        foreach ($candidates as $candidate) {
            if (str_starts_with($candidate, '+')) {
                $normalized = '+'.preg_replace('/\D+/', '', substr($candidate, 1));
            } else {
                $normalized = preg_replace('/\D+/', '', $candidate);
            }

            if (! is_string($normalized) || $normalized === '') {
                continue;
            }

            if (preg_match('/^(?:\+389\d{8}|0\d{8})$/', $normalized) === 1) {
                return $normalized;
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private function inferTags(JobListing $job): array
    {
        $haystack = mb_strtolower(trim(implode(' ', array_filter([
            $job->title,
            $job->category,
            $job->description,
            $job->location,
        ]))));

        $tags = [];

        if ($job->featured) {
            $tags[] = 'Истакнато';
        }

        if (str_contains($haystack, 'итно')) {
            $tags[] = 'Итно';
        }

        if (str_contains($haystack, 'викенд')) {
            $tags[] = 'Викенд';
        }

        if (str_contains($haystack, 'сезон')) {
            $tags[] = 'Сезонско';
        }

        if (str_contains($haystack, 'терен')) {
            $tags[] = 'Теренска работа';
        }

        if (str_contains($haystack, 'промо')) {
            $tags[] = 'Промоции';
        }

        if (str_contains($haystack, 'администра')) {
            $tags[] = 'Канцелариска работа';
        }

        if (str_contains($haystack, 'магацин')) {
            $tags[] = 'Магацин';
        }

        if ($job->daily_pay !== null) {
            $tags[] = 'Платено веднаш';
        }

        return collect($tags)
            ->push('Брз почеток')
            ->push('Флексибилно')
            ->unique()
            ->take(4)
            ->values()
            ->all();
    }

    private function publicJobListingsQuery(): Builder
    {
        $query = JobListing::query()
            ->with('company')
            ->where('status', JobListing::STATUS_ACTIVE)
            ->latest();

        if (TagSystem::enabled()) {
            $query->with('tags');
        }

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function mapFrontendJob(JobListing $job): array
    {
        return [
            'slug' => $job->slug,
            'logo' => $this->resolveCompanyLogoUrl($job->company),
            'title' => $job->title,
            'badge' => $job->featured ? 'Издвоено' : match ($job->status) {
                JobListing::STATUS_PAUSED => 'Паузирано',
                JobListing::STATUS_FILLED => 'Пополнето',
                default => 'Активно',
            },
            'company' => $job->company?->name ?? 'Непозната компанија',
            'category' => $job->category,
            'location' => $job->location,
            'description' => $job->description,
            'daily_pay' => $job->daily_pay,
            'engagement_type' => $this->resolveEngagementType($job),
            'job_image' => $job->job_image,
            'tags' => $this->resolvePublicTags($job),
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $jobs
     * @return array<int, array{name:string,slug:string}>
     */
    private function availablePublicTags(Collection $jobs): array
    {
        if (TagSystem::enabled()) {
            return Tag::query()
                ->whereHas('jobListings', fn (Builder $query): Builder => $query->where('status', JobListing::STATUS_ACTIVE))
                ->orderBy('name')
                ->get(['name', 'slug'])
                ->map(fn (Tag $tag): array => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ])
                ->all();
        }

        return $jobs
            ->pluck('tags')
            ->flatten(1)
            ->filter(fn (mixed $tag): bool => is_array($tag) && filled($tag['name'] ?? null) && filled($tag['slug'] ?? null))
            ->unique('slug')
            ->sortBy('name')
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{name:string,slug:string}>
     */
    private function resolvePublicTags(JobListing $job): array
    {
        if (TagSystem::enabled()) {
            $tags = $job->relationLoaded('tags')
                ? $job->tags
                : $job->tags()->orderBy('name')->get();

            return $tags
                ->sortBy('name')
                ->map(fn (Tag $tag): array => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ])
                ->values()
                ->all();
        }

        return collect($this->inferTags($job))
            ->map(fn (string $tag): array => [
                'name' => $tag,
                'slug' => Str::slug($tag),
            ])
            ->all();
    }

    /**
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $jobs
     * @return array<int, array<string, string>>
     */
    private function homepageCategories(Collection $jobs): array
    {
        return $jobs
            ->pluck('category')
            ->map(fn (mixed $category): string => trim((string) $category))
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(4)
            ->map(function (int $count, string $category): array {
                return [
                    'icon' => $this->homepageCategoryIcon($category),
                    'name' => $category,
                    'count' => $count . ' ' . ($count === 1 ? 'оглас' : 'огласи'),
                ];
            })
            ->values()
            ->all();
    }

    private function homepageCategoryIcon(string $category): string
    {
        $normalized = mb_strtolower($category);

        if (
            str_contains($normalized, 'продаж') ||
            str_contains($normalized, 'промо') ||
            str_contains($normalized, 'угост') ||
            str_contains($normalized, 'настан')
        ) {
            return 'building-storefront';
        }

        return 'briefcase';
    }

    /**
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $jobs
     * @return array<int, array<string, string|int>>
     */
    private function footerStats(Collection $jobs): array
    {
        $companiesCount = Schema::hasTable('companies') ? Company::count() : 0;

        return [
            ['value' => $jobs->count(), 'label' => 'Огласи за работа'],
            ['value' => $companiesCount, 'label' => 'Компании'],
        ];
    }

    /**
     * @return array{locationTree:array<int, array{name:string, municipalities:array<int, array{name:string}>}>, selectedLocationLabel:?string}
     */
    private function locationFilterViewData(string $selectedLocation): array
    {
        return [
            'locationTree' => LocationOptions::tree(),
            'selectedLocationLabel' => LocationOptions::displayLabel($selectedLocation),
        ];
    }

    private function resolveCompanyLogoUrl(?Company $company): string
    {
        $placeholder = 'https://placehold.co/96x96/eff6ff/166534?text=' . urlencode(mb_substr($company?->name ?? 'HR', 0, 2));

        if ($company === null || blank($company->logo_path)) {
            return $placeholder;
        }

        $rawPath = ltrim(trim((string) $company->logo_path), '/');

        $candidates = collect([
            str_starts_with($rawPath, 'storage/') ? $rawPath : 'storage/' . $rawPath,
            str_starts_with($rawPath, 'companies/') ? 'storage/companies/logos/' . basename($rawPath) : null,
            str_starts_with($rawPath, 'companies/logos/') ? 'storage/companies/' . basename($rawPath) : null,
        ])->filter()->unique()->values();

        foreach ($candidates as $publicPath) {
            if (file_exists(public_path($publicPath))) {
                return asset($publicPath);
            }
        }

        return $placeholder;
    }

    private function viewLastModified(string $view): ?Carbon
    {
        $path = resource_path('views/pages/' . $view);

        if (! is_file($path)) {
            return null;
        }

        return Carbon::createFromTimestamp(filemtime($path));
    }

    private function latestTimestamp(?Carbon ...$timestamps): ?Carbon
    {
        return collect($timestamps)
            ->filter(fn (?Carbon $timestamp): bool => $timestamp !== null)
            ->sortByDesc(fn (Carbon $timestamp): int => $timestamp->getTimestamp())
            ->first();
    }

    private function parseSitemapDate(?string $value): ?Carbon
    {
        return filled($value) ? Carbon::parse($value) : null;
    }

    private function sitemapLastmod(?Carbon $value): string
    {
        return ($value ?? now())->copy()->utc()->toAtomString();
    }

    /**
     * @param  array<string, mixed>|string  $parameters
     */
    private function publicRoute(string $name, array|string $parameters = []): string
    {
        return PublicUrl::absolutePath(route($name, $parameters, false));
    }
}
