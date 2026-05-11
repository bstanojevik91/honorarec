<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function jobListings(): BelongsToMany
    {
        return $this->belongsToMany(JobListing::class, 'job_listing_tag', 'tag_id', 'job_listing_id')->withTimestamps();
    }
}
