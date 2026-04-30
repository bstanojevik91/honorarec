<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('job_listings', 'engagement_type')) {
            Schema::table('job_listings', function (Blueprint $table): void {
                $table->string('engagement_type')->nullable()->after('category');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('job_listings', 'engagement_type')) {
            Schema::table('job_listings', function (Blueprint $table): void {
                $table->dropColumn('engagement_type');
            });
        }
    }
};
