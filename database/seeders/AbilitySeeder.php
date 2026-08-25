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
            ],
            [
                'name' => 'Cadastrar usuário',
                'slug' => 'cad_usuario',
            ],
            [
                'name' => 'Deletar usuário',
                'slug' => 'del_usuario',
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for profile
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar perfis',
                'slug' => 'list_perfil',
            ],
            [
                'name' => 'Cadastrar perfil',
                'slug' => 'cad_perfil',
            ],
            [
                'name' => 'Deletar perfil',
                'slug' => 'del_perfil',
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for abilities
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar habilidade',
                'slug' => 'list_habilidade',
            ],
            [
                'name' => 'Cadastrar habilidade',
                'slug' => 'cad_habilidade',
            ],
            [
                'name' => 'Deletar habilidade',
                'slug' => 'del_habilidade',
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for faq
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar perguntas frequentes',
                'slug' => 'list_faqs',
            ],
            [
                'name' => 'Cadastrar perguntas frequentes',
                'slug' => 'cad_faqs',
            ],
            [
                'name' => 'Deletar perguntas frequentes',
                'slug' => 'del_faqs',
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for CustomLog
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar logs',
                'slug' => 'list_logs',
            ],
             [
                'name' => 'Cadastrar logs',
                'slug' => 'cad_logs',
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for ExpertiseArea
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar areas de expertise',
                'slug' => 'list_areas_expertise',
            ],
            [
                'name' => 'Cadastrar areas de expertise',
                'slug' => 'cad_areas_expertise',
            ],
            [
                'name' => 'Deletar areas de expertise',
                'slug' => 'del_areas_expertise',
            ],
            /*
            |--------------------------------------------------------------------------
            | Abilities for unities
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar unities',
                'slug' => 'list_unities',
            ],
            [
                'name' => 'Cadastrar unities',
                'slug' => 'cad_unities',
            ],
            [
                'name' => 'Deletar unities',
                'slug' => 'del_unities',
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
