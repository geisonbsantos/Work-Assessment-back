<?php

namespace Database\Seeders;

use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = [
            [
                'name' => 'ADMINISTRADOR'
            ],
            [
                'name' => 'LÍDER TÉCNICO'
            ],
            [
                'name' => 'TRABALHADOR'
            ],
            [
                'name' => 'CHEFE MEDIATO'
            ],
        ];
        foreach ($profiles as $value) {
            Profile::firstOrCreate([
                'name' => $value['name'],
                'slug' => Str::slug($value['name']),
            ]);
        }
    }
}
