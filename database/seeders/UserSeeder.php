<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Saka Media',
                'password' => Hash::make('1234567890'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}