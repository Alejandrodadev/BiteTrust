<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RestaurantsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('restaurants')->insert([
            [
                'name' => 'Restaurante La Esquina',
                'address' => 'Calle Falsa 123',
                'city' => 'Madrid',
                'country' => 'España',
                'google_place_id' => 'ChIJd8BlQ2BZwokRAFUEcm_qrcA', // ID real o ficticio
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pizza Express',
                'address' => 'Avenida Principal 456',
                'city' => 'Barcelona',
                'country' => 'España',
                'google_place_id' => 'ChIJN1t_tDeuEmsRUsoyG83frY4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hamburguesas Deluxe',
                'address' => 'Gran Vía 789',
                'city' => 'Madrid',
                'country' => 'España',
                'google_place_id' => 'QyTIJN1t_tDeuEmsRUsoyGeead4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
