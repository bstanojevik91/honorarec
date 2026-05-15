<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('job_listings', 'job_image')) {
            Schema::table('job_listings', function (Blueprint $table): void {
                $table->string('job_image')->nullable()->after('engagement_type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('job_listings', 'job_image')) {
            Schema::table('job_listings', function (Blueprint $table): void {
                $table->dropColumn('job_image');
            });
        }
    }
};
