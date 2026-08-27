<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\Sector;
use App\Models\Unity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The password used across factory users ("password").
     */
    public const PASSWORD = 'password';

    /**
     * CPF válido (com dígitos verificadores corretos) e sem máscara.
     */
    public static function cpf(): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[$i] = random_int(0, 9);
        }
        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($c = 0; $c < $t; $c++) {
                $soma += $n[$c] * (($t + 1) - $c);
            }
            $n[$t] = ((10 * $soma) % 11) % 10;
        }

        return implode('', $n);
    }

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'cpf' => self::cpf(),
            'email' => Str::lower(Str::random(12)).'@example.test',
            'profile_id' => Profile::factory(),
            'unity_id' => Unity::factory(),
            'sector_id' => Sector::factory(),
            'email_verified_at' => now(),
            'password' => self::PASSWORD, // hashed pelo mutator do model
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => ['email_verified_at' => null]);
    }

    /**
     * Usuário no perfil ADMINISTRADOR (id 1, semeado por ProfileSeeder).
     */
    public function administrador(): static
    {
        return $this->state(fn (array $attributes) => ['profile_id' => 1]);
    }
}
