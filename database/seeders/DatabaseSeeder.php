<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RateSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SpatieSeeder;
use Database\Seeders\SystemAccountSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
        $this->call([UserSeeder::class]);
        //$this->call([SpatieSeeder::class]);
        //$this->call([RateSeeder::class]);
        $this->call([SystemAccountSeeder::class]);

        // \App\Models\User::factory(10)->create();
    }
}
