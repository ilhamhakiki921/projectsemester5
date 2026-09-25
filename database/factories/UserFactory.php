<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
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
            'nama_lengkap' => fake()->name(),
            'username'     => fake()->unique()->userName(),
            // Model User sudah cast 'password' => 'hashed', jadi cukup isi teks biasa.
            'password'     => static::$password ??= 'password',
            'role'         => 'admin',
            'status'       => 'aktif',
        ];
    }

    /**
     * Indicate that the account is inactive (tidak bisa login).
     */
    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'nonaktif',
        ]);
    }
}
