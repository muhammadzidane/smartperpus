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

                // Seeder bagian Books
                AuthorSeeder::class,
                CategorySeeder::class,
                BookSeeder::class,
                SynopsisSeeder::class,
                PrintedBookStockSeeder::class,

                // Seeder bagian Couriers

            )
        );
    }
}
