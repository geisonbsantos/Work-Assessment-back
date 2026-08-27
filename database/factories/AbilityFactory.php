<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ability>
 */
class AbilityFactory extends Factory
{
    public function definition(): array
    {
        $sufixo = Str::lower(Str::random(6));

        return [
            'name' => 'Habilidade '.$sufixo,
            'slug' => 'hab_'.$sufixo,
        ];
    }
}
