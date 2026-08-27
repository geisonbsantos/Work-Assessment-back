<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    public function definition(): array
    {
        $name = 'PERFIL '.Str::upper(Str::random(6));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
