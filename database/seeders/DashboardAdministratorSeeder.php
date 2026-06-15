<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardAdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Dashboard Administrator',
                'email' => 'analytics@abiasmartschools.com',
                'password' => 'Password123!',
                'role' => UserRole::ADMINISTRATOR,
            ]
        );
    }
}
