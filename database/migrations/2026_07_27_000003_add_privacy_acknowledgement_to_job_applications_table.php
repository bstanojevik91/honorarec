<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            $table->string('privacy_policy_version')->nullable()->after('cv_path');
            $table->timestamp('privacy_acknowledged_at')->nullable()->after('privacy_policy_version');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            $table->dropColumn([
                'privacy_policy_version',
                'privacy_acknowledged_at',
            ]);
        });
    }
};
