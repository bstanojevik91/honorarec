<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateProfile extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_NEW = 'new';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_PLACED = 'placed';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_WITHDRAWN = 'withdrawn';
    public const STATUS_SPAM = 'spam';

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'date_of_birth', 'city', 'neighbourhood',
        'exact_address', 'phone', 'phone_normalized', 'email', 'driving_status',
        'preferred_radius', 'engagement_types', 'work_categories', 'other_work_preference',
        'additional_information', 'current_employment_status', 'status', 'admin_notes',
        'privacy_policy_version', 'privacy_acknowledged_at', 'employer_contact_consented_at',
        'consent_withdrawn_at', 'last_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'engagement_types' => 'array',
            'work_categories' => 'array',
            'current_employment_status' => 'boolean',
            'privacy_acknowledged_at' => 'datetime',
            'employer_contact_consented_at' => 'datetime',
            'consent_withdrawn_at' => 'datetime',
            'last_confirmed_at' => 'datetime',
        ];
    }

    public static function genderOptions(): array
    {
        return ['male' => 'Машки', 'female' => 'Женски', 'not_specified' => 'Не сакам да наведам'];
    }

    public static function radiusOptions(): array
    {
        return ['1' => 'До 1 km', '5' => 'До 5 km', '10' => 'До 10 km', '15' => 'До 15 km', '15_plus' => '15+ km'];
    }

    public static function drivingStatusOptions(): array
    {
        return [
            'active_driver' => 'Имам возачка дозвола и активно возам',
            'inactive_driver' => 'Имам возачка дозвола, но не возам активно',
            'no_licence' => 'Немам возачка дозвола',
        ];
    }

    public static function engagementTypeOptions(): array
    {
        return [
            'daily' => 'Работа на дневница', 'weekend' => 'Викенд-ангажман',
            'seasonal' => 'Сезонска работа', 'flexible' => 'Флексибилна работа',
            'part_time' => 'Работа со скратено работно време',
            'full_time' => 'Работа со полно работно време', 'all' => 'Сè од наведеното',
        ];
    }

    public static function workCategoryOptions(): array
    {
        return [
            'hospitality' => 'Угостителство', 'physical_work' => 'Физичка работа',
            'logistics_transport' => 'Логистика и транспорт', 'administration' => 'Административна работа',
            'creative' => 'Креативна работа', 'sales_retail' => 'Продажба и трговија',
            'production' => 'Производство и фабричка работа', 'construction_trades' => 'Градежништво и занаети',
            'cleaning_maintenance' => 'Чистење и одржување', 'customer_support' => 'Корисничка поддршка',
            'events_promotions' => 'Настани и промоции', 'agriculture_seasonal' => 'Земјоделство и сезонска работа',
            'care' => 'Нега и грижа', 'it_digital' => 'IT и дигитална работа', 'other' => 'Останато',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => 'Нов / непроверен', self::STATUS_ACTIVE => 'Активен',
            self::STATUS_CONTACTED => 'Контактиран', self::STATUS_PLACED => 'Ангажиран',
            self::STATUS_INACTIVE => 'Неактивен', self::STATUS_WITHDRAWN => 'Повлечен',
            self::STATUS_SPAM => 'Спам / невалиден',
        ];
    }

    public function age(): ?int
    {
        return $this->date_of_birth?->age;
    }
}
