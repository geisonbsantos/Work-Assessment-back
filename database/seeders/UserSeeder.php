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
                'password' => Hash::make('geral763'),
            ],
            [
                'name' => 'Develop',
                'cpf' => '12312312387',
                'email' => 'develop@saude.ba.gov.br',
                'profile_id' => '1',
                'password' => Hash::make('develop763'),
            ],
        ];
        User::insert($user);
    }
}
