<?php

namespace Tests\Feature;

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
}
