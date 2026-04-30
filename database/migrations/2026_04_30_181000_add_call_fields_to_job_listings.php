<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table): void {
            if (! Schema::hasColumn('job_listings', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('category');
            }

            if (! Schema::hasColumn('job_listings', 'call_enabled')) {
                $table->boolean('call_enabled')->default(false)->after('contact_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table): void {
            if (Schema::hasColumn('job_listings', 'call_enabled')) {
                $table->dropColumn('call_enabled');
            }

            if (Schema::hasColumn('job_listings', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
        });
    }
};
