<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('job_applications', 'privacy_policy_version')) {
            Schema::table('job_applications', function (Blueprint $table): void {
                $table->string('privacy_policy_version')->nullable()->after('cv_path');
            });
        }

        if (! Schema::hasColumn('job_applications', 'privacy_acknowledged_at')) {
            Schema::table('job_applications', function (Blueprint $table): void {
                $table->timestamp('privacy_acknowledged_at')->nullable()->after('privacy_policy_version');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('job_applications', 'privacy_acknowledged_at')) {
            Schema::table('job_applications', function (Blueprint $table): void {
                $table->dropColumn('privacy_acknowledged_at');
            });
        }

        if (Schema::hasColumn('job_applications', 'privacy_policy_version')) {
            Schema::table('job_applications', function (Blueprint $table): void {
                $table->dropColumn('privacy_policy_version');
            });
        }
    }
};
