<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'tester@gmail.com'],
            [
                'name' => 'Tester Account',
                'password' => Hash::make('abc123456789'),
            ]
        );
    }
}

