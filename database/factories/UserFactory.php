<?php

namespace Database\Factories;

use App\Enum\UserGender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'document' => fake()->unique()->numerify('###.###.###-##'),
            'phone' => fake()->phoneNumber(),
            'billing_address' => fake()->streetName(),
            'login' => fake()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'balance' => fake()->randomFloat(2, 0, 1000),
            'remember_token' => Str::random(10),
        ];
    }
}
