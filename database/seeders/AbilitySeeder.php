<?php

namespace Database\Seeders;

use App\Models\Ability;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abilities = [
            /*
            |--------------------------------------------------------------------------
            | Abilities for user
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar usuários',
                'slug' => 'list_usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cadastrar usuário',
                'slug' => 'cad_usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Deletar usuário',
                'slug' => 'del_usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for profile
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar perfis',
                'slug' => 'list_perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cadastrar perfil',
                'slug' => 'cad_perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Deletar perfil',
                'slug' => 'del_perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for abilities
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar habilidade',
                'slug' => 'list_habilidade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cadastrar habilidade',
                'slug' => 'cad_habilidade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Deletar habilidade',
                'slug' => 'del_habilidade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for faq
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar perguntas frequentes',
                'slug' => 'list_faqs',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cadastrar perguntas frequentes',
                'slug' => 'cad_faqs',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Deletar perguntas frequentes',
                'slug' => 'del_faqs',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for CustomLog
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar logs',
                'slug' => 'list_logs',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
             [
                'name' => 'Cadastrar logs',
                'slug' => 'cad_logs',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for ExpertiseArea
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar areas de expertise',
                'slug' => 'list_areas_expertise',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cadastrar areas de expertise',
                'slug' => 'cad_areas_expertise',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Deletar areas de expertise',
                'slug' => 'del_areas_expertise',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
          
        ];
        foreach ($abilities as $value) {
            Ability::firstOrCreate([
                'name' => $value['name'],
                'slug' => $value['slug'],
            ]);
        }
    }
}
