<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

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
            'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80',
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
        ];

        $promo = [
            'title' => 'Пронајди ја твојата работа од соништата',
            'points' => [
                'Дополнителен приход',
                'Сезонска работа',
                'Втора работа',
            ],
            'primary_image' => 'https://images.unsplash.com/photo-1484981138541-3d074aa97716?auto=format&fit=crop&w=900&q=80',
            'secondary_image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=700&q=80',
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

        $posts = $this->blogPosts();

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
            'categories' => $categories,
            'searchCategories' => $searchCategories,
            'promo' => $promo,
            'testimonials' => $testimonials,
            'posts' => $posts,
            'footerStats' => $this->footerStats($jobs),
        ]);
    }

    public function faq(): View
    {
        $faqs = [
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
            ],
            [
                'question' => 'Како да стапам во контакт со Honorarec.mk?',
                'answer' => 'Засега користете го линкот „Објави оглас / Контакт“ во навигацијата. Подоцна тука може да се поврзе контакт форма, е-пошта и телефон.',
            ],
        ];

        return view('pages.faq', [
            'faqs' => $faqs,
            'footerStats' => $this->footerStats($this->frontendJobs()),
        ]);
    }

    public function blog(): View
    {
        $posts = collect($this->blogPosts());

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
        $posts = collect($this->blogPosts());
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
                        'logo' => $job->company?->logo_path
                            ? asset('storage/'.$job->company->logo_path)
                            : 'https://placehold.co/96x96/eff6ff/166534?text='.urlencode(mb_substr($job->company?->name ?? 'HR', 0, 2)),
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
    private function blogPosts(): array
    {
        return [
            [
                'slug' => 'kako-pobrzo-da-najdes-rabota-na-dnevnica',
                'title' => 'Како побрзо да најдеш работа на дневница',
                'excerpt' => 'Неколку практични совети како да го средиш профилот и да аплицираш попаметно за краткорочни ангажмани.',
                'meta_description' => 'Практични совети како побрзо да најдеш работа на дневница, како да аплицираш и што работодавачите најмногу забележуваат.',
                'category' => 'Совети за кандидати',
                'reading_time' => '4 минути читање',
                'published_at' => '01 април 2026',
                'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1400&q=80',
                'intro' => 'Брзата работа на дневница најчесто ја добиваат оние што имаат јасна порака, точен телефон и кратка, но уверлива апликација.',
                'sections' => [
                    [
                        'heading' => 'Подготви кратко, но јасно претставување',
                        'content' => 'Кога аплицираш за краткорочен ангажман, работодавачот сака веднаш да разбере кој си, дали си достапен и дали можеш брзо да започнеш. Наместо долги објаснувања, користи неколку директни реченици.',
                        'points' => [
                            'Наведи кога можеш да започнеш.',
                            'Остави точен и активен телефонски број.',
                            'Нагласи ако веќе имаш слично искуство.',
                        ],
                    ],
                    [
                        'heading' => 'Следи огласи редовно',
                        'content' => 'Огласите за дневница често се затвораат брзо. Затоа е важно да ја проверуваш платформата редовно и да аплицираш веднаш штом ќе видиш оглас што ти одговара.',
                    ],
                    [
                        'heading' => 'Телефонот нека ти биде достапен',
                        'content' => 'Голем дел од компаниите прво контактираат по телефон. Ако бројот не е точен или не одговараш навреме, шансата лесно оди кај друг кандидат.',
                    ],
                ],
            ],
            [
                'slug' => 'koi-sezonski-raboti-se-najbarani-vo-momentov',
                'title' => 'Кои сезонски работи се најбарани во моментов',
                'excerpt' => 'Погледни ги најчестите категории на работа и што работодавците најмногу бараат од кандидатите.',
                'meta_description' => 'Преглед на најбараните сезонски работи во Македонија и категориите во кои компаниите најчесто бараат кандидати.',
                'category' => 'Пазар на труд',
                'reading_time' => '3 минути читање',
                'published_at' => '30 март 2026',
                'image' => 'https://images.unsplash.com/photo-1484981138541-3d074aa97716?auto=format&fit=crop&w=1400&q=80',
                'intro' => 'Сезонските ангажмани се меѓу најбараните огласи, особено во логистика, продажба, угостителство и промоции.',
                'sections' => [
                    [
                        'heading' => 'Магацин и логистика',
                        'content' => 'Во периоди на зголемена испорака и сезонски набавки, компаниите најчесто бараат магационери, сортирачи и помошни работници за товар и истовар.',
                    ],
                    [
                        'heading' => 'Угостителство и туризам',
                        'content' => 'Во туристичките места најчесто се отвораат позиции за помошен персонал, келнери, шанкери и сезонски работници во хотели и ресторани.',
                    ],
                    [
                        'heading' => 'Промоции и продажба',
                        'content' => 'Промотери, демонстратори и лица за теренска продажба се бараат кога брендови имаат активни кампањи и настани.',
                        'points' => [
                            'Комуникативност и директен контакт со луѓе.',
                            'Подготвеност за работа на терен или настани.',
                            'Флексибилност за викенд или попладневни смени.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'kako-da-odberes-vtora-rabota-sto-ti-odgovara',
                'title' => 'Како да одбереш втора работа што ти одговара',
                'excerpt' => 'Втората работа треба да биде флексибилна и одржлива. Овој водич ќе ти помогне да процениш што ти одговара.',
                'meta_description' => 'Совети како да избереш втора работа што навистина одговара на твоето време, распоред и финансиски цели.',
                'category' => 'Кариера',
                'reading_time' => '5 минути читање',
                'published_at' => '28 март 2026',
                'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80',
                'intro' => 'Добрата втора работа не е само дополнителен приход, туку и ангажман што можеш реално да го вклопиш во секојдневието без да се преоптовариш.',
                'sections' => [
                    [
                        'heading' => 'Провери колку време навистина имаш',
                        'content' => 'Најчестата грешка е да се прифати ангажман што изгледа добро на хартија, но не е реален во однос на распоредот. Прво процени ги деновите, часовите и патувањето.',
                    ],
                    [
                        'heading' => 'Избери тип на ангажман што ти одговара',
                        'content' => 'Ако ти треба флексибилност, разгледај викенд или дневни ангажмани. Ако бараш постабилен дополнителен приход, барај подолгорочни и повторливи позиции.',
                        'points' => [
                            'За повеќе слобода избери викенд или дневни задачи.',
                            'За стабилност барај повторливи ангажмани.',
                            'Секогаш спореди ја заработката со времето што го вложуваш.',
                        ],
                    ],
                    [
                        'heading' => 'Гледај ја комуникацијата со работодавачот',
                        'content' => 'Јасна комуникација, точни услови и навремен одговор често кажуваат многу за тоа како ќе изгледа целата соработка.',
                    ],
                ],
            ],
        ];
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
}
