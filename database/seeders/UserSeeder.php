<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'ADMINISTRADOR GERAL',
                'cpf' => '76345028045',
                'email' => 'administrador@saude.ba.gov.br',
                'profile_id' => '1',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'GEISON',
                'cpf' => '90211928534',
                'email' => 'geison.santos@saude.ba.gov.br',
                'profile_id' => '3',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'ISADORA',
                'cpf' => '25631557037',
                'email' => 'isadora.cruz@saude.ba.gov.br',
                'profile_id' => '2',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'PRISCILA',
                'cpf' => '00735177554',
                'email' => 'priscila.macedo@saude.ba.gov.br',
                'profile_id' => '4',
                'password' => Hash::make('123456'),
            ],
        ];
        User::insert($user);
    }
}
