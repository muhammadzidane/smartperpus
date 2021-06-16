<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Author;

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
                BookSeeder::class,
                CategorySeeder::class,
                SynopsisSeeder::class,
                BookCategorySeeder::class,
                PrintedBookStockSeeder::class,
                ProvinceSeeder::class,
                CitySeeder::class,
                DistrictSeeder::class,
            )
        );
    }
}
