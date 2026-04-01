<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@honorarec.mk',
        ], [
            'name' => 'Honorarec Admin',
            'password' => Hash::make('admin12345'),
            'is_admin' => true,
        ]);

        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'company_id' => null,
        ]);

        $marketPlus = Company::updateOrCreate([
            'email' => 'info@marketplus.mk',
        ], [
            'name' => 'Маркет Плус',
            'phone' => '070 123 456',
            'description' => 'Компанија за промоции, продажба и теренски активности.',
        ]);

        $logistik = Company::updateOrCreate([
            'email' => 'kontakt@logistik.mk',
        ], [
            'name' => 'Логистик Дооел',
            'phone' => '071 222 333',
            'description' => 'Сезонски и логистички ангажмани низ цела Македонија.',
        ]);

        $eventPartners = Company::updateOrCreate([
            'email' => 'hello@eventpartners.mk',
        ], [
            'name' => 'Евент Партнери',
            'phone' => '075 444 999',
            'description' => 'Организација на настани и промотивни активности.',
        ]);

        User::updateOrCreate([
            'email' => 'company@honorarec.mk',
        ], [
            'name' => 'Маркет Плус Корисник',
            'password' => Hash::make('company12345'),
            'is_admin' => false,
            'company_id' => $marketPlus->id,
        ]);

        $promoJob = JobListing::updateOrCreate([
            'slug' => 'promoter-za-vikend-aktivnost',
        ], [
            'company_id' => $marketPlus->id,
            'title' => 'Промотер за викенд активност',
            'description' => 'Потребно е лице за промотивна активност во маркет за викенд смена.',
            'daily_pay' => 1500,
            'location' => 'Скопје',
            'category' => 'Промоции',
            'featured' => true,
            'status' => 'active',
            'expires_at' => now()->addDays(15)->toDateString(),
        ]);

        $warehouseJob = JobListing::updateOrCreate([
            'slug' => 'magacioner-za-sezonska-rabota',
        ], [
            'company_id' => $logistik->id,
            'title' => 'Магационер за сезонска работа',
            'description' => 'Ангажман во магацин со можност за продолжување на соработката.',
            'daily_pay' => 1800,
            'location' => 'Битола',
            'category' => 'Магацин',
            'featured' => false,
            'status' => 'active',
            'expires_at' => now()->addDays(20)->toDateString(),
        ]);

        $eventJob = JobListing::updateOrCreate([
            'slug' => 'asistent-za-nastan-i-registracija',
        ], [
            'company_id' => $eventPartners->id,
            'title' => 'Асистент за настан и регистрација',
            'description' => 'Поддршка за настан, регистрација на гости и координација на терен.',
            'daily_pay' => 2000,
            'location' => 'Скопје',
            'category' => 'Настани',
            'featured' => true,
            'status' => 'paused',
            'expires_at' => now()->addDays(10)->toDateString(),
        ]);

        JobApplication::updateOrCreate([
            'job_listing_id' => $promoJob->id,
            'phone' => '070 555 111',
        ], [
            'full_name' => 'Ана Тодоровска',
            'city' => 'Скопје',
            'message' => 'Имам претходно искуство со промотивни активности и работа со клиенти.',
        ]);

        JobApplication::updateOrCreate([
            'job_listing_id' => $warehouseJob->id,
            'phone' => '071 666 222',
        ], [
            'full_name' => 'Игор Стојанов',
            'city' => 'Битола',
            'message' => 'Достапен сум за полн работен ден и сменска работа.',
        ]);

        JobApplication::updateOrCreate([
            'job_listing_id' => $eventJob->id,
            'phone' => '075 777 333',
        ], [
            'full_name' => 'Марија Илиевска',
            'city' => 'Велес',
            'message' => 'Заинтересирана сум за работа на настани и координација со посетители.',
        ]);
    }
}
