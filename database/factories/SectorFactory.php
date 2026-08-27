<?php

namespace Database\Factories;

use App\Models\Unity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sector>
 */
class SectorFactory extends Factory
{
    public function definition(): array
    {
        $descricao = 'Setor '.Str::upper(Str::random(6));

        return [
            'description' => $descricao,
            'slug' => Str::slug($descricao),
            'unity_id' => Unity::factory(),
        ];
    }
}
