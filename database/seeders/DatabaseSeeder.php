<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'operator',
            ],
            [
                'name' => 'Operator SSIS',
                'email' => 'operator@ssis.local',
                'password' => 'password',
                'role' => 'operator',
                'is_active' => true,
            ]
        );
    }
}