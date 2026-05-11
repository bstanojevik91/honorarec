<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobListing;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JobTagsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_tags(): void
    {
        $this->createTagTables();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-tags@test.mk',
            'password' => 'password123',
            'is_admin' => true,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        $this->actingAs($admin)
            ->post(route('admin.tags.store'), [
                'name' => 'Итно',
                'slug' => 'itno',
            ])
            ->assertRedirect(route('admin.tags.index'));

        $tag = Tag::query()->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.tags.update', $tag), [
                'name' => 'Викенд работа',
                'slug' => 'vikend-rabota',
            ])
            ->assertRedirect(route('admin.tags.index'));

        $tag->refresh();

        $this->assertSame('Викенд работа', $tag->name);
        $this->assertSame('vikend-rabota', $tag->slug);

        $this->actingAs($admin)
            ->delete(route('admin.tags.destroy', $tag))
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseCount('tags', 0);
    }

    public function test_employer_can_assign_multiple_tags_to_job_listing(): void
    {
        $this->createTagTables();

        $company = Company::create([
            'name' => 'Tagged Company',
            'email' => 'tagged-company@test.mk',
            'phone' => '070123456',
            'description' => 'Tagged company',
        ]);

        $user = User::create([
            'name' => 'Employer User',
            'email' => 'employer-tags@test.mk',
            'password' => 'password123',
            'is_admin' => false,
            'company_id' => $company->id,
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $urgentTag = Tag::create(['name' => 'Итно', 'slug' => 'itno']);
        $weekendTag = Tag::create(['name' => 'Викенд работа', 'slug' => 'vikend-rabota']);

        $this->actingAs($user)
            ->get(route('employer.jobs.create'))
            ->assertOk()
            ->assertSee('Итно')
            ->assertSee('Викенд работа');

        $this->actingAs($user)
            ->post(route('employer.jobs.store'), [
                'title' => 'Employer tagged job',
                'location' => 'Скопје',
                'category' => 'Промоции',
                'tag_ids' => [$urgentTag->id, $weekendTag->id],
            ])
            ->assertRedirect(route('employer.jobs.index'));

        $job = JobListing::query()->firstOrFail();

        $this->assertSame(
            [$urgentTag->id, $weekendTag->id],
            $job->tags()->orderBy('tags.id')->pluck('tags.id')->all()
        );
    }

    public function test_public_jobs_can_be_filtered_by_dynamic_tag_slug(): void
    {
        $this->createTagTables();

        $company = Company::create([
            'name' => 'Filter Company',
            'email' => 'filter-company@test.mk',
            'phone' => '070123456',
            'description' => 'Filter company',
        ]);

        $urgentTag = Tag::create(['name' => 'Итно', 'slug' => 'itno']);
        $weekendTag = Tag::create(['name' => 'Викенд работа', 'slug' => 'vikend-rabota']);

        $taggedJob = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Итен оглас',
            'slug' => 'iten-oglas',
            'description' => 'Опис за итен оглас.',
            'location' => 'Скопје',
            'category' => 'Промоции',
            'status' => JobListing::STATUS_ACTIVE,
        ]);
        $taggedJob->tags()->sync([$urgentTag->id]);

        $otherJob = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Викенд оглас',
            'slug' => 'vikend-oglas',
            'description' => 'Опис за викенд оглас.',
            'location' => 'Битола',
            'category' => 'Магацин',
            'status' => JobListing::STATUS_ACTIVE,
        ]);
        $otherJob->tags()->sync([$weekendTag->id]);

        $this->get(route('jobs.index', ['tags' => ['itno']]))
            ->assertOk()
            ->assertSee('Итен оглас')
            ->assertDontSee('Викенд оглас')
            ->assertSee('Итно');

        $this->get(route('jobs.show', $taggedJob->slug))
            ->assertOk()
            ->assertSee('Итно')
            ->assertSee(route('jobs.index', ['tags' => ['itno']]), false);
    }

    private function createTagTables(): void
    {
        if (! Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table): void {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('job_listing_tag')) {
            Schema::create('job_listing_tag', function (Blueprint $table): void {
                $table->foreignId('job_listing_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['job_listing_id', 'tag_id']);
            });
        }
    }
}
