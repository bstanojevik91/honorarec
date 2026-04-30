<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCallClick extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'job_listing_id',
        'phone_dialed',
        'ip_address',
        'user_agent',
        'referer_url',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }
}
