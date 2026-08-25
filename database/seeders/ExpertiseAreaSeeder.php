<?php

namespace Database\Seeders;

use App\Models\ExpertiseArea;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpertiseAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expertiseAreas = [
            /*
            |--------------------------------------------------------------------------
            | Expertise Areas for user
            |--------------------------------------------------------------------------
            */
            [
                'description' => 'Desenvolvedor Front-end',
                'slug' => 'desenvolvedor_front_end',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'Desenvolvedor Back-end',
                'slug' => 'desenvolvedor_back_end',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'Desenvolvedor FullStack',
                'slug' => 'desenvolvedor_full_stack',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'UX/UI',
                'slug' => 'ux_ui',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'Infraestrutura',
                'slug' => 'infraestrutura',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'Suporte Técnico',
                'slug' => 'suporte_tecnico',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'BI',
                'slug' => 'bi',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'Product Owner',
                'slug' => 'product_owner',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'Assessoria',
                'slug' => 'assessoria',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'description' => 'Geolocalização',
                'slug' => 'geolocalizacao',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        foreach ($expertiseAreas as $value) {
            ExpertiseArea::firstOrCreate([
                'description' => $value['description'],
                'slug' => $value['slug'],
            ]);
        }
    }
}
