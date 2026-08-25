<?php

namespace Database\Seeders;

use App\Models\ExpertiseArea;
use App\Models\UserExpertiseArea;
use Illuminate\Database\Seeder;

class UserExpertiseAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | The admin ExpertiseArea have all the skills
        |--------------------------------------------------------------------------
        */
       $idExpertiseArea = ExpertiseArea::all('id');
        foreach ($idExpertiseArea as $idExpertiseArea) {
            UserExpertiseArea::firstOrCreate([
                'user_id' => 1,
                'expertise_area_id' => $idExpertiseArea->id,
            ]);
            UserExpertiseArea::firstOrCreate([
                'user_id' => 2,
                'expertise_area_id' => $idExpertiseArea->id,
            ]);
            UserExpertiseArea::firstOrCreate([
                'user_id' => 3,
                'expertise_area_id' => $idExpertiseArea->id,
            ]);
            UserExpertiseArea::firstOrCreate([
                'user_id' => 4,
                'expertise_area_id' => $idExpertiseArea->id,
            ]);

        }
    }
}
