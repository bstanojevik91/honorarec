<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\User;
use App\Support\CategoryName;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\CreatesJobCategoryTable;
use Tests\TestCase;

class JobCategoriesFeatureTest extends TestCase
{
    use CreatesJobCategoryTable;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createJobCategoryTable();

        // The existing approval-status migration only runs on MySQL. Allow the
        // same statuses in this SQLite regression fixture without changing it.
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('job_listings', function (Blueprint $table): void {
                $table->string('status')->default('pending')->change();
            });
        }
    }

    public function test_normalization_handles_cyrillic_and_unicode_whitespace_without_merging_other_concepts(): void
    {
        $this->assertSame('угостителство', CategoryName::normalize("\u{00a0}УГОСТИТЕЛСТВО\t\n"));
        $this->assertSame('работа на терен', CategoryName::normalize(" Работа\u{2003}\t НА   терен "));
        $this->assertSame('ѓорче петров', CategoryName::normalize('ЃОРЧЕ ПЕТРОВ'));
        $this->assertNotSame(CategoryName::normalize('Угостителство'), CategoryName::normalize('Хотелиерство'));
        $this->assertNotSame(CategoryName::normalize('Угостителство'), CategoryName::normalize('Ugostitelstvo'));
        $this->assertNotSame(CategoryName::normalize('Угостителство'), CategoryName::normalize('Hospitality'));
    }

    public function test_public_counts_and_legacy_links_work_with_manually_canonicalized_data(): void
    {
        $company = $this->company();
        for ($i = 0; $i < 12; $i++) {
            $job = $this->job($company, 'Угостителство', [
                // The existing publication query includes active, expired jobs.
                'expires_at' => now()->subDay(),
            ]);
        }
        foreach (['pending', 'paused', 'filled', 'rejected'] as $status) {
            $this->job($company, 'Угостителство', ['status' => $status]);
        }
        foreach (['Хотелиерство', 'Ugostitelstvo', 'Hospitality', 'Работа на терен', 'Работа на терен', ''] as $name) {
            $this->job($company, $name);
        }
        // This fixture represents data after the separately executed manual SQL.
        // The production cleanup itself can be tested once its database version is known.
        $this->get(route('home'))->assertOk()->assertViewHas('categories', function ($categories) {
            $matches = collect($categories)->where('name', 'Угостителство');

            return $matches->count() === 1 && $matches->first()['count'] === '12 огласи';
        })->assertSee(e(route('jobs.index', ['q' => '', 'city' => '', 'category' => 'Угостителство', 'engagement_type' => ''], false)), false);

        foreach (['Угостителство', 'угостителство', "\u{00a0}УГОСТИТЕЛСТВО\t"] as $legacyName) {
            $this->get(route('jobs.index', ['category' => $legacyName]))->assertOk()
                ->assertViewHas('jobs', fn ($jobs) => count($jobs) === 12)
                ->assertViewHas('filters', fn ($filters) => $filters['category'] === 'Угостителство');
        }
        $this->get(route('jobs.index', ['category' => "работа\t на  терен"]))->assertOk()
            ->assertViewHas('jobs', fn ($jobs) => count($jobs) === 2);
        $this->get(route('jobs.show', $job->slug))->assertOk()->assertSee('Угостителство');
    }

    public static function roles(): array
    {
        return [['admin'], ['employer']];
    }

    #[DataProvider('roles')]
    public function test_forms_select_existing_categories_and_preserve_values_after_errors(string $role): void
    {
        $company = $this->company();
        $this->actingAs($this->user($role, $company));
        JobCategory::create(['name' => 'Угостителство']);
        JobCategory::create(['name' => 'Хотелиерство']);
        $job = $this->job($company, 'Угостителство');

        foreach (['create', 'edit'] as $action) {
            $response = $this->get(route("$role.jobs.$action", $action === 'edit' ? $job : []))
                ->assertOk()->assertSee('Избери категорија');
            $select = $this->categorySelect($response->getContent());
            $this->assertCount(1, $select->query('//select[@name="category"]'));
            $this->assertCount(0, $select->query('//input[@name="category"]'));
            $this->assertCount(1, $select->query('//select[@name="category"]/option[@value="Угостителство"]'));
            if ($action === 'edit') {
                $this->assertCount(1, $select->query('//option[@value="Угостителство"][@selected]'));
            }

            $url = route("$role.jobs.$action", $action === 'edit' ? $job : []);
            $payload = ['title' => '', 'company_id' => $company->id, 'category' => 'Хотелиерство'];
            $this->from($url)->{ $action === 'edit' ? 'put' : 'post' }(
                route("$role.jobs.".($action === 'edit' ? 'update' : 'store'), $action === 'edit' ? $job : []), $payload
            )->assertRedirect($url)->assertSessionHasErrors('title');
            $select = $this->categorySelect($this->get($url)->assertOk()->getContent());
            $this->assertCount(1, $select->query('//option[@value="Хотелиерство"][@selected]'));
        }
    }

    #[DataProvider('roles')]
    public function test_direct_job_requests_only_accept_existing_categories_and_allow_empty_categories(string $role): void
    {
        $company = $this->company();
        $this->actingAs($this->user($role, $company));
        JobCategory::create(['name' => 'Угостителство']);
        $job = $this->job($company, 'Угостителство');
        $payload = ['title' => 'Нов оглас', 'slug' => 'new-job', 'company_id' => $company->id];

        foreach (['Произволна категорија', '99999', ['Угостителство']] as $invalid) {
            $this->post(route("$role.jobs.store"), $payload + ['category' => $invalid])->assertSessionHasErrors('category');
            $this->put(route("$role.jobs.update", $job), $payload + ['category' => $invalid])->assertSessionHasErrors('category');
        }
        $this->assertDatabaseCount('job_listings', 1);
        $this->assertDatabaseCount('job_categories', 1);
        $this->assertSame('Угостителство', $job->fresh()->category);

        $this->post(route("$role.jobs.store"), $payload + ['category' => "\u{00a0}УГОСТИТЕЛСТВО\t"])
            ->assertSessionHasNoErrors()->assertRedirect(route("$role.jobs.index"));
        $created = JobListing::where('slug', 'new-job')->firstOrFail();
        $this->assertSame('Угостителство', $created->category);
        $this->put(route("$role.jobs.update", $created), $payload + ['category' => ''])
            ->assertSessionHasNoErrors()->assertRedirect(route("$role.jobs.index"));
        $this->assertSame('', $created->fresh()->category);
        $this->post(route("$role.jobs.store"), ['title' => 'Без категорија', 'company_id' => $company->id])
            ->assertSessionHasNoErrors()->assertRedirect(route("$role.jobs.index"));
        $this->assertDatabaseCount('job_categories', 1);
    }

    public function test_only_admins_can_create_categories_and_duplicate_names_are_rejected(): void
    {
        $company = $this->company();
        $this->get(route('admin.categories.index'))->assertRedirect();
        $this->post(route('admin.categories.store'), ['name' => 'Тајна'])->assertRedirect();
        $this->actingAs($this->user('employer', $company));
        $this->get(route('admin.categories.index'))->assertForbidden();
        $this->post(route('admin.categories.store'), ['name' => 'Тајна'])->assertForbidden();
        $this->assertDatabaseCount('job_categories', 0);

        $this->actingAs($this->user('admin', $company));
        $this->post(route('admin.categories.store'), ['name' => " Работа\u{00a0} на\tтерен "])
            ->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('job_categories', ['name' => 'Работа на терен', 'normalized_name' => 'работа на терен']);
        $this->post(route('admin.categories.store'), ['name' => 'РАБОТА   НА ТЕРЕН'])
            ->assertSessionHasErrors(['name' => 'Категорија со ова име веќе постои.']);
        $this->post(route('admin.categories.store'), ['name' => "\u{00a0}\t"])->assertSessionHasErrors('name');
        $this->get(route('admin.categories.index'))->assertOk()->assertSee('Работа на терен');

        foreach (['admin', 'employer'] as $role) {
            $this->actingAs(User::where('email', "$role@test.mk")->firstOrFail());
            $job = $this->job($company, 'Работа на терен');
            $this->get(route("$role.jobs.create"))->assertOk()->assertSee('Работа на терен');
            $this->get(route("$role.jobs.edit", $job))->assertOk()->assertSee('Работа на терен');
        }
        $this->assertDatabaseCount('job_categories', 1);
    }

    public function test_database_enforces_normalized_uniqueness(): void
    {
        JobCategory::create(['name' => 'Угостителство']);
        $this->expectException(UniqueConstraintViolationException::class);
        JobCategory::create(['name' => ' угостителство ']);
    }

    private function company(): Company
    {
        return Company::create(['name' => 'Компанија', 'email' => 'company@test.mk', 'phone' => '070123456']);
    }

    private function user(string $role, Company $company): User
    {
        $user = User::create([
            'name' => $role, 'email' => "$role@test.mk", 'password' => 'password123',
            'is_admin' => $role === 'admin', 'company_id' => $company->id,
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function job(Company $company, string $category, array $overrides = []): JobListing
    {
        return JobListing::create(array_replace([
            'company_id' => $company->id, 'title' => 'Оглас '.JobListing::count(),
            'slug' => 'job-'.JobListing::count(), 'description' => 'Опис', 'location' => 'Скопје',
            'category' => $category, 'status' => JobListing::STATUS_ACTIVE,
        ], $overrides));
    }

    private function categorySelect(string $html): \DOMXPath
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);

        return new \DOMXPath($dom);
    }
}
