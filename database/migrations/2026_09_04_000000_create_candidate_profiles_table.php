<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('candidate_profiles')) {
            return;
        }

        Schema::create('candidate_profiles', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender', 20);
            $table->date('date_of_birth');
            $table->string('city');
            $table->string('neighbourhood');
            $table->string('exact_address')->nullable();
            $table->string('phone');
            $table->string('phone_normalized', 15)->unique();
            $table->string('email');
            $table->string('driving_status', 20);
            $table->string('preferred_radius', 10);
            $table->json('engagement_types');
            $table->json('work_categories');
            $table->string('other_work_preference')->nullable();
            $table->text('additional_information')->nullable();
            $table->boolean('current_employment_status');
            $table->string('status', 20)->default('new');
            $table->text('admin_notes')->nullable();
            $table->string('privacy_policy_version');
            $table->timestamp('privacy_acknowledged_at');
            $table->timestamp('employer_contact_consented_at');
            $table->timestamp('consent_withdrawn_at')->nullable();
            $table->timestamp('last_confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
