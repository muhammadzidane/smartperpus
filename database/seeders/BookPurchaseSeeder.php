<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Faker\Factory as Faker;
use App\Models\{ User, Book };

class BookPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i=1; $i <= 3 ; $i++) {
            $book  = Book::find($i);
            $user  = User::find($i);
            $data  = array(
                'invoice'          => $faker->unique()->numerify('000#######'),
                'book_version'     => 'hard_cover',
                'amount'           => $faker->numberBetween(1, 10),
                'courier_name'     => 'Jne',
                'courier_service'  => 'JNE Fast',
                'shipping_cost'    => 10000,
                'note'             => null,
                'insurance'        => 1000,
                'unique_code'      => 320,
                'total_payment'    => 50000,
                'payment_method'   => 'Transfer Bank BRI',
                'payment_status'   => 'waiting_for_payment',
                'payment_deadline' => Date::now()->addDays(1)->format('Y-m-d H:i:s'),
            );

            $book->users()->attach($user, $data);
        }
    }
}
