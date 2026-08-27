<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unity>
 */
class UnityFactory extends Factory
{
    public function definition(): array
    {
        $sufixo = Str::upper(Str::random(6));
        $descricao = 'Unidade '.$sufixo;

        return [
            'description' => $descricao,
            'slug' => Str::slug($descricao),
            'cnes' => (string) random_int(1000000, 9999999).$sufixo,
            'municipality' => $this->faker->city(),
        ];
    }
}
