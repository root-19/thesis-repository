<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@thesis-atlas.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@thesis-atlas.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
    }
}
