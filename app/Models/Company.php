<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'logo_path',
        'description',
    ];

    public function jobListings(): HasMany
    {
        return $this->hasMany(JobListing::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
