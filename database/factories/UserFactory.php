<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'father_name' => fake()->firstNameMale(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'student',
            'password' => static::$password ??= Hash::make('password'),
            'date_of_birth' => fake()->date(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function teacher(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'teacher',
        ]);
    }

    public function supervisor(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'supervisor',
        ]);
    }

    public function student(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'student',
        ]);
    }
}