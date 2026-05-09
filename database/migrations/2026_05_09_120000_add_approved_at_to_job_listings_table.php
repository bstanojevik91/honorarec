<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('job_listings', 'approved_at')) {
            Schema::table('job_listings', function (Blueprint $table): void {
                $table->dateTime('approved_at')->nullable()->after('status');
            });
        }

        if (Schema::hasColumn('job_listings', 'expires_at')) {
            $driver = Schema::getConnection()->getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                DB::statement('ALTER TABLE job_listings MODIFY expires_at DATETIME NULL');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('job_listings', 'approved_at')) {
            Schema::table('job_listings', function (Blueprint $table): void {
                $table->dropColumn('approved_at');
            });
        }

        if (Schema::hasColumn('job_listings', 'expires_at')) {
            $driver = Schema::getConnection()->getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                DB::statement('ALTER TABLE job_listings MODIFY expires_at DATE NULL');
            }
        }
    }
};
