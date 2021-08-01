<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            array(
                // User Seeder
                UserSeeder::class,

                // Seeder bagian Books
                AuthorSeeder::class,
                CategorySeeder::class,
                BookSeeder::class,
                SynopsisSeeder::class,
                ProvinceSeeder::class,
                CitySeeder::class,
                BookUserSeeder::class,
                // DistrictSeeder::class,
            )
        );
    }
}
