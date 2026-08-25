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
        $users = [
            [
                'name' => 'ADMINISTRADOR GERAL',
                'cpf' => '76345028045',
                'email' => 'administrador@saude.ba.gov.br',
                'profile_id' => '1',
                'unity_id' => '1',
                'sector_id' => '1',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'GEISON',
                'cpf' => '90211928534',
                'email' => 'geison.santos@saude.ba.gov.br',
                'profile_id' => '3',
                'unity_id' => '1',
                'sector_id' => '1',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'ISADORA',
                'cpf' => '25631557037',
                'email' => 'isadora.cruz@saude.ba.gov.br',
                'profile_id' => '2',
                'unity_id' => '1',
                'sector_id' => '1',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'PRISCILA',
                'cpf' => '00735177554',
                'email' => 'priscila.macedo@saude.ba.gov.br',
                'profile_id' => '4',
                'unity_id' => '1',
                'sector_id' => '1',
                'password' => Hash::make('123456'),
            ],
        ];

        User::insert($users);

        // foreach ($users as $user) {
        //     User::firstOrCreate([
        //         'name' => $user['name'],
        //         'cpf' => $user['cpf'],
        //         'email' => $user['email'],
        //         'profile_id' => $user['profile_id'],
        //         'unity_id' => $user['unity_id'],
        //         'password' => $user['password'],
        //     ]);
        // }
    }
}
