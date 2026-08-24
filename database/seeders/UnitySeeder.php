<?php

namespace Database\Seeders;

use App\Models\Unity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UnitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unities = [
            [
                'description' => 'ADMINISTRAÇÃO GERAL',
                'cnes' => '1234567',
                'municipality' => 'Salvador',
                'slug' => 'administracao-geral',
            ],
            [
                'description' => 'DMA',
                'cnes' => '2345678',
                'municipality' => 'Salvador',
                'slug' => 'dma',
            ],
        ];

        foreach ($unities as $unity) {
            Unity::firstOrCreate([
                'description' => $unity['description'],
                'slug' => $unity['slug'],
                'cnes' => $unity['cnes'],
                'municipality' => $unity['municipality'],
            ]);
        }
    }
}
