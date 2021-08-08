<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book, Province, City, User, BookUser, UserChat, AdminChat, Category};
use Faker\Factory as Faker;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $faker = \Faker\Factory::create('id_ID');
        $author_count = Author::get()->count();
        $realese_date = $faker->dateTime()->format('Y-m-d');
        $subtitle     = $faker->country();

        $book =  Book::create(
            array(
                'isbn'               => $faker->unique()->isbn13(),
                'category_id'        => $faker->numberBetween(1, 22), // Sesuai dengan jumlah kategori
                'printed_book_stock' => $faker->numberBetween(1, 100),
                'name'               => $faker->sentence(),
                'price'              => $faker->numberBetween(25000, 200000),
                'image'              => 'book-example-' . $faker->numberBetween(1, 25) . '.jpg',
                'author_id'          => $faker->numberBetween(1, $author_count),
                'rating'             => null,
                'discount'           => 0,
                'ebook'              => false,
                'pages'              => $faker->numberBetween(200, 700),
                'release_date'       => $realese_date,
                'publisher'          => $faker->sentence(),
                'subtitle'           => $subtitle,
                'weight'             => $faker->numberBetween(200, 500),
                'width'              => $faker->numberBetween(20, 25),
                'height'             => $faker->numberBetween(25, 29),
            )
        );

        $create = array('text' => $faker->paragraphs(9));

        $book->synopsis()->create($create);
    }

    public function testPost(Request $request)
    {
        // $request->photo->store('public/test');
        // return response()->json(array('photo' => $request->photo));
        dd(true);
    }

    public function pagination()
    {
        return view('pagination');
    }

    public function ajaxRequestStore()
    {
        return 'wkwkwk';
    }

    public function index()
    {
        $msg = 'this is a simple message';
        return response()->json(array('msg' => $msg), 200);
    }

    public function testCurl()
    {
        // $curlopt_postfield  = 'origin=' . $request->origin_id . '&originType=' . $request->origin_type;
        // $curlopt_postfield .= '&destination=' . $request->destination_id . '&destinationType=' . $request->destination_type;
        // $curlopt_postfield .= '&weight=' . $request->weight . '&courier=' . $request->courier;
        $curlopt_postfield =

            $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $curlopt_postfield,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: ce496165f4a20bc07d96b6fe3ab41ded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}
