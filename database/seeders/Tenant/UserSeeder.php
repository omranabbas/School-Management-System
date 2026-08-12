<?php

namespace Database\Seeders\Tenant;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Password123!');

        User::updateOrCreate(
            ['email' => 'admin@future-school.test'],
            [
                'name' => 'School',
                'last_name' => 'Admin',
                'father_name' => 'System',
                'role' => 'admin',
                'password' => $password,
                'date_of_birth' => '1985-01-01',
            ]
        );

        $supervisors = [
            [
                'email' => 'supervisor.primary@future-school.test',
                'name' => 'Ahmad',
                'last_name' => 'Ali',
                'father_name' => 'Hassan',
                'date_of_birth' => '1980-01-01',
            ],
            [
                'email' => 'supervisor.preparatory@future-school.test',
                'name' => 'Omar',
                'last_name' => 'Khaled',
                'father_name' => 'Mahmoud',
                'date_of_birth' => '1979-03-15',
            ],
            [
                'email' => 'supervisor.secondary@future-school.test',
                'name' => 'Mohammad',
                'last_name' => 'Samir',
                'father_name' => 'Ali',
                'date_of_birth' => '1978-06-20',
            ],
        ];

        foreach ($supervisors as $supervisor) {
            User::updateOrCreate(
                ['email' => $supervisor['email']],
                array_merge($supervisor, [
                    'role' => 'supervisor',
                    'password' => $password,
                ])
            );
        }

        $teachers = [
            ['email' => 'teacher1@future-school.test', 'name' => 'Teacher', 'last_name' => 'One'],
            ['email' => 'teacher2@future-school.test', 'name' => 'Teacher', 'last_name' => 'Two'],
            ['email' => 'teacher3@future-school.test', 'name' => 'Teacher', 'last_name' => 'Three'],
            ['email' => 'teacher4@future-school.test', 'name' => 'Teacher', 'last_name' => 'Four'],
            ['email' => 'teacher5@future-school.test', 'name' => 'Teacher', 'last_name' => 'Five'],
            ['email' => 'teacher6@future-school.test', 'name' => 'Teacher', 'last_name' => 'Six'],
        ];

        foreach ($teachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'last_name' => $teacher['last_name'],
                    'father_name' => 'Father',
                    'role' => 'teacher',
                    'password' => $password,
                    'date_of_birth' => '1990-01-01',
                ]
            );
        }

        for ($i = 1; $i <= 12; $i++) {
            User::updateOrCreate(
                ['email' => "student{$i}@future-school.test"],
                [
                    'name' => "Student",
                    'last_name' => "Number {$i}",
                    'father_name' => "Father {$i}",
                    'role' => 'student',
                    'password' => $password,
                    'date_of_birth' => '2010-01-01',
                ]
            );
        }
    }
}