<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewPhotosTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('review_photos')->insert([
            [
                'review_id' => 1,
                'photo_url' => 'resources/img/rest1.jpg',
                'created_at' => now(),
            ],
            [
                'review_id' => 2,
                'photo_url' => 'resources/img/rest2.jpg',
                'created_at' => now(),
            ],
        ]);
    }
}
