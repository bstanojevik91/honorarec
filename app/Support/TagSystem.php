<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

final class TagSystem
{
    public static function enabled(): bool
    {
        return Schema::hasTable('tags') && Schema::hasTable('job_listing_tag');
    }
}
