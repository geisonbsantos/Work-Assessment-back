<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProfileSeeder::class,
            ExpertiseAreaSeeder::class,
            UnitySeeder::class,
            SectorSeeder::class,
            UserSeeder::class,
            AbilitySeeder::class,
            ProfileAbilitySeeder::class,
            UserExpertiseAreaSeeder::class,
        ]);
    }
}
