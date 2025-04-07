<?php

namespace Database\Seeders;

use App\Models\Restaurant;
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
                'google_place_id' => 'ChIJd8BlQ2BZwokRAFUEcm_qrcA',
                'website' => 'https://laesquina.com',
                'schedule' => json_encode([
                    'lunes' => '10:00-23:00',
                    'martes' => '10:00-23:00',
                    'miercoles' => '10:00-23:00',
                    'jueves' => '10:00-23:00',
                    'viernes' => '10:00-00:00',
                    'sabado' => '10:00-00:00',
                    'domingo' => '10:00-22:00',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pizza Express',
                'address' => 'Avenida Principal 456',
                'city' => 'Barcelona',
                'country' => 'España',
                'google_place_id' => 'ChIJN1t_tDeuEmsRUsoyG83frY4',
                'website' => 'https://pizzaexpress.com',
                'schedule' => json_encode([
                    'lunes' => '11:00-22:00',
                    'martes' => '11:00-22:00',
                    'miercoles' => '11:00-22:00',
                    'jueves' => '11:00-23:00',
                    'viernes' => '11:00-00:00',
                    'sabado' => '11:00-00:00',
                    'domingo' => 'Cerrado',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hamburguesas Deluxe',
                'address' => 'Gran Vía 789',
                'city' => 'Madrid',
                'country' => 'España',
                'google_place_id' => 'QyTIJN1t_tDeuEmsRUsoyGeead4',
                'website' => 'https://hamburguesasdeluxe.com',
                'schedule' => json_encode([
                    'lunes' => '10:00-22:00',
                    'martes' => '10:00-22:00',
                    'miercoles' => '10:00-22:00',
                    'jueves' => '10:00-23:00',
                    'viernes' => '10:00-01:00',
                    'sabado' => '10:00-01:00',
                    'domingo' => '10:00-21:00',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
