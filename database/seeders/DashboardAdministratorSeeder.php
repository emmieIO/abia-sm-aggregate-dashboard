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
        User::create([
            'name' => 'Admin User',
            'username' => 'admin-'.str()->random(5),
            'email' => 'default@mail.com',
            'password' => 'maxsecret123@',
            'role' => UserRole::ADMINISTRATOR->value,
        ]);
    }
}
