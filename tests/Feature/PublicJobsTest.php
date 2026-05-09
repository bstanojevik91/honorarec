<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_jobs_pages_only_use_active_database_listings(): void
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'jobs@test-company.mk',
            'phone' => '070123456',
            'description' => 'Test company description',
        ]);

        $activeJob = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Активен оглас',
            'slug' => 'aktiven-oglas',
            'description' => 'Опис за активен оглас.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => true,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Паузиран оглас',
            'slug' => 'pauziran-oglas',
            'description' => 'Опис за паузиран оглас.',
            'daily_pay' => 1200,
            'location' => 'Битола',
            'category' => 'Магацин',
            'featured' => false,
            'status' => JobListing::STATUS_PAUSED,
        ]);

        $this->get('/oglasi')
            ->assertOk()
            ->assertSee('Активен оглас')
            ->assertDontSee('Паузиран оглас');

        $this->get(route('jobs.show', $activeJob->slug))
            ->assertOk()
            ->assertSee('Активен оглас');

        $this->get(route('jobs.show', 'pauziran-oglas'))
            ->assertNotFound();
    }

    public function test_homepage_shows_honest_empty_state_when_there_are_no_jobs(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Моментално нема активни огласи');
    }

    public function test_job_page_shows_only_real_active_related_jobs(): void
    {
        $company = Company::create([
            'name' => 'Related Jobs Company',
            'email' => 'related@test-company.mk',
            'phone' => '070123456',
            'description' => 'Test company description',
        ]);

        $currentJob = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Главен оглас',
            'slug' => 'glaven-oglas',
            'description' => 'Опис за главен оглас.',
            'daily_pay' => 1700,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => true,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        $firstRelatedJob = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Промотер за настан',
            'slug' => 'promoter-za-nastan',
            'description' => 'Опис за прв поврзан оглас.',
            'daily_pay' => 1600,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        $secondRelatedJob = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Асистент за промоции',
            'slug' => 'asistent-za-promocii',
            'description' => 'Опис за втор поврзан оглас.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        $thirdRelatedJob = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Теренски промотер',
            'slug' => 'terenski-promoter',
            'description' => 'Опис за трет поврзан оглас.',
            'daily_pay' => 1400,
            'location' => 'Тетово',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Паузиран тест оглас',
            'slug' => 'pauziran-test-oglas',
            'description' => 'Опис за паузиран оглас.',
            'daily_pay' => 1300,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_PAUSED,
        ]);

        $response = $this->get(route('jobs.show', $currentJob->slug));

        $response->assertOk()
            ->assertSee('Слични огласи')
            ->assertSee(route('jobs.show', $firstRelatedJob->slug), false)
            ->assertSee(route('jobs.show', $secondRelatedJob->slug), false)
            ->assertSee(route('jobs.show', $thirdRelatedJob->slug), false)
            ->assertDontSee('promoter-za-vikend-aktivnost')
            ->assertDontSee('magacioner-za-sezonska-rabota')
            ->assertDontSee('terenski-popisuvac')
            ->assertDontSee('pomosen-rabotnik-vo-ugostitelstvo')
            ->assertDontSee('pauziran-test-oglas');
    }

    public function test_city_filter_for_skopje_includes_jobs_from_skopje_municipalities(): void
    {
        $company = Company::create([
            'name' => 'Skopje Filter Company',
            'email' => 'skopje-filter@test-company.mk',
            'phone' => '070123456',
            'description' => 'Company for Skopje filter test.',
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Општ оглас за Скопје',
            'slug' => 'opst-oglas-za-skopje',
            'description' => 'Опис за оглас од Скопје.',
            'daily_pay' => 1700,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Оглас за Аеродром',
            'slug' => 'oglas-za-aerodrom',
            'description' => 'Опис за оглас од Аеродром.',
            'daily_pay' => 1650,
            'location' => 'Аеродром',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Оглас за Кисела Вода',
            'slug' => 'oglas-za-kisela-voda',
            'description' => 'Опис за оглас од Кисела Вода.',
            'daily_pay' => 1600,
            'location' => 'Скопје - Кисела Вода',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Оглас за Битола',
            'slug' => 'oglas-za-bitola',
            'description' => 'Опис за оглас од Битола.',
            'daily_pay' => 1500,
            'location' => 'Битола',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        $this->get(route('jobs.index', ['city' => 'Скопје']))
            ->assertOk()
            ->assertSee('Општ оглас за Скопје')
            ->assertSee('Оглас за Аеродром')
            ->assertSee('Оглас за Кисела Вода')
            ->assertDontSee('Оглас за Битола');
    }

    public function test_city_filter_for_specific_municipality_only_returns_that_municipality(): void
    {
        $company = Company::create([
            'name' => 'Municipality Filter Company',
            'email' => 'municipality-filter@test-company.mk',
            'phone' => '070123456',
            'description' => 'Company for municipality filter test.',
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Оглас во Аеродром',
            'slug' => 'oglas-vo-aerodrom',
            'description' => 'Опис за оглас во Аеродром.',
            'daily_pay' => 1750,
            'location' => 'Скопје - Аеродром',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Оглас во Карпош',
            'slug' => 'oglas-vo-karpos',
            'description' => 'Опис за оглас во Карпош.',
            'daily_pay' => 1600,
            'location' => 'Карпош',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Општ оглас во Скопје',
            'slug' => 'opst-oglas-vo-skopje',
            'description' => 'Опис за општ оглас во Скопје.',
            'daily_pay' => 1550,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        $this->get(route('jobs.index', ['city' => 'Аеродром']))
            ->assertOk()
            ->assertSee('Оглас во Аеродром')
            ->assertDontSee('Оглас во Карпош')
            ->assertDontSee('Општ оглас во Скопје');
    }

    public function test_location_filter_keeps_keyword_and_category_filters_working(): void
    {
        $company = Company::create([
            'name' => 'Combined Filter Company',
            'email' => 'combined-filter@test-company.mk',
            'phone' => '070123456',
            'description' => 'Company for combined filter test.',
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Промотер во Аеродром',
            'slug' => 'promoter-vo-aerodrom',
            'description' => 'Промотерски ангажман во Аеродром.',
            'daily_pay' => 1800,
            'location' => 'Аеродром',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Магационер во Аеродром',
            'slug' => 'magacioner-vo-aerodrom',
            'description' => 'Магацински ангажман во Аеродром.',
            'daily_pay' => 1700,
            'location' => 'Аеродром',
            'category' => 'Магацин',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Промотер во Битола',
            'slug' => 'promoter-vo-bitola',
            'description' => 'Промотерски ангажман во Битола.',
            'daily_pay' => 1650,
            'location' => 'Битола',
            'category' => 'Промоции',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        $this->get(route('jobs.index', [
            'q' => 'промотер',
            'city' => 'Скопје',
            'category' => 'Промоции',
        ]))
            ->assertOk()
            ->assertSee('Промотер во Аеродром')
            ->assertDontSee('Магационер во Аеродром')
            ->assertDontSee('Промотер во Битола');
    }

    public function test_honorarna_rabota_landing_page_shows_seo_content_and_latest_jobs(): void
    {
        $company = Company::create([
            'name' => 'SEO Company',
            'email' => 'seo@test-company.mk',
            'phone' => '070123456',
            'description' => 'SEO company description',
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Хонорарна промоција',
            'slug' => 'honorarna-promocija',
            'description' => 'Опис за хонорарна промоција.',
            'daily_pay' => 1800,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => true,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Сезонска работа во продажба',
            'slug' => 'sezonska-rabota-vo-prodazba',
            'description' => 'Опис за сезонска работа.',
            'daily_pay' => 1600,
            'location' => 'Охрид',
            'category' => 'Продажба',
            'featured' => false,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Скриен оглас',
            'slug' => 'skrien-oglas',
            'description' => 'Опис за скриен оглас.',
            'daily_pay' => 1000,
            'location' => 'Битола',
            'category' => 'Администрација',
            'featured' => false,
            'status' => JobListing::STATUS_PAUSED,
        ]);

        $response = $this->get(route('seo.honorarna-rabota'));

        $response->assertOk()
            ->assertSee('Хонорарна работа во Македонија')
            ->assertSee('https://honorarec.mk/honorarna-rabota', false)
            ->assertSee('Бараш хонорарна работа или работа на дневница? Пребарај part-time, сезонски и флексибилни огласи на Honorarec.mk.')
            ->assertSee('Хонорарна промоција')
            ->assertSee('Сезонска работа во продажба')
            ->assertDontSee('Скриен оглас')
            ->assertSee(route('home'), false)
            ->assertSee(route('jobs.index'), false)
            ->assertSee(route('post-a-job'), false);
    }

    public function test_sitemap_xml_includes_static_pages_and_public_database_content(): void
    {
        $company = Company::create([
            'name' => 'Sitemap Company',
            'email' => 'sitemap@test-company.mk',
            'phone' => '070123456',
            'description' => 'Sitemap company description',
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Активен sitemap оглас',
            'slug' => 'aktiven-sitemap-oglas',
            'description' => 'Опис за активен sitemap оглас.',
            'daily_pay' => 1800,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => true,
            'status' => JobListing::STATUS_ACTIVE,
        ]);

        JobListing::create([
            'company_id' => $company->id,
            'title' => 'Паузиран sitemap оглас',
            'slug' => 'pauziran-sitemap-oglas',
            'description' => 'Опис за паузиран sitemap оглас.',
            'daily_pay' => 1200,
            'location' => 'Битола',
            'category' => 'Магацин',
            'featured' => false,
            'status' => JobListing::STATUS_PAUSED,
        ]);

        BlogPost::create([
            'title' => 'Објавен blog пост',
            'slug' => 'objaven-blog-post',
            'excerpt' => 'Краток извадок.',
            'content' => 'Содржина на објавениот пост.',
            'category' => 'Совети',
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);

        BlogPost::create([
            'title' => 'Нацрт blog пост',
            'slug' => 'nacrt-blog-post',
            'excerpt' => 'Краток извадок за нацрт.',
            'content' => 'Содржина на нацрт постот.',
            'category' => 'Совети',
            'status' => BlogPost::STATUS_DRAFT,
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
            ->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false)
            ->assertSee(route('home'), false)
            ->assertSee(route('jobs.index'), false)
            ->assertSee(route('seo.honorarna-rabota'), false)
            ->assertSee(route('faq'), false)
            ->assertSee(route('jobs.show', 'aktiven-sitemap-oglas'), false)
            ->assertDontSee(route('jobs.show', 'pauziran-sitemap-oglas'), false)
            ->assertSee(route('blog.show', 'objaven-blog-post'), false)
            ->assertDontSee(route('blog.show', 'nacrt-blog-post'), false)
            ->assertSee('<changefreq>daily</changefreq>', false)
            ->assertSee('<priority>1.0</priority>', false);
    }

    public function test_public_robots_txt_matches_expected_rules(): void
    {
        $this->assertFileExists(public_path('robots.txt'));

        $this->assertSame(
            "User-agent: *\nAllow: /\n\nSitemap: https://honorarec.mk/sitemap.xml\n",
            file_get_contents(public_path('robots.txt'))
        );
    }
}
