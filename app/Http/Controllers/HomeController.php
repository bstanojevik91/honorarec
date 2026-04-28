<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Support\DefaultBlogPosts;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    private const ENGAGEMENT_TYPES = [
        'На дневница',
        'За викенди',
        'Полно работно време',
        'Сезонска работа',
    ];

    public function index(): View
    {
        $hero = [
            'title' => 'Хонорарец.мк',
            'subtitle' => 'Најди работа на дневница',
            'image' => 'https://images.pexels.com/photos/4481260/pexels-photo-4481260.jpeg?auto=compress&cs=tinysrgb&w=1600',
        ];

        $categories = [
            [
                'icon' => 'briefcase',
                'name' => 'Дневни работи',
                'count' => '12 огласи',
            ],
            [
                'icon' => 'building-storefront',
                'name' => 'Сезонски ангажмани',
                'count' => '8 огласи',
            ],
            [
                'icon' => 'briefcase',
                'name' => 'Угостителство',
                'count' => '9 огласи',
            ],
            [
                'icon' => 'building-storefront',
                'name' => 'Магацин и логистика',
                'count' => '7 огласи',
            ],
        ];

        $promo = [
            'title' => 'Зошто пребарувањето е полесно со Honorarec.mk',
            'points' => [
                'Брзо филтрирање по клучен збор, локација и категорија',
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

        $jobs = $this->frontendJobs();
        $searchCategories = $jobs
            ->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('pages.home', [
            'hero' => $hero,
            'jobs' => $jobs->take(3)->all(),
            'categories' => collect($categories)->take(4)->all(),
            'searchCategories' => $searchCategories,
            'promo' => $promo,
            'testimonials' => $testimonials,
            'posts' => collect($posts)->take(2)->all(),
            'footerStats' => $this->footerStats($jobs),
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
        $filters = [
            'q' => trim((string) $request->string('q')),
            'city' => trim((string) $request->string('city')),
            'category' => trim((string) $request->string('category')),
            'engagement_type' => trim((string) $request->string('engagement_type')),
            'tags' => collect((array) $request->input('tags', []))
                ->map(fn (mixed $tag): string => trim((string) $tag))
                ->filter()
                ->values()
                ->all(),
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
            ->pluck('tags')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('pages.jobs', [
            'jobs' => $jobs->all(),
            'filters' => $filters,
            'availableCategories' => $availableCategories,
            'engagementTypes' => self::ENGAGEMENT_TYPES,
            'availableTags' => $availableTags,
            'footerStats' => $this->footerStats($allJobs),
        ]);
    }

    public function showJob(string $slug): View
    {
        $job = $this->frontendJobs()->firstWhere('slug', $slug);
        $jobListing = null;

        abort_if($job === null, 404);

        if (Schema::hasTable('job_listings')) {
            $jobListing = JobListing::query()
                ->with('company')
                ->where('slug', $slug)
                ->first();
        }

        return view('pages.job-show', [
            'job' => $job,
            'applicationEnabled' => $jobListing !== null,
            'footerStats' => $this->footerStats($this->frontendJobs()),
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
            Schema::hasTable('job_listings') && Schema::hasTable('job_applications'),
            404
        );

        $jobListing = JobListing::query()->where('slug', $slug)->firstOrFail();
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
        if (
            Schema::hasTable('job_listings') &&
            Schema::hasTable('companies') &&
            JobListing::query()->exists()
        ) {
            return JobListing::query()
                ->with('company')
                ->where('status', 'active')
                ->latest()
                ->get()
                ->map(function (JobListing $job): array {
                    return [
                        'slug' => $job->slug,
                        'logo' => $this->resolveCompanyLogoUrl($job->company),
                        'title' => $job->title,
                        'badge' => $job->featured ? 'Издвоено' : match ($job->status) {
                            'paused' => 'Паузирано',
                            'filled' => 'Пополнето',
                            default => 'Активно',
                        },
                        'company' => $job->company?->name ?? 'Непозната компанија',
                        'category' => $job->category,
                        'location' => $job->location,
                        'description' => $job->description,
                        'daily_pay' => $job->daily_pay,
                        'engagement_type' => $this->inferEngagementType($job),
                        'tags' => $this->inferTags($job),
                    ];
                });
        }

        return collect($this->fallbackJobListings());
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
     * @param array{q:string,city:string,category:string,engagement_type:string,tags:array<int, string>} $filters
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function filterJobs(Collection $jobs, array $filters): Collection
    {
        return $jobs
            ->filter(function (array $job) use ($filters): bool {
                $matchesKeyword = $filters['q'] === '' || collect([
                    $job['title'] ?? '',
                    $job['company'] ?? '',
                    $job['category'] ?? '',
                    $job['description'] ?? '',
                ])->contains(fn (string $value): bool => str_contains(mb_strtolower($value), mb_strtolower($filters['q'])));

                $matchesCity = $filters['city'] === '' || str_contains(
                    mb_strtolower((string) ($job['location'] ?? '')),
                    mb_strtolower($filters['city'])
                );

                $matchesCategory = $filters['category'] === '' || mb_strtolower((string) ($job['category'] ?? '')) === mb_strtolower($filters['category']);
                $matchesEngagementType = $filters['engagement_type'] === '' || mb_strtolower((string) ($job['engagement_type'] ?? '')) === mb_strtolower($filters['engagement_type']);
                $jobTags = collect($job['tags'] ?? [])->map(fn (mixed $tag): string => mb_strtolower((string) $tag));
                $matchesTags = $filters['tags'] === [] || collect($filters['tags'])
                    ->every(fn (string $tag): bool => $jobTags->contains(mb_strtolower($tag)));

                return $matchesKeyword && $matchesCity && $matchesCategory && $matchesEngagementType && $matchesTags;
            })
            ->values();
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
            return 'За викенди';
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

    /**
     * @return array<int, array<string, string>>
     */
    private function fallbackJobListings(): array
    {
        return [
            [
                'slug' => 'promoter-za-vikend-aktivnost',
                'logo' => 'https://placehold.co/96x96/eff6ff/166534?text=MK',
                'title' => 'Промотер за викенд активност',
                'badge' => 'Итно',
                'company' => 'Маркет Плус',
                'category' => 'Промоции',
                'location' => 'Скопје',
                'engagement_type' => 'За викенди',
                'tags' => ['Истакнато', 'Итно', 'Студенти', 'Флексибилно'],
            ],
            [
                'slug' => 'magacioner-za-sezonska-rabota',
                'logo' => 'https://placehold.co/96x96/fef2f2/9a3412?text=HR',
                'title' => 'Магационер за сезонска работа',
                'badge' => 'Дневница',
                'company' => 'Логистик Дооел',
                'category' => 'Магацин',
                'location' => 'Битола',
                'engagement_type' => 'Сезонска работа',
                'tags' => ['Магацин', 'Сезонско', 'Брз почеток'],
            ],
            [
                'slug' => 'terenski-popisuvac',
                'logo' => 'https://placehold.co/96x96/f0fdf4/14532d?text=IT',
                'title' => 'Теренски попишувач',
                'badge' => 'Ново',
                'company' => 'Дата Фокус',
                'category' => 'Администрација',
                'location' => 'Тетово',
                'engagement_type' => 'На дневница',
                'tags' => ['Теренска работа', 'Брз почеток', 'Флексибилно'],
            ],
            [
                'slug' => 'pomosen-rabotnik-vo-ugostitelstvo',
                'logo' => 'https://placehold.co/96x96/ecfccb/3f6212?text=FO',
                'title' => 'Помошен работник во угостителство',
                'badge' => 'Викенд',
                'company' => 'Гастро Лајн',
                'category' => 'Угостителство',
                'location' => 'Охрид',
                'engagement_type' => 'За викенди',
                'tags' => ['Викенд', 'Без искуство', 'Брз почеток'],
            ],
            [
                'slug' => 'asistent-za-nastan-i-registracija',
                'logo' => 'https://placehold.co/96x96/fae8ff/86198f?text=EV',
                'title' => 'Асистент за настан и регистрација',
                'badge' => 'Настан',
                'company' => 'Евент Партнери',
                'category' => 'Настани',
                'location' => 'Скопје',
                'engagement_type' => 'На дневница',
                'tags' => ['Настани', 'Флексибилно', 'Студенти'],
            ],
            [
                'slug' => 'vozac-za-lokalna-dostava',
                'logo' => 'https://placehold.co/96x96/e0f2fe/0c4a6e?text=DS',
                'title' => 'Возач за локална достава',
                'badge' => 'Флекс',
                'company' => 'Деливери Сервис',
                'category' => 'Логистика',
                'location' => 'Прилеп',
                'engagement_type' => 'Полно работно време',
                'tags' => ['Возачка дозвола', 'Логистика', 'Флексибилно'],
            ],
            [
                'slug' => 'promoter-vo-prodaen-salon',
                'logo' => 'https://placehold.co/96x96/fff7ed/c2410c?text=PR',
                'title' => 'Промотер во продажен салон',
                'badge' => 'Популарно',
                'company' => 'Ритејл Про',
                'category' => 'Продажба',
                'location' => 'Штип',
                'engagement_type' => 'Полно работно време',
                'tags' => ['Продажба', 'Истакнато', 'Комуникација'],
            ],
            [
                'slug' => 'administrativen-asistent-na-opredeleno',
                'logo' => 'https://placehold.co/96x96/f1f5f9/1e293b?text=AD',
                'title' => 'Административен асистент на определено',
                'badge' => 'Канцеларија',
                'company' => 'Офис Плус',
                'category' => 'Администрација',
                'location' => 'Куманово',
                'engagement_type' => 'Полно работно време',
                'tags' => ['Канцелариска работа', 'Администрација', 'Организација'],
            ],
            [
                'slug' => 'sezonski-rabotnik-za-magacin',
                'logo' => 'https://placehold.co/96x96/dcfce7/166534?text=SE',
                'title' => 'Сезонски работник за магацин',
                'badge' => 'Сезонско',
                'company' => 'Фреш Трејд',
                'category' => 'Сезонска работа',
                'location' => 'Струмица',
                'engagement_type' => 'Сезонска работа',
                'tags' => ['Сезонско', 'Магацин', 'Брз почеток'],
            ],
        ];
    }

    /**
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $jobs
     * @return array<int, array<string, string|int>>
     */
    private function footerStats(Collection $jobs): array
    {
        $companiesCount = Schema::hasTable('companies') ? Company::count() : 15;

        return [
            ['value' => $jobs->count(), 'label' => 'Огласи за работа'],
            ['value' => $companiesCount, 'label' => 'Компании'],
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
            $rawPath,
            str_starts_with($rawPath, 'storage/') ? substr($rawPath, 8) : $rawPath,
            str_starts_with($rawPath, 'companies/') ? 'companies/logos/' . basename($rawPath) : null,
            str_starts_with($rawPath, 'companies/logos/') ? 'companies/' . basename($rawPath) : null,
        ])->filter()->unique()->values();

        foreach ($candidates as $path) {
            if (Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
        }

        return $placeholder;
    }
}
