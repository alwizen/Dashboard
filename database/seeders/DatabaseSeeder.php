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
        User::factory(1)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ]);


        //call BookSeeder
        $this->call(
            [
                DepartmentSeeder::class,
                // BookSeeder::class,
                // PostSeeder::class,
                // ContactSeeder::class,
                ProductSeeder::class,
            ]
        );
    }
}
