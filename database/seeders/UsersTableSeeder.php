<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Alex',
                'email' => 'alex@example.com',
                'password' =>bcrypt('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        User::factory(10)->create();
    }
}
