<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class JobExpiryControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_create_and_edit_forms_do_not_show_expiry_date_field(): void
    {
        $company = Company::create([
            'name' => 'Employer Company',
            'email' => 'employer-company@test.mk',
            'phone' => '070123456',
            'description' => 'Employer company',
        ]);

        $user = User::create([
            'name' => 'Employer User',
            'email' => 'employer@test.mk',
            'password' => 'password123',
            'is_admin' => false,
            'company_id' => $company->id,
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Employer Job',
            'slug' => 'employer-job',
            'description' => 'Employer job description',
            'location' => 'Скопје',
            'category' => 'Промоции',
            'status' => JobListing::STATUS_ACTIVE,
            'expires_at' => now()->addDays(10),
        ]);

        $this->actingAs($user)
            ->get(route('employer.jobs.create'))
            ->assertOk()
            ->assertDontSee('name="expires_at"', false);

        $this->actingAs($user)
            ->get(route('employer.jobs.edit', $job))
            ->assertOk()
            ->assertDontSee('name="expires_at"', false);
    }

    public function test_admin_create_and_edit_forms_still_show_expiry_date_field(): void
    {
        $company = Company::create([
            'name' => 'Admin Company',
            'email' => 'admin-company@test.mk',
            'phone' => '070123456',
            'description' => 'Admin company',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.mk',
            'password' => 'password123',
            'is_admin' => true,
            'company_id' => null,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Admin Job',
            'slug' => 'admin-job',
            'description' => 'Admin job description',
            'location' => 'Битола',
            'category' => 'Промоции',
            'status' => JobListing::STATUS_ACTIVE,
            'expires_at' => now()->addDays(10),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.jobs.create'))
            ->assertOk()
            ->assertSee('name="expires_at"', false);

        $this->actingAs($admin)
            ->get(route('admin.jobs.edit', $job))
            ->assertOk()
            ->assertSee('name="expires_at"', false);
    }

    public function test_employer_store_ignores_manual_expiry_until_admin_approval(): void
    {
        Carbon::setTestNow('2026-05-09 10:00:00');

        $company = Company::create([
            'name' => 'Store Company',
            'email' => 'store-company@test.mk',
            'phone' => '070123456',
            'description' => 'Store company',
        ]);

        $user = User::create([
            'name' => 'Store Employer',
            'email' => 'store-employer@test.mk',
            'password' => 'password123',
            'is_admin' => false,
            'company_id' => $company->id,
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $this->actingAs($user)
            ->post(route('employer.jobs.store'), [
                'title' => 'Ново employer оглас',
                'location' => 'Скопје',
                'category' => 'Промоции',
                'expires_at' => '2035-01-01',
            ])
            ->assertRedirect(route('employer.jobs.index'));

        $job = JobListing::query()->where('title', 'Ново employer оглас')->firstOrFail();

        $this->assertSame(JobListing::STATUS_PENDING, $job->status);
        $this->assertNull($job->approved_at);
        $this->assertNull($job->expires_at);

        Carbon::setTestNow();
    }

    public function test_employer_update_cannot_change_existing_expiry_date(): void
    {
        $company = Company::create([
            'name' => 'Update Company',
            'email' => 'update-company@test.mk',
            'phone' => '070123456',
            'description' => 'Update company',
        ]);

        $user = User::create([
            'name' => 'Update Employer',
            'email' => 'update-employer@test.mk',
            'password' => 'password123',
            'is_admin' => false,
            'company_id' => $company->id,
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Постоечки employer оглас',
            'slug' => 'postoecki-employer-oglas',
            'description' => 'Опис',
            'location' => 'Скопје',
            'category' => 'Промоции',
            'status' => JobListing::STATUS_ACTIVE,
            'expires_at' => Carbon::parse('2026-06-15'),
        ]);

        $this->actingAs($user)
            ->put(route('employer.jobs.update', $job), [
                'title' => 'Ажуриран employer оглас',
                'slug' => 'postoecki-employer-oglas',
                'location' => 'Битола',
                'category' => 'Магацин',
                'expires_at' => '2035-01-01',
            ])
            ->assertRedirect(route('employer.jobs.index'));

        $job->refresh();

        $this->assertSame('2026-06-15', $job->expires_at?->format('Y-m-d'));
        $this->assertSame('Ажуриран employer оглас', $job->title);
        $this->assertSame('Битола', $job->location);
    }

    public function test_admin_approval_sets_approved_at_and_thirty_day_expiry_only_once(): void
    {
        Carbon::setTestNow('2026-05-09 09:00:00');

        $company = Company::create([
            'name' => 'Approval Company',
            'email' => 'approval-company@test.mk',
            'phone' => '070123456',
            'description' => 'Approval company',
        ]);

        $admin = User::create([
            'name' => 'Approval Admin',
            'email' => 'approval-admin@test.mk',
            'password' => 'password123',
            'is_admin' => true,
            'company_id' => null,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        $job = JobListing::create([
            'company_id' => $company->id,
            'title' => 'Pending Job',
            'slug' => 'pending-job',
            'description' => 'Pending job description',
            'location' => 'Скопје',
            'category' => 'Промоции',
            'status' => JobListing::STATUS_PENDING,
            'approved_at' => null,
            'expires_at' => null,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.jobs.approve', $job))
            ->assertRedirect(route('admin.jobs.index'));

        $job->refresh();

        $this->assertSame(JobListing::STATUS_ACTIVE, $job->status);
        $this->assertSame('2026-05-09 09:00:00', $job->approved_at?->format('Y-m-d H:i:s'));
        $this->assertSame('2026-06-08 00:00:00', $job->expires_at?->format('Y-m-d H:i:s'));

        Carbon::setTestNow('2026-05-15 12:00:00');

        $this->actingAs($admin)
            ->patch(route('admin.jobs.approve', $job))
            ->assertRedirect(route('admin.jobs.index'));

        $job->refresh();

        $this->assertSame('2026-05-09 09:00:00', $job->approved_at?->format('Y-m-d H:i:s'));
        $this->assertSame('2026-06-08 00:00:00', $job->expires_at?->format('Y-m-d H:i:s'));

        Carbon::setTestNow();
    }
}
