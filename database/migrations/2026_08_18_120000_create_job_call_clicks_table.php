<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_call_clicks')) {
            return;
        }

        Schema::create('job_call_clicks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_listing_id')->constrained()->cascadeOnDelete();
            $table->string('visitor_hash', 64);
            $table->unsignedBigInteger('time_bucket');
            $table->string('dedupe_key', 64)->unique('job_call_clicks_dedupe_key_unique');
            $table->timestamps();

            $table->index('job_listing_id');
            $table->index(['job_listing_id', 'created_at']);
            $table->index(['job_listing_id', 'time_bucket']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_call_clicks');
    }
};
