<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'user_id' => 1,
                'restaurant_id' => 1,
                'rating' => 5,
                'comment' => 'Excelente comida y servicio.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'restaurant_id' => 2,
                'rating' => 4,
                'comment' => 'Buena comida, pero un poco caro.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
