<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE job_listings MODIFY status ENUM('pending', 'active', 'paused', 'filled', 'rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE job_listings SET status = 'paused' WHERE status IN ('pending', 'rejected')");
            DB::statement("ALTER TABLE job_listings MODIFY status ENUM('active', 'paused', 'filled') NOT NULL DEFAULT 'active'");
        }
    }
};
