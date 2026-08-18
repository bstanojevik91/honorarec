<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCallClick extends Model
{
    protected $fillable = [
        'job_listing_id',
        'visitor_hash',
        'time_bucket',
        'dedupe_key',
    ];

    protected function casts(): array
    {
        return [
            'time_bucket' => 'integer',
        ];
    }

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }
}
