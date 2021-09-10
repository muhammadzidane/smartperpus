<?php

namespace Database\Seeders;

use App\Models\BookUser;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for ($i = 0; $i < 8; $i++) {
        //     $ran      = array(1, 4);
        //     $randomId = $ran[array_rand($ran, 1)];
        //     $tomorrow = Carbon::now()->addDay(+1);
        //     $faker    = Faker::create('id_ID');

        //     $data = array(
        //         'book_id' => 1,
        //         'user_id' => $randomId,
        //         'invoice' => $faker->unique()->numerify('##########'),
        //         'book_version' => 'hard_cover',
        //         'amount' => 2,
        //         'courier_name' => 'jne',
        //         'courier_service' => 'JNE Super Faster',
        //         'shipping_cost' => 15000,
        //         'note' => 'jangan di buang barangnya',
        //         'insurance' => 1000,
        //         'unique_code' => 123,
        //         'total_payment' => 41123,
        //         'payment_method' => 'Transfer Bank Bri',
        //         'payment_status' => 'waiting_for_confirmation',
        //         'completed_date' => null,
        //         'payment_deadline' => $tomorrow->format('Y-m-d H:i:s'),
        //         'upload_payment_image' => 'example.jpg',
        //         'confirmed_payment' => false,
        //     );

        //     BookUser::create($data);
        // }
    }
}
