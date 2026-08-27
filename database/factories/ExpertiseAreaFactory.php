<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpertiseArea>
 */
class ExpertiseAreaFactory extends Factory
{
    public function definition(): array
    {
        $descricao = 'Area '.Str::upper(Str::random(6));

        return [
            'description' => $descricao,
            'slug' => Str::slug($descricao),
        ];
    }
}
