<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            RestaurantsTableSeeder::class,
            ReviewsTableSeeder::class,
            ReviewPhotosTableSeeder::class,
        ]);
    }
}
