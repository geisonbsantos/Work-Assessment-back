<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectors = [
            [
                'description' => 'ADMINISTRAÇÃO - administracao-geral',
                'slug' => 'administracao',
                'unity_id' => 1,
            ],
            [
                'description' => 'DIRETORIA - dma',
                'slug' => 'diretoria',
                'unity_id' => 2,
            ],
            [
                'description' => 'COSIS - dma',
                'slug' => 'cosis',
                'unity_id' => 2,
            ],
            [
                'description' => 'INFRA - dma',
                'slug' => 'infra',
                'unity_id' => 2,
            ],
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate([
                'description' => $sector['description'],
                'slug' => $sector['slug'],
                'unity_id' => $sector['unity_id'],
            ]);
        }
    }
}
