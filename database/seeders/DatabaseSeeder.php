<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::insert([
            [
                'username' => 'yafiyoga',
                'password' => bcrypt('N@dia2007'),
                'role' => 'admin',
                'isDeleted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
             
                'username' => 'pakfidi',
                'password' => bcrypt('fidiwputro'),
                'role' => 'guru_sd', // pilih salah satu dari enum
                'isDeleted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
            
    }
}
