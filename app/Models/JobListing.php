<?php

namespace App\Models;

use App\Support\MacedonianPhone;
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
        'contact_phone',
        'call_enabled',
        'featured',
        'status',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'call_enabled' => 'boolean',
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

    public function callClicks(): HasMany
    {
        return $this->hasMany(JobCallClick::class);
    }

    public function effectiveCallPhone(): ?string
    {
        if (! $this->call_enabled) {
            return null;
        }

        $candidates = [
            $this->contact_phone,
            $this->company?->call_phone,
            MacedonianPhone::sanitize($this->company?->phone),
        ];

        foreach ($candidates as $phone) {
            if (filled($phone) && preg_match(MacedonianPhone::VALIDATION_REGEX, $phone) === 1) {
                return $phone;
            }
        }

        return null;
    }
}
