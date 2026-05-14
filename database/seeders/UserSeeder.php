<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@school.com',
        ]);

        User::factory()
            ->supervisor()
            ->count(3)
            ->create();

        User::factory()
            ->teacher()
            ->count(15)
            ->create();

        User::factory()
            ->student()
            ->count(100)
            ->create();
    }
}