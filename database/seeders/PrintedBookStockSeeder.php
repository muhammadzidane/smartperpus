<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\{Book, PrintedBookStock};

class PrintedBookStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i=1; $i <= Book::count(); $i++) {
            PrintedBookStock::create(
                array(
                    'amount'  => $faker->numberBetween(1, 100),
                    'book_id' => $i,
                )
            );
        }
    }
}
