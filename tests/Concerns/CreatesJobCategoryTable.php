<?php

namespace Tests\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CreatesJobCategoryTable
{
    private function createJobCategoryTable(): void
    {
        // Test fixture only. Production schema is installed manually in phpMyAdmin.
        if (Schema::hasTable('job_categories')) {
            return;
        }

        Schema::create('job_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $normalized = $table->string('normalized_name');
            if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $normalized->collation('utf8mb4_bin');
            }
            $table->unique('normalized_name');
            $table->timestamps();
        });
    }
}
