<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class JobListing extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_FILLED = 'filled';
    public const STATUS_REJECTED = 'rejected';
    public const ENGAGEMENT_DAILY = 'На дневница';
    public const ENGAGEMENT_PART_TIME = 'Part-time';
    public const ENGAGEMENT_WEEKENDS = 'Викенд работа';
    public const ENGAGEMENT_SEASONAL = 'Сезонска работа';
    public const ENGAGEMENT_FULL_TIME = 'Полно работно време';

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'description',
        'daily_pay',
        'location',
        'category',
        'engagement_type',
        'featured',
        'status',
        'approved_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'approved_at' => 'datetime',
            'expires_at' => 'datetime',
            'daily_pay' => 'decimal:2',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Чека одобрување',
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_PAUSED => 'Паузиран',
            self::STATUS_FILLED => 'Пополнет',
            self::STATUS_REJECTED => 'Одбиен',
        ];
    }

    public static function engagementTypeOptions(): array
    {
        return [
            self::ENGAGEMENT_DAILY,
            self::ENGAGEMENT_PART_TIME,
            self::ENGAGEMENT_WEEKENDS,
            self::ENGAGEMENT_SEASONAL,
            self::ENGAGEMENT_FULL_TIME,
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

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null
            && now()->greaterThanOrEqualTo($this->expires_at->copy()->startOfDay());
    }

    public function remainingDays(): ?int
    {
        if (! $this->isApproved() || $this->expires_at === null || $this->isExpired()) {
            return null;
        }

        return now()->startOfDay()->diffInDays($this->expires_at->copy()->startOfDay(), false);
    }

    public function approvedAtLabel(): ?string
    {
        return $this->approved_at?->format('d.m.Y');
    }

    public function expiresAtLabel(): ?string
    {
        return $this->expires_at?->format('d.m.Y');
    }

    public function createdAtLabel(): ?string
    {
        return $this->created_at?->format('d.m.Y');
    }

    public function employerLifecycleMessage(): string
    {
        if (! $this->isApproved()) {
            return 'Огласот чека одобрување';
        }

        if ($this->isExpired()) {
            return 'Огласот е истечен';
        }

        $remainingDays = $this->remainingDays();

        return $remainingDays === null
            ? 'Нема поставен рок на истекување'
            : 'Имате уште ' . $remainingDays . ' дена';
    }

    public function defaultApprovalExpiry(Carbon $approvedAt): Carbon
    {
        return $approvedAt->copy()->addDays(30)->startOfDay();
    }
}
