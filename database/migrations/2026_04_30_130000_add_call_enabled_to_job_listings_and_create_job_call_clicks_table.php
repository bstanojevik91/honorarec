<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table): void {
            $table->boolean('call_enabled')->default(false)->after('contact_phone');
        });

        Schema::create('job_call_clicks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_listing_id')->constrained()->cascadeOnDelete();
            $table->string('phone_dialed');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer_url')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_call_clicks');

        Schema::table('job_listings', function (Blueprint $table): void {
            $table->dropColumn('call_enabled');
        });
    }
};
