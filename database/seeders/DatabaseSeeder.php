<?php

namespace Database\Seeders;

use App\Models\Company;
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
        JobListing::query()
            ->whereIn('slug', [
                'promoter-za-vikend-aktivnost',
                'magacioner-za-sezonska-rabota',
                'asistent-za-nastan-i-registracija',
            ])
            ->delete();

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
    }
}
