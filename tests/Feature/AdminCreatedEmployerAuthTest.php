<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreatedEmployerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_employer_account_is_verified_immediately_and_can_log_in(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-company-auth@test.mk',
            'password' => 'password123',
            'is_admin' => true,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        $company = Company::create([
            'name' => 'Immediate Login Company',
            'email' => 'company-auth@test.mk',
            'phone' => '070123456',
            'description' => 'Immediate login company',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.companies.employer-account.store', $company), [
                'email' => 'employer-auth@test.mk',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('admin.companies.edit', $company));

        $employer = $company->user()->firstOrFail();

        $this->assertNotNull($employer->email_verified_at);

        $this->post(route('employer.login.store'), [
            'email' => 'employer-auth@test.mk',
            'password' => 'password123',
        ])->assertRedirect(route('employer.dashboard'));

        $this->assertAuthenticatedAs($employer);
    }
}
