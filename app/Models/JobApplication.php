<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_listing_id',
        'full_name',
        'phone',
        'phone_normalized',
        'city',
        'message',
        'cv_path',
    ];

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }
}
