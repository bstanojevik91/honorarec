<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_FILLED = 'filled';

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'description',
        'daily_pay',
        'location',
        'category',
        'featured',
        'status',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'expires_at' => 'date',
            'daily_pay' => 'decimal:2',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_PAUSED => 'Паузиран',
            self::STATUS_FILLED => 'Пополнет',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Непознат статус';
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
